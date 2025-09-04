@extends('frontend.layouts.app')

@section('title', 'Add Single Day Report')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="card my-2">
                    @if(count($accessible_business_areas) > 1)
                        <div class="card-header">
                            <h4 class="card-title">Select Business Area</h4>
                        </div>
                    @endif
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="row">
                                <div class="col-5">
                                    <div class="form-group mb-0">
                                        <select name="business_area_id" class="form-control">
                                            @foreach($accessible_business_areas as $biz_area)
                                                <option value="{{ $biz_area->id }}"
                                                        @if(isset($_GET['business_area_id']) && $_GET['business_area_id'] == $biz_area->id) selected @endif>{{ $biz_area->name }}</option>
                                            @endforeach
                                        </select>
                                        <label>Business Area</label>
                                    </div>
                                </div>

                                {{--                                @isset($weeks)--}}
                                {{--                                    <div class="col-4">--}}
                                {{--                                        <div class="form-group mb-0">--}}
                                {{--                                            <select class="form-control" name="week_range_id" id="week_range_id">--}}
                                {{--                                                @php $week_id = isset($week_range_id) ? $week_range_id : null; @endphp--}}
                                {{--                                                @include('frontend.business_goals.partials._week_range_options', ['selected_week_id' => $week_id])--}}
                                {{--                                            </select>--}}
                                {{--                                            <label>Week Number</label>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                @endisset--}}

                                <div class="col-5">
                                    <div class="form-group mb-3">
                                        <input type="date" name="for_date"
                                               min="{{ substr($weeks->last()->from_date, 0, 10) }}"
                                               max="{{ now()->toDateString() }}" id="for_date"
                                               @if(isset($_GET['for_date'])) value="{{ $_GET['for_date'] }}"
                                               @endif class="form-control" placeholder=""
                                               aria-describedby="for_date_helpId" required>
                                        <span class="text-muted"></span>
                                        <label for="">For Date</label>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <button type="submit" class="btn bg-navy btn-block">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @if(isset($_GET['for_date']))
                    <div class="card">
                        {{--                    {{ dd($business_area) }}--}}

                        <div class="card-header">{{ $business_area->name ?? 'Yyyy?'}} - Business Score Card Data Entry
                            for date: {{ $_GET['for_date'] }}

                            @if((is_null($data_points) || empty($data_points) || !$data_points->count()) && isset($business_area) && $most_recent_filled_day)
                                <form method="GET" class="mt-3">
                                    <input type="hidden" name="business_area_id" value="{{ $business_area->id }}">
                                    <input type="hidden" name="prefill" value="1">
                                    <input type="hidden" name="for_date" value="{{ $_GET['for_date'] }}">
                                    <button type="submit" class="btn bg-navy btn-block">Rollover Recent Data</button>
                                </form>
                            @endif
                        </div>
                        <div class="card-body">
                            <form action="{{ route('frontend.business_goals.store_report') }}" method="POST">
                                @csrf
                                <input type="hidden" name="business_area_id" value="{{ $business_area->id }}">
                                {{--                            <div class="form-group mb-3">--}}
                                {{--                                <label for="">For Date</label>--}}
                                <input type="hidden" name="for_date" value="{{ $_GET['for_date'] }}" max="" id=""
                                       class="form-control" placeholder="" aria-describedby="helpId">
                                {{--                            </div>--}}

                                @if($business_area->id == 9)
                                @php
                                $isp_sla_targets = [
                                    'MTN' => [
                                        'Bandwidth' => 155,
                                        'Up-time' => 98
                                        ],
                                    'GLO' => [
                                        'Bandwidth' => 155,
                                        'Up-time' => 97
                                        ],
                                    'SWIFT' => [
                                        'Bandwidth' => 100,
                                        'Up-time' => 98
                                        ],
                                    'DESTON' => [
                                        'Bandwidth' => 100,
                                        'Up-time' => 95
                                        ],
                                    'DIAMOND GLOBE-ABV' => [
                                        'Bandwidth' => 100,
                                        'Up-time' => 92
                                        ],
                                    'STARLINK' => [
                                        'Bandwidth' => 100,
                                        'Up-time' => 90
                                        ],
                                ];

                                $metric_types = ["Up-time" => "%", "Bandwidth" => "Mbps"];
                                @endphp
                                @endif


                                @if(in_array($business_area->id, [2, 3, 5, 6, 7, 10]))
                                    @if($business_area->id == 2)

                                        @php
                                            // For Ground Ops ordering
                                                $custom_order = [73, 75, 77, 78, 79, 80, 81, 82, 282, 283, 284, 285, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 297, 298, 299, 300, 301, 302, 303, 304, 305, 83, 84, 272, 273, 391, 85, 86, 275, 390, 389, 89, 90, 186, 187, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 188, 110];
                                        @endphp
                                    @elseif($business_area->id == 3)
                                        @php
                                            // For Flight Ops - Operational Delivery / Customer Service ordering
                                                $custom_order = [1, 2, 27, 28, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15
    ];
                                              $fl_ops_target = [
                                                  '1' => 80,
                                                  '3' => 90,
                                                  '27' => 85,
                                                  '14' => 96
    ];
                                        @endphp

                                    @elseif($business_area->id == 5)
                                        @php
                                            $custom_order = [49,345,50,51,346,52,53,54,55,56,347,57,348,58,207,208,209,210,211];
                                        @endphp

                                    @elseif($business_area->id == 7)
                                        @php
                                            $custom_order = [111, 112, 162, 113, 114, 115];
                                        @endphp

                                    @elseif($business_area->id == 6)
                                        @php

                                            $custom_order = [59, 60, 61, 62, 63, 64, 65, 66, 67, 72, 68, 387, 69, 70];
                                        @endphp

                                    @elseif($business_area->id == 10)
                                        {{--                                Internal Control --}}
                                        @php
                                            $custom_order = [320, 321, 322, 323, 324, 325, 326, 327, 328, 329, 330, 331, 332, 333, 334, 335, 336, 337, 340, 341, 342, 343];
                                        @endphp
                                    @endif

                                    @foreach($custom_order as $order)
                                        @php $form_field = $form_fields->where('id', $order)->first(); $field_value = null; @endphp
                                        @if($form_field)
                                            @isset($data_points)
                                                @php
                                                    $field_value = $data_points->where('business_area_id', $business_area->id)
                                                    ->where('score_card_form_field_id', $form_field->id)
                                                    ->where('for_date', \Carbon\Carbon::parse($for_date))
                                                    ->first();
                                                @endphp
                                            @endisset
                                            <div class="form-group">
                                                <label>{{ $business_area->id == 9 ? $form_field->placeholder.' | ' : '' }}{{ $form_field->label }} @isset($form_field->unit) ({{ $form_field->unit }}) @endisset</label>
                                                @if($business_area->id == 9 && $form_field->unit == 'ISP')
                                                    @php
                                                    if(isset($field_value) && json_decode($field_value->data_value)){
                                                        $field_values = json_decode($field_value->data_value, true);
                                                    }
                                                    @endphp

                                                    <div class="row border-bottom pb-3">
                                                        @foreach(["Up-time", "Bandwidth"] as $metric)
                                                            @foreach(["target", "actual"] as $value_type)
                                                                <div class="col">
                                                                    <input type="number"
                                                                     step="0.01"
                                                                     class="form-control"
                                                                     name="isp_metrics[{{ $form_field->id }}][{{ $metric }}][{{ $value_type }}]"
                                                                     value="{{
                                                                     isset($field_values) && array_key_exists($metric, $field_values) && array_key_exists($value_type, $field_values[$metric])
                                                                     ? $field_values[$metric][$value_type]
                                                                     : ($value_type == 'target' ? (isset($isp_sla_targets[$form_field->label][$metric]) ? $isp_sla_targets[$form_field->label][$metric] : '') : '') }}"

                                                                     @if($value_type == 'target') readonly @endif
                                                                     >
                                                                    <strong class="text-maroon">{{ $metric }} {{ $value_type }} ({{ $metric_types[$metric] }})</strong>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                                @else
                                                <input
                                                    name="form_field[{{ $form_field->id }}]"
                                                    type="{{ $form_field->form_type }}"
                                                    @if($form_field->form_type == 'number')
                                                        step="0.01" min="0"
                                                    @endif
                                                    id="f_id_{{ $form_field->id }}"
                                                    placeholder="{{ $form_field->placeholder }}"
                                                    class="form-control
                                                @if(in_array($form_field->id, [29, 32, 33, 34])) hr_staff_categories @endif
                                                    @if(in_array($form_field->id, [272, 273, 274, 391])) GOPs_glitches @endif
                                                        "
                                                    @isset($field_value) value="{{ $field_value->data_value }}" @endisset>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach

                                    @if($business_area->id == 7)
                                        @include('frontend.business_goals.partials._commercial-customer-relations-single-day-data-entry')
                                    @endif

                                    @if($business_area->id == 10)
                                        {{--                                    Internal control--}}
                                        @php
                                            $departments = \Illuminate\Support\Facades\DB::table('departments')->distinct()->get()->pluck('name');
                                        @endphp
                                        <span
                                            class="text-muted mt-3 mb-1"><strong>OBSERVATIONS (COMPLETED)</strong></span>
                                        @include('frontend.business_goals.partials._internal-control-observations-completed-entry')

                                        <span
                                            class="text-muted mt-3 mb-1"><strong>OBSERVATIONS (ON-GOING)</strong></span>
                                        @include('frontend.business_goals.partials._internal-control-observations-ongoing-entry')
                                    @endif

                                @else
                                    @foreach($form_fields as $form_field)
                                        @php $field_value = null; @endphp
                                        @isset($data_points)
                                            @php
                                                $field_value = $data_points->where('business_area_id', $business_area->id)
                                                ->where('score_card_form_field_id', $form_field->id)
                                                ->where('for_date', \Carbon\Carbon::parse($for_date))
                                                ->first()
                                            @endphp
                                        @endisset

                                        <div class="form-group">
                                            <label>{{ $business_area->id == 9 ? $form_field->placeholder.' | ' : '' }}{{ $form_field->label }} @isset($form_field->unit) ({{ $form_field->unit }}) @endisset</label>
                                            @if($business_area->id == 9 && $form_field->unit == 'ISP')
                                                @php
                                                $isp_sla_targets = [
                                                    'MTN' => [
                                                        'Bandwidth' => 155,
                                                        'Up-time' => 98
                                                        ],
                                                    'GLO' => [
                                                        'Bandwidth' => 155,
                                                        'Up-time' => 97
                                                        ],
                                                    'SWIFT' => [
                                                        'Bandwidth' => 100,
                                                        'Up-time' => 98
                                                        ],
                                                    'DESTON' => [
                                                        'Bandwidth' => 100,
                                                        'Up-time' => 95
                                                        ],
                                                    'DIAMOND GLOBE-ABV' => [
                                                        'Bandwidth' => 100,
                                                        'Up-time' => 92
                                                        ],
                                                    'STARLINK' => [
                                                        'Bandwidth' => 100,
                                                        'Up-time' => 90
                                                        ],
                                                ];

                                                if(isset($field_value) && json_decode($field_value->data_value)){
                                                    $field_values = json_decode($field_value->data_value, true);
                                                }

                                                $metric_types = ["Up-time" => "%", "Bandwidth" => "Mbps"];
                                                @endphp

                                                <div class="row border-bottom pb-3">
                                                    @foreach(["Up-time", "Bandwidth"] as $metric)
                                                        @foreach(["target", "actual"] as $value_type)
                                                            <div class="col">
                                                                <input type="number"
                                                                 step="0.01"
                                                                 class="form-control"
                                                                 name="isp_metrics[{{ $form_field->id }}][{{ $metric }}][{{ $value_type }}]"
                                                                 value="{{
                                                                 isset($field_values) && array_key_exists($metric, $field_values) && array_key_exists($value_type, $field_values[$metric])
                                                                 ? $field_values[$metric][$value_type]
                                                                 : ($value_type == 'target' ? (isset($isp_sla_targets[$form_field->label][$metric]) ? $isp_sla_targets[$form_field->label][$metric] : '') : '') }}"

                                                                 @if($value_type == 'target') readonly @endif
                                                                 >
                                                                <strong class="text-maroon">{{ $metric }} {{ $value_type }} ({{ $metric_types[$metric] }})</strong>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            @else
                                            <input
                                                name="form_field[{{ $form_field->id }}]"
                                                type="{{ $form_field->form_type }}"
                                                @if($form_field->form_type == 'number')
                                                    step="0.01" min="0"
                                                @endif
                                                id="f_id_{{ $form_field->id }}"
                                                placeholder="{{ $form_field->placeholder }}"
                                                class="form-control
                                                @if(in_array($form_field->id, [29, 32, 33, 34])) hr_staff_categories @endif
                                                @if(in_array($form_field->id, [272, 273, 274, 391])) GOPs_glitches @endif
"
                                                @isset($field_value) value="{{ $field_value->data_value }}" @endisset>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @if($business_area->id == 1)
            var headcount = document.getElementById('f_id_16');
            // console.log(headcount);
            @if(isset($data_points))
                @php
                    $headcount = $data_points->where('name', 'Headcount')->first();
                @endphp
                @endif
                headcount.value = @if(isset($headcount)) {{ $headcount->data_value }} @else 0 @endif;
            const inputFields = document.querySelectorAll('.hr_staff_categories');

            var sum_headcount = 0;
            inputFields.forEach(function (inputField) {
                inputField.addEventListener('change', handleChange);
            });

            function handleChange(event) {
                // const id = event.target.id;
                // const value = event.target.value;
                sum_headcount = 0;
                inputFields.forEach(function (hr_field) {
                    sum_headcount += Number(hr_field.value);
                });
                headcount.value = sum_headcount;
                console.log(sum_headcount);
            }
            @endif

            @if($business_area->id == 2)
            var onlineGlitches = document.getElementById('f_id_85');
            @if(isset($data_points))
                @php
                    $onlineGlitches = $data_points->where('name', 'Online Glitch')->first();
                @endphp
                @endif
                onlineGlitches.value = @if(isset($onlineGlitches)) {{ $onlineGlitches->data_value }} @else 0 @endif;
            const glitchesInputFields = document.querySelectorAll('.GOPs_glitches');

            var sum_glitches = 0;
            glitchesInputFields.forEach(function (inputField) {
                inputField.addEventListener('change', handleChangeGOPS);
            });

            function handleChangeGOPS(event) {
                sum_glitches = 0;
                glitchesInputFields.forEach(function (gops_field) {
                    sum_glitches += Number(gops_field.value);
                });
                onlineGlitches.value = sum_glitches;
            }

            @endif

        });
    </script>

    @if($business_area->id == 10)
        @include('frontend.business_goals.partials._internal-control-observations-data-entry-script')
    @endif
@endpush
