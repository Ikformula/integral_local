{{--IT--}}
@include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )
<table class="tableE table-bordered table-hover table-sm mx-auto" id="ba_{{ $business_area_id }}">
    <thead>
        <tr>
            <th colspan="6" class="bg-navy">{{ $business_area->name }} </th>
        </tr>
        <tr style="background-color: #B3CEEB">
            <th>DEPT.</th>
            <th>TRANSACTIONS</th>
            <th>Week {{ $week_in_focus->week_number }}</th>
            <th>Week {{ $previousWeek->week_number }}</th>
            @if(isset($_GET['yam']))
            <th>Month ({{ $presentation_data['titles']['this month'] ?? '' }})</th>
            <th>Year ({{ $presentation_data['titles']['this year'] ?? '' }})</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @php
        $categories = [
        'ISMS',
        'CLOUD / SAAS SERVERS',
        'ISP',
        // 'CYBER ATTACKS',
        'PROJECTS',
        'Training',
        ];
        @endphp

        @foreach($categories as $category)
        <tr style="background-color: #f4f4f4">
            <th>IT</th>
            @if(isset($_GET['yam']))
            <th colspan="5">{{ $category }}</th>
            @else
            <th colspan="3">{{ $category }}</th>
            @endif
        </tr>
        @foreach($form_fields_collection->where('unit', $category) as $field)
        @php
        $current_week = isset($presentation_data['current_week'][$field->id]) ? (isset($presentation_data['current_week'][$field->id]['total']) ? $presentation_data['current_week'][$field->id]['total'] : (isset($presentation_data['current_week'][$field->id]['average']) ? $presentation_data['current_week'][$field->id]['average'] : null)) : null;
        $prev_week = isset($presentation_data['previous_week'][$field->id]) ? (isset($presentation_data['previous_week'][$field->id]['total']) ? $presentation_data['previous_week'][$field->id]['total'] : (isset($presentation_data['previous_week'][$field->id]['average']) ? $presentation_data['previous_week'][$field->id]['average'] : null)) : null;

        // Handle JSON data for ISP category
        if ($category === 'ISP') {
        $current_week_data = null;
        $prev_week_data = null;

        if ($current_week && is_string($current_week)) {
        try {
        $current_week_data = json_decode($current_week, true);
        } catch (\Exception $e) {
        $current_week_data = null;
        }
        }

        if ($prev_week && is_string($prev_week)) {
        try {
        $prev_week_data = json_decode($prev_week, true);
        } catch (\Exception $e) {
        $prev_week_data = null;
        }
        }
        }
        @endphp


        @if($category === 'ISP' && isset($current_week_data))
        @foreach(['Up-time', 'Bandwidth'] as $metric)
        @if($metric === 'Bandwidth' && (Route::currentRouteName() === 'frontend.business_goals.multi.business.areas' || $logged_in_user->email == 'john.nomnor@arikair.com'))
        @continue
        @endif
        @if(isset($current_week_data[$metric]))
        <tr>
            <td>{{ $field->placeholder ?? '' }} | {{ $field->label }}</td>
            <td>{{ $metric }} ({{ $metric === 'Up-time' ? '%' : 'Mbps' }})</td>
            <td class="position-relative" style="text-align: left;">
                @if(isset($current_week_data[$metric]['target'], $current_week_data[$metric]['actual']))
                @php
                $diff = $current_week_data[$metric]['actual'] - $current_week_data[$metric]['target'];
                $badgeClass = $diff < 0 ? 'badge-danger' : 'badge-success' ;
                    $sign=$diff> 0 ? '+' : '';
                    $unit = $metric === 'Up-time' ? '%' : 'Mbps';
                    @endphp
                    <span class="badge {{ $badgeClass }} position-absolute" style="top: 5px; right: 5px;">
                        {{ $sign }}{{ round($diff, 2) }}{{ $unit }}
                    </span>
                    @endif
                    Target: {{ $current_week_data[$metric]['target'] ?? 'N/A' }}<br>
                    Actual: <span class="text-lg {{ isset($current_week_data[$metric]['target'], $current_week_data[$metric]['actual']) && $current_week_data[$metric]['actual'] < $current_week_data[$metric]['target'] ? 'text-danger' : 'text-success' }}">
                        {{ $current_week_data[$metric]['actual'] ?? 'N/A' }} {{ $current_week_data[$metric]['actual'] ? $metric === 'Up-time' ? '%' : 'Mbps' : '' }}
                    </span>
            </td>
            <td class="position-relative" style="text-align: left;">
                @if(isset($prev_week_data[$metric]))
                @if(isset($prev_week_data[$metric]['target'], $prev_week_data[$metric]['actual']))
                @php
                $diff = $prev_week_data[$metric]['actual'] - $prev_week_data[$metric]['target'];
                $badgeClass = $diff < 0 ? 'badge-danger' : 'badge-success' ;
                    $sign=$diff> 0 ? '+' : '';
                    $unit = $metric === 'Up-time' ? '%' : 'Mbps';
                    @endphp
                    <span class="badge {{ $badgeClass }} position-absolute" style="top: 5px; right: 5px;">
                        {{ $sign }}{{ $diff }}{{ $unit }}
                    </span>
                    @endif
                    Target: {{ $prev_week_data[$metric]['target'] ?? 'N/A' }}<br>
                    Actual: <span class="text-lg {{ isset($prev_week_data[$metric]['target'], $prev_week_data[$metric]['actual']) && $prev_week_data[$metric]['actual'] < $prev_week_data[$metric]['target'] ? 'text-danger' : 'text-success' }}">
                        {{ $prev_week_data[$metric]['actual'] ?? 'N/A' }} {{ $prev_week_data[$metric]['actual'] ? $metric === 'Up-time' ? '%' : 'Mbps' : '' }}
                    </span>
                    @else
                    N/A
                    @endif
            </td>
            @if(isset($_GET['yam']))
            <td>
                @if(isset($presentation_data['current_month'][$field->id][$metric]))
                @php
                $monthData = $presentation_data['current_month'][$field->id][$metric];
                @endphp
                Target: {{ $monthData['target'] ?? 'N/A' }}<br>
                Actual: <span class="text-lg {{ isset($monthData['target'], $monthData['actual']) && $monthData['actual'] < $monthData['target'] ? 'text-danger' : 'text-success' }}">
                    {{ $monthData['actual'] ?? 'N/A' }} {{ $monthData['actual'] ? $metric === 'Up-time' ? '%' : 'Mbps' : '' }}
                </span>
                @else
                N/A
                @endif
            </td>
            <td>
                @if(isset($presentation_data['current_year'][$field->id][$metric]))
                @php
                $yearData = $presentation_data['current_year'][$field->id][$metric];
                @endphp
                Target: {{ $yearData['target'] ?? 'N/A' }}<br>
                Actual: <span class="text-lg {{ isset($yearData['target'], $yearData['actual']) && $yearData['actual'] < $yearData['target'] ? 'text-danger' : 'text-success' }}">
                    {{ $yearData['actual'] ?? 'N/A' }} {{ $yearData['actual'] ? $metric === 'Up-time' ? '%' : 'Mbps' : '' }}
                </span>
                @else
                N/A
                @endif
            </td>
            @endif
        </tr>
        @endif
        @endforeach
        @elseif($category !== 'ISP' && (isset($current_week) || isset($prev_week)))
        <tr>
            <td>{{ $field->placeholder ?? '' }}</td>
            <td>{{ $field->label ?? ''}}</td>
            <td>{{ $current_week ?? 'N/A'}}</td>
            <td>{{ $prev_week ?? 'N/A'}}</td>
            @if(isset($_GET['yam']))
            <td>
                @php
                $monthData = $presentation_data['current_month'][$field->id] ?? null;
                @endphp
                @if($monthData)
                @if(isset($monthData['average']))
                {{ $monthData['average'] }}
                @elseif(isset($monthData['total']))
                {{ $monthData['total'] }}
                @else
                N/A
                @endif
                @else
                N/A
                @endif
            </td>
            <td>
                @php
                $yearData = $presentation_data['current_year'][$field->id] ?? null;
                @endphp
                @if($yearData)
                @if(isset($yearData['average']))
                {{ $yearData['average'] }}
                @elseif(isset($yearData['total']))
                {{ $yearData['total'] }}
                @else
                N/A
                @endif
                @else
                N/A
                @endif
            </td>
            @endif
        </tr>
        @endif
        @endforeach
        @endforeach

        @php
        $current_week_stats = \App\Services\WeekRangeService::serviceNowWeekStats(1, $week_in_focus->from_date, $week_in_focus->to_date);
        $prev_week_stats = \App\Services\WeekRangeService::serviceNowWeekStats(1, $previousWeek->from_date, $previousWeek->to_date);
        @endphp
        <tr style="background-color: #f4f4f4">
            <th>IT</th>
            @if(isset($_GET['yam']))
            <th colspan="5">IT HELPDESK</th>
            @else
            <th colspan="3">IT HELPDESK</th>
            @endif
        </tr>
        @php
        if(isset($_GET['yam'])) {
        // Get month range
        $month_start = \Carbon\Carbon::parse($week_in_focus->from_date)->startOfMonth()->format('Y-m-d');
        $month_end = \Carbon\Carbon::parse($week_in_focus->from_date)->endOfMonth()->format('Y-m-d');
        $monthStats = \App\Services\WeekRangeService::serviceNowWeekStats(1, $month_start, $month_end);

        // Get year range
        $year_start = \Carbon\Carbon::parse($week_in_focus->from_date)->startOfYear()->format('Y-m-d');
        $year_end = \Carbon\Carbon::parse($week_in_focus->from_date)->endOfYear()->format('Y-m-d');
        $yearStats = \App\Services\WeekRangeService::serviceNowWeekStats(1, $year_start, $year_end);
        } else {
        $monthStats = null;
        $yearStats = null;
        }
        @endphp
        <tr>
            <td></td>
            <td>TOTAL ITEMS</td>
            <td>{{ $current_week_stats['total'] }}</td>
            <td>{{ $prev_week_stats['total'] }}</td>
            @if(isset($_GET['yam']))
            <td>{{ $monthStats['total'] ?? 'N/A' }}</td>
            <td>{{ $yearStats['total'] ?? 'N/A' }}</td>
            @endif
        </tr>
        <tr>
            <td></td>
            <td align="right">PHONE</td>
            <td>{{ $current_week_stats['phone'] }}</td>
            <td>{{ $prev_week_stats['phone'] }}</td>
            @if(isset($_GET['yam']))
            <td>{{ $monthStats['phone'] ?? 'N/A' }}</td>
            <td>{{ $yearStats['phone'] ?? 'N/A' }}</td>
            @endif
        </tr>
        <tr>
            <td></td>
            <td align="right">WALK IN</td>
            <td>{{ $current_week_stats['walk in'] }}</td>
            <td>{{ $prev_week_stats['walk in'] }}</td>
            @if(isset($_GET['yam']))
            <td>{{ $monthStats['walk in'] ?? 'N/A' }}</td>
            <td>{{ $yearStats['walk in'] ?? 'N/A' }}</td>
            @endif
        </tr>
        <tr>
            <td></td>
            <td align="right">EMAIL-IThelpdesk@arikair.com</td>
            <td>{{ $current_week_stats['email'] }}</td>
            <td>{{ $prev_week_stats['email'] }}</td>
            @if(isset($_GET['yam']))
            <td>{{ $monthStats['email'] ?? 'N/A' }}</td>
            <td>{{ $yearStats['email'] ?? 'N/A' }}</td>
            @endif
        </tr>
        <tr>
            <td></td>
            <td align="right">CLOSED ITEMS</td>
            <td>{{ $current_week_stats['closed'] }}</td>
            <td>{{ $prev_week_stats['closed'] }}</td>
            @if(isset($_GET['yam']))
            <td>{{ $monthStats['closed'] ?? 'N/A' }}</td>
            <td>{{ $yearStats['closed'] ?? 'N/A' }}</td>
            @endif
        </tr>
        <tr>
            <td></td>
            <td align="right">OPEN ITEMS</td>
            <td>{{ $current_week_stats['open'] }}</td>
            <td>{{ $prev_week_stats['open'] }}</td>
            @if(isset($_GET['yam']))
            <td>{{ $monthStats['open'] ?? 'N/A' }}</td>
            <td>{{ $yearStats['open'] ?? 'N/A' }}</td>
            @endif
        </tr>
    </tbody>
</table>


{{--<div class="row mt-3">--}}
{{-- <div class="col-12">--}}
{{-- @include('frontend.business_goals.dailies._9')--}}
{{-- </div>--}}
{{--</div>--}}