@extends('frontend.layouts.app')

@section('title', (isset($business_area) ? $business_area->name : ''). ' BSC Input')

@if(in_array($business_area->id, [3, 10]))
@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush
    @endif

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                @include('frontend.business_goals.partials._business-area-selector')
                <div class="card">
                    <div class="card-header">{{ $business_area->name ?? 'Yyyy?'}} - Business Score Card Data Entry for Wk {{ $selected_week->week_number }}: {{ $selected_week->from_day }} - {{ $selected_week->to_day }}
                        @if(is_null($data_points) || empty($data_points) || !$data_points->count() && $recent_filled_week))
                    <form method="GET" class="mt-3">
                        <input type="hidden" name="business_area_id" value="{{ $business_area->id }}">
                        @isset($week_range_id)
                        <input type="hidden" name="week_range_id" value="{{ $week_range_id }}">
                        @endisset
                        <input type="hidden" name="prefill" value="1">
                        <button type="submit" class="btn bg-maroon btn-block">Rollover Recent Data</button>
                    </form>
                            @endif
                    </div>
                    <div class="card-body">
                        <form action="{{ route('frontend.business_goals.store_report') }}" method="POST">
                            @csrf
                            <input type="hidden" name="business_area_id" value="{{ $business_area->id }}">

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
                                @endphp
                            @endif

{{--                            <div class="form-group mb-3">--}}
{{--                                <label for="">For Date</label>--}}
{{--                                <input type="date" name="for_date" max="" id="" class="form-control" placeholder=""--}}
{{--                                       aria-describedby="helpId">--}}
{{--                                <span class="text-muted">Fill this if the report is just for a day</span>--}}
{{--                            </div>--}}

                            <div class="form-group mb-3" style="display: none;">
                                <label for="">Week</label>
                                <select type="date" name="week_range_id" id="" class="form-control" readonly>
                                    @include('frontend.business_goals.partials._week_range_options', ['selected_week_id' => isset($week_range_id) ? $week_range_id : null])
                                </select>
{{--                                <span class="text-muted">Or select a week</span>--}}
                            </div>

                            @if(in_array($business_area->id, [1, 2, 3, 5, 6, 7, 10]))

                            @if($business_area->id == 1)
                                    @php
                                        // $custom_order = [16, 29, 32, 34, 33, 31, 36, 35, 24];
$custom_order = [16, 29, 32, 386, 34, 33, 31, 36, 35, 24, 25, 26, 370, 371, 372, 373, 374, 375, 376, 377, 378, 379, 380, 381, 382, 383, 384];
                                    @endphp
                                @elseif($business_area->id == 2)

@php
// For Ground Ops ordering
    $custom_order = [73, 75, 77, 78, 79, 80, 81, 82, 282, 283, 284, 285, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 297, 298, 299, 300, 301, 302, 303, 304, 305, 83, 84, 272, 273, 391, 85, 86, 275, 390, 389, 89, 90, 186, 187, 404, 405, 91, 406, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 188, 110];
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
                                @isset($data_points) @php $field_value = $data_points->where('business_area_id', $business_area->id)->where('score_card_form_field_id', $form_field->id)->where('week_range_id', $week_range_id)->first() @endphp @endisset
                                <div class="form-group">
                                    <label>@if($business_area->id == 1 && $form_field->id > 369 && $form_field->id < 386 )
                                            No. of Staff in
                                        @endif {{ $form_field->label }} @if(isset($form_field->unit) && $form_field->unit != '') ({{ $form_field->unit }}) @endisset</label>
                                    <input name="form_field[{{ $form_field->id }}]"
                                           id="f_id_{{ $form_field->id }}"
                                           type="{{ $form_field->form_type }}"
                                           @if($form_field->form_type == 'number') step="0.01" min="0" @endif
                                           placeholder="{{ $form_field->placeholder }}"
                                           class="form-control
@if(in_array($form_field->id, [29, 32, 33, 34])) hr_staff_categories @endif
@if(in_array($form_field->id, [272, 273, 274, 391])) GOPs_glitches @endif
                                               "
                                           value="{{ (isset($fl_ops_target) && isset($fl_ops_target[$form_field->id] )) ? $fl_ops_target[$form_field->id] : (isset($field_value) ? $field_value->data_value : '') }}"
                                           @isset($field_value) required @endisset
                                    >
                                </div>
								@endif
                            @endforeach

                                @if($business_area->id == 7)
                                    @include('frontend.business_goals.partials._commercial-customer-relations-data-entry')
                                @endif

                                @if($business_area->id == 3)
                                    <span class="text-muted mt-3 mb-1"><strong>DELAYS</strong></span>
@include('frontend.business_goals.partials._flight-ops-operational-delivery-delays-entry')
                                    @endif

                                @if($business_area->id == 10)
{{--                                    Internal control--}}
                                    @php
                                        $departments = \Illuminate\Support\Facades\DB::table('departments')->distinct()->get()->pluck('name');
                                    @endphp
                                    <span class="text-muted mt-3 mb-1"><strong>OBSERVATIONS (COMPLETED)</strong></span>
                                    @include('frontend.business_goals.partials._internal-control-observations-completed-entry')

                                    <span class="text-muted mt-3 mb-1"><strong>OBSERVATIONS (ON-GOING)</strong></span>
                                    @include('frontend.business_goals.partials._internal-control-observations-ongoing-entry')
                                    @endif
                            @else
                                @foreach($form_fields as $form_field)
                                    @php $field_value = null; @endphp
                                    @isset($data_points) @php $field_value = $data_points->where('business_area_id', $business_area->id)->where('score_card_form_field_id', $form_field->id)->where('week_range_id', $week_range_id)->first() @endphp @endisset
                                    <div class="form-group">
                                        <label>
                                            {{ $business_area->id == 9 ? $form_field->placeholder.' | ' : '' }}{{ $form_field->label }} @isset($form_field->unit) ({{ $form_field->unit }}) @endisset
                                        </label>
                                            @if($form_field->unit == 'ISP')
                                                @php
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
                                                                 : ($value_type == 'target' ? $isp_sla_targets[$form_field->label][$metric] : '') }}"

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

                                            @if($business_area->id != 9)
                                            placeholder="{{ $form_field->placeholder }}"
                                            @endif

                                            id="f_id_{{ $form_field->id }}"
                                            class="form-control" @isset($field_value) value="{{ $field_value->data_value }}" required @endisset>
                                        @endif
                                    </div>
                                @endforeach
                            @endif

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
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
            }

            let permStaff = document.getElementById('f_id_29');
            let contractStaff = document.getElementById('f_id_32');
            let contractRatio = document.getElementById('f_id_386');
            handleContractRatio();

            permStaff.addEventListener('change', handleContractRatio);
            contractStaff.addEventListener('change', handleContractRatio);

            function handleContractRatio(event){
                contractRatio.value = formatNumber((contractStaff.value / permStaff.value) * 100);
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


            let failedTrx = document.getElementById('f_id_275');
            let ticketSales = document.getElementById('f_id_389');
            let percentFailure = document.getElementById('f_id_390');

            ticketSales.
            function handlePercentFailureGOPs(event){

            }
        @endif

            function formatNumber(num) {
                if (Number.isInteger(num)) {
                    return num; // Return the integer as is
                }

                const decimalPlaces = num.toString().split(".")[1]?.length || 0;

                if (decimalPlaces <= 2) {
                    return num; // Return the number as is if it has 1 or 2 decimal places
                }

                return parseFloat(num.toFixed(2)); // Format to 2 decimal places otherwise
            }
        });
    </script>

@if($business_area->id == 3)
    <script>
        let delayCodeCounter = 1; // Initial counter for ID

        // Function to add a new delay code row
        document.getElementById('add-delay-code-btn').addEventListener('click', function() {
            delayCodeCounter++; // Increment the counter to make unique IDs

            let newRow = document.createElement('div');
            newRow.classList.add('row');
            newRow.id = `delay-code-${delayCodeCounter}`;
            newRow.innerHTML = `
        <div class="col-9">
            <div class="form-group">
                <select class="form-control select2" name="delay_codes[]" required>
                    <option selected disabled>Select One</option>
                    @foreach($delay_codes as $delay_code)
            <option value="{{ $delay_code->delay_codes }}">{{ $delay_code->delay_codes }}, {{ $delay_code->delay_reason }} - {{ $delay_code->delay_definition }}</option>
                    @endforeach
            </select>
            <label>Delay code</label>
        </div>
    </div>
    <div class="col-2">
        <input type="number" step="1" min="0" class="form-control" name="delay_amounts[]" required>
        <label>Number of times</label>
    </div>
    <div class="col-1">
        <button type="button" class="btn btn-danger remove-delay-code-btn"><i class="fa fa-times"></i></button>
    </div>
`;

            // Insert the new row before the add button's parent
            document.getElementById('add-delay-code-btn').parentNode.before(newRow);

            // Re-initialize select2 on the newly added select
            $(newRow).find('.select2').select2({
                theme: 'bootstrap4'
            });
        });

        // Event delegation to handle dynamic removal of delay code rows
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-delay-code-btn')) {
                e.target.closest('.row').remove();
            }
        });
    </script>

@endif

@if($business_area->id == 10)
        @include('frontend.business_goals.partials._internal-control-observations-data-entry-script')
@endif

    @if(in_array($business_area->id, [3, 10]))
        <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
        <script>
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        </script>
    @endif

{{--    <script>--}}
{{--        // to replace non numeric or dot characters in number inputs - NOT WORKING PROPERLY YET--}}
{{--        document.addEventListener('input', function(event) {--}}
{{--            // Check if the event is happening on an input of type number--}}
{{--            if (event.target.tagName === 'INPUT' && event.target.type === 'number') {--}}
{{--                // Use a small timeout to allow the paste operation to complete--}}
{{--                setTimeout(function() {--}}
{{--                    // Replace any character that is not a digit (0-9) or a dot (.) with an empty string--}}
{{--                    event.target.value = event.target.value.replace(/[^0-9.]/g, '');--}}
{{--                }, 0);--}}
{{--            }--}}
{{--        });--}}

{{--    </script>--}}

@endpush
