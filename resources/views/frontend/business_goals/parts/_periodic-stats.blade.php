@php
    $quarter_number = getQuarterNum($week_in_focus->in_month_num);
    $year = $week_in_focus->in_year;

    $data_values = \App\Models\BscPeriodicDataPoint::where('for_year', $year)->get();

    $general_stats = [
        [62, 'No. of Flights operated', '', ''],
        [50, 'Capacity', '', ''],
        [66, 'Total PAX', '', ''],
        [58, 'Average Fare + Taxes', '', '₦'],
        ['', 'Total Sales', '', '₦'],
        [55, 'Revenue', '', '₦'],
        [2, 'OTP', '1', '%'],
        [28, 'Load Factor', '1', '%'],
        [4, 'Completion Factor', '1', '%'],
    ];

    $months = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
        7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
    ];

    $data['month'] = $data_values->where('for_month', $week_in_focus->in_month)->where('time_title', 'month');
    for ($i = 1; $i <= 12; $i++) {
        $data[$i] = $data_values->where('for_month_number', $i)->where('time_title', 'month');
    }

    $data['quarter'] = $data_values->where('for_quarter', $quarter_number)->where('time_title', 'quarter');
    $data['year'] = $data_values->where('for_year', $year)->where('time_title', 'year');

@endphp

<div class="row">
    <div class="col-12">
        <div class="card arik-card">
            <div class="card-header">
                <h3 class="card-title text-lg text-bold">BSC Stats</h3>
            </div>

            <div class="card-body">
                <!-- Bootstrap Tabs -->
                <ul class="nav nav-tabs nav-justified" id="bscTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="year-tab" data-toggle="tab" href="#year" role="tab"><h3 style="font-weight: bolder;">Yearly</h3></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="quarter-tab" data-toggle="tab" href="#quarter" role="tab"><h3 style="font-weight: bolder;">Quarterly</h3></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="month-tab" data-toggle="tab" href="#month" role="tab"><h3 style="font-weight: bolder;">Monthly</h3></a>
                    </li>
                </ul>

                        <div class="tab-content mt-3" id="bscTabContent">
                    <!-- Yearly Stats -->
                    <div class="tab-pane fade show active" id="year" role="tabpanel">
                        <h4 class="text-bold">The Year {{ $year }}</h4>
                        <div class="row">
                            @foreach($general_stats as $stat)
                                @php $data_record = $data['year']->where('score_card_form_field_id', $stat[0])->first(); @endphp
                                @include('frontend.business_goals.partials.stat-card', ['data_record' => $data_record, 'stat' => $stat])
                            @endforeach
                        </div>
                    </div>

                    <!-- Quarterly Stats -->
                    <div class="tab-pane fade" id="quarter" role="tabpanel">
                        <h4 class="text-bold">Quarter {{ $quarter_number }}, {{ $year }}</h4>
                        <div class="row">
                            @foreach($general_stats as $stat)
                                @php $data_record = $data['quarter']->where('score_card_form_field_id', $stat[0])->first(); @endphp
                                @include('frontend.business_goals.partials.stat-card', ['data_record' => $data_record, 'stat' => $stat])
                            @endforeach
                        </div>
                    </div>

                    <!-- Monthly Stats with Sub-Tabs -->
                    <div class="tab-pane fade" id="month" role="tabpanel">
                        <h4 class="text-bold">Months in {{ $year }}</h4>

                        <ul class="nav nav-pills nav-justified mb-3" id="monthTabs" role="tablist">
                            @foreach($months as $num => $month)
                                <li class="nav-item">
                                    <a class="nav-link text-lg {{ $num == $week_in_focus->in_month_num ? 'active' : '' }}"
                                       id="month-{{ $num }}-tab" data-toggle="pill"
                                       href="#month-{{ $num }}" role="tab" style="font-size: larger;"><strong>{{ $month }}</strong></a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content" id="monthTabContent">
                            @foreach($months as $num => $month)
                                <div class="tab-pane fade {{ $num == $week_in_focus->in_month_num ? 'show active' : '' }}"
                                     id="month-{{ $num }}" role="tabpanel">
                                    <div class="row">
                                        @foreach($general_stats as $stat)
                                            @php $data_record = $data[$num]->where('score_card_form_field_id', $stat[0])->first(); @endphp
                                            @include('frontend.business_goals.partials.stat-card', ['data_record' => $data_record, 'stat' => $stat])
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div> <!-- End Monthly Stats -->
                </div> <!-- End Tab Content -->
            </div> <!-- End Card Body -->
        </div> <!-- End Card -->
    </div> <!-- End Column -->
</div> <!-- End Row -->

