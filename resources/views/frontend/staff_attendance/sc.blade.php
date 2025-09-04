@foreach($dates as $date)
{{--    The below is the section for each cell in the attendance table on the frontend:--}}

    <td style="background-color: @if(isset($attendances[$staff_ara_id][$date['str']]['day_attendance']['manager_auth'])) #a770d1 @else {{$attendances[$staff_ara_id][$date['str']]['day_attendance']['day_schedule']['colour']}} @endif;

    @if($attendances[$staff_ara_id][$date['str']]['day_attendance']['closing_status'] == "closed early") border-bottom: 3px solid red; @endif"
        @if(isset($attendances[$staff_ara_id][$date['str']]['day_attendance']['manager_auth']))
        @php($manager_auth[$staff_ara_id][$date['str']] = $attendances[$staff_ara_id][$date['str']]['day_attendance']['manager_auth'])
        title="{{ $manager_auth[$staff_ara_id][$date['str']]->manager->name }} gave authorization: {{ $manager_auth[$staff_ara_id][$date['str']]->reason }}"
        @endif
    >
        @if(!in_array($date['week_day'], ['Saturday', 'Sunday']) )
            <small>@if($attendances[$staff_ara_id][$date['str']]['day_attendance']['day_schedule']['colour'] == '#F5F5F5')
                    Working remotely @else {{$attendances[$staff_ara_id][$date['str']]['day_attendance']['status']}}


                    {{-- Cummulate stats for latensss and absence --}}
                    @if($attendances[$staff_ara_id][$date['str']]['day_attendance']['status'] == 'late')
                        @php($stats[$staff_ara_id]['late']++)
                    @endif

                    @if($attendances[$staff_ara_id][$date['str']]['day_attendance']['status'] == 'absent')
                        @php($stats[$staff_ara_id]['absent']++)
                    @endif

                @endif</small>
            <br>
        @endif


        <span
            class="text-black-60">{{$attendances[$staff_ara_id][$date['str']]['day_attendance']['resumed']}}</span>
        <br>

        @if(!in_array($date['week_day'], ['Saturday', 'Sunday']) )
            <small>{{$attendances[$staff_ara_id][$date['str']]['day_attendance']['closing_status']}}</small>
        @endif

        @if($attendances[$staff_ara_id][$date['str']]['day_attendance']['closing_status'] != "wasn't clocked out")
            <br>

            <span
                class="text-maroon">{{$attendances[$staff_ara_id][$date['str']]['day_attendance']['closed']}} </span>
        @endif
        @if($attendances[$staff_ara_id][$date['str']]['day_attendance']['day_schedule']['colour'] != '#F5F5F5')
            <hr class="border-secondary"> {{$attendances[$staff_ara_id][$date['str']]['day_attendance']['hours']}}
            hours
        @endif
    </td>
@endforeach


@php

    // Below is the function that assigns default colours in the backend
    public function checkDaySchedule($schedule_all, $date, $attendance_count = null, $first_in_hour = null, $valid_first_in = true)
    {
    $date = Carbon::parse($date);
    if(!is_null($schedule_all)) {
    // Retrieve all schedules for the given week_day
    $schedules = $schedule_all
    ->where('week_day', strtolower($date->format('l')));

    // Filter the results in PHP
    $schedule = $schedules->first(function ($schedule) use ($date) {
    return ($schedule->commenced_on <= $date && ($schedule->ended_on === null || $schedule->ended_on >= $date));
    });


    }else{
    $schedule = null;
    }

    $show = 'show';
    if(is_null($attendance_count) || $attendance_count == 0)
    $show = 'no show';

    $workdays = [
    'monday',
    'tuesday',
    'wednesday',
    'friday',
    'thursday',
    ];

    if(!in_array(strtolower($date->format('l')), $workdays)){
    $location = 'Remote';
    $show = 'weekend';
    }else if(!$schedule || !is_object($schedule) || $schedule->location == 'On duty'){
    $location = 'On duty';
    }else if(!is_null($schedule) && !empty($schedule->location)){
    $location = $schedule->location;
    }else{
    $location = 'On duty';
    }


    $colors = [
    'On duty - no show' => '#FFADAD',
    'On duty - show' => '#FFFFFF',
    'Remote - no show' => '#F5F5F5',
    'Remote - show' => '#FDFFB6',
    'Remote - weekend' => '#CAFFBF',
    'On duty - late' => '#F07166',
    'On duty - not marked in' => '#6FE3E1',
    ];


    if(!is_null($first_in_hour) && $first_in_hour >= 9){
    $color = $colors['On duty - late'];
    }

    if(isset($location) && $location == 'Remote' && !is_null($first_in_hour)){
    $color = $colors['Remote - show'];
    }

    if($location == 'On duty' && !$valid_first_in && $first_in_hour){
    // not marked in but marked out
    $color = $colors['On duty - not marked in'];
    }

    //        if(strtolower($date->format('l')) == 'tuesday')
    //            dd($color);

    $data = [
    'location' => $location,
    'colour' => isset($color) ? $color : ($colors[$location.' - '.$show] ?? '#ffb569'),
    'weekday' => strtolower($date->format('l')),
    'schedule' => $schedule
    ];

    return $data;
    }

@endphp

