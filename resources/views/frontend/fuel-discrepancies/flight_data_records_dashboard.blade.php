@extends('frontend.layouts.app')

@section('title', 'FuDiscReps' )

@push('after-styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    @include('includes.partials._datatables-css')
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">

                <form method="get">
                    <h5>Filter</h5>
                    <div class="row">
                        @if(!$is_pilot)
                        <div class="col">
                            <div class="form-group">
                                <select name="pilot_id" class="form-control">
                                    <option
                                        {{ isset($_GET['pilot_id']) ? $_GET['pilot_id'] : 'selected' }} disabled>{{ isset($pilot) ? $pilot->user->full_name : 'Select One' }}</option>
                                    @foreach($pilots as $pilot)
                                        <option value="{{ $pilot->id }}">{{ $pilot->full_name }}, {{ $pilot->company_id }}</option>
                                    @endforeach
                                </select>
                                <label>Pilot</label>
                            </div>
                        </div>
                        @endif

                        <div class="col">

                            <div class="form-group">
                                <select name="aircraft_id" class="form-control">
                                    <option
                                        {{ isset($_GET['aircraft_id']) ? $_GET['aircraft_id'] : 'selected' }} disabled>{{ isset($aircraft) ? $aircraft->registration_number : 'Select One' }}</option>
                                    @foreach($aircrafts as $aircraft)
                                        <option
                                            value="{{ $aircraft->id }}">{{ $aircraft->registration_number }}</option>
                                    @endforeach
                                </select>
                                <label>Aircraft Reg. Number</label>
                            </div>
                        </div>
                        @include('frontend.includes._date-range-filter')


                        @if(1 < 0)
                        <div class="col">

                            <div class="form-group">
                                <select name="departure_location_id" class="form-control">
                                    <option
                                        {{ isset($_GET['departure_location_id']) ? 'value="'.$_GET['departure_location_id'].'"' : 'disabled' }} selected>{{ isset($departure_location_id) ? $departure_location_id->name : 'Select One' }}</option>
                                    @foreach($locations as $departure_location)
                                        <option
                                            value="{{ $departure_location->id }}">{{ $departure_location->name }}</option>
                                    @endforeach
                                </select>
                                <label>Departure Location</label>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <select name="arrival_location_id" class="form-control">
                                    <option
                                        {{ isset($_GET['arrival_location_id']) ? 'value="'.$_GET['arrival_location_id'].'"' : 'disabled' }} selected>{{ isset($arrival_location_id) ? $arrival_location_id->name : 'Select One' }}</option>
                                    @foreach($locations as $arrival_location)
                                        <option
                                            value="{{ $arrival_location->id }}">{{ $arrival_location->name }}</option>
                                    @endforeach
                                </select>
                                <label>Arrival Location</label>
                            </div>

                        </div>
                        @endif
                        <div class="col form-group">
                            <button type="submit" class="btn btn-primary btn">Apply filters</button>
                        </div>
                    </div>


                </form>
            @if(1 < 0)
                @php $colours = [
    'navy',
    'maroon',
    // 'info',
    'danger',
    // 'warning',
    // 'success'
];
                @endphp
                <div class="row">
                    <div class="col">
                        <div class="info-box shadow">
                            <span class="info-box-icon bg-{{ $colours[array_rand($colours)] }}"><i
                                    class="fas fa-poll"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">OFP</span>
                                <span class="info-box-number" id="OFP_amount"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="info-box shadow">
                            <span class="info-box-icon bg-{{ $colours[array_rand($colours)] }}"><i
                                    class="fas fa-poll"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Carried</span>
                                <span class="info-box-number" id="carried_amount"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="info-box shadow">
                            <span class="info-box-icon bg-{{ $colours[array_rand($colours)] }}"><i
                                    class="fas fa-poll"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Discrepancy</span>
                                <span class="info-box-number" id="discrepancy_amount"></span>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header" id="fuel-records-header">
                            <button type="button" class="btn bg-maroon" data-toggle="modal"
                                    data-target="#addRecordModal">Add New Record <i class="fa fa-plus"></i></button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
{{--                                @if(1 < 0)--}}
                                    <table class="table table-striped" id="fuel-records-data">
                                        <thead>
                                        <tr>
                                            <td>S/N</td>
                                            <td>Envelope Number</td>
                                            <td>A/C Reg. Num.</td>
                                            <td>Pilots</td>
{{--                                            <td>OFP Dispatch</td>--}}
{{--                                            <td>OFP Pilot Agreed</td>--}}
{{--                                            <td>Actual Carried</td>--}}
                                            <td>Discrepancy</td>
                                            <td>Date</td>
                                                        <td>Action</td>
                                        </tr>
                                        </thead>

                                        <tbody>
{{--                                        @php--}}
{{--                                            $discrepancies = 0;--}}
{{--                                            $ofp_dispatches = 0;--}}
{{--                                            $carried = 0;--}}
{{--                                        @endphp--}}
                                        @foreach($flight_envelopes as $flight_envelope)
                                            @php $cell_values = $flight_envelope->cell_values; @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $flight_envelope->flight_envelope_number }}</td>
                                                <td>{{ $flight_envelope->aircraft->registration_number }}</td>
                                                <td>

                                                    @for($i = 1; $i <= 5; $i++)
                                                        @php
                                                            $rank = getFeCellValue($cell_values, 'flight_deck_rank_' . $i);
                                                            $nameCode = getFeCellValue($cell_values, 'flight_deck_name_code_' . $i);
                                                            $companyId = getFeCellValue($cell_values, 'flight_deck_company_id_no_' . $i);
                                                        @endphp

                                                        @if(isset($rank, $nameCode, $companyId))
                                                            {{ $rank ?? '' }} {{ $nameCode ?? '' }}, {{ $companyId ?? '' }} <br>
                                                        @endif
                                                    @endfor
                                                </td>

{{--                                                <td>{{ $flight_envelope->departure_location() }}</td>--}}
{{--                                                <td>{{ $flight_envelope->arrival_location() }}</td>--}}
{{--                                                <td>{{ $flight_envelope->pilot_user->full_name }}</td>--}}
{{--                                                <td>{{ number_format(getFeCellValue($cell_values, 'fuel_for_next_flight_depart')) }}</td>--}}
{{--                                                <td>{{ number_format($flight_envelope->ofp_pilot_agreed) }}</td>--}}
{{--                                                <td>{{ number_format(getFeCellValue($cell_values, 'fuel_for_next_flight_depart')) }}</td>--}}
{{--                                                @php--}}
{{--                                                    $ofp_dispatches += $flight_envelope->ofp_dispatch;--}}
{{--                                                    $carried += $flight_envelope->actual_fuel_amount_carried;--}}
{{--                                                    $discrepancy =  $flight_envelope->actual_fuel_amount_carried - $flight_envelope->ofp_dispatch;--}}
{{--                                                    $discrepancies += $discrepancy;--}}
{{--                                                @endphp--}}
                                                <td>{{ number_format(getFeCellValue($cell_values, 'fuel_qty_check_litres_discrepancy')) }} ltrs</td>
                                                <td>{{ $flight_envelope->created_at->toDateString() }}</td>
                                                <td><a href="{{ route('frontend.flight_envelopes.records.edit', $flight_envelope->id) }}" class="btn btn-info">View</a> </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
{{--                                @endif--}}
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>

    <div class="modal fade" id="addRecordModal" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Flight Envelope</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('frontend.flight_envelopes.records.store') }}" method="POST">
                        @csrf
                            <div ref="component"
                                 class="form-group has-feedback formio-component formio-component-textfield formio-component-planNumber"
                                 id="ebpmy5k">


                                <label id="l-ebpmy5k-planNumber" for="ebpmy5k-planNumber" class="col-form-label"
                                       ref="label">
                                    Plan Number

                                </label>


                                <div ref="element">


                                    <input aria-required="false" aria-labelledby="l-ebpmy5k-planNumber"
                                           id="ebpmy5k-planNumber" value="" spellcheck="true" lang="en"
                                           class="form-control" type="text" name="plan_number" ref="input">


                                </div>


                                <div class="formio-errors invalid-feedback" ref="messageContainer"></div>

                            </div>
                            <div ref="component"
                                 class="form-group has-feedback formio-component formio-component-textfield formio-component-aircraftReg"
                                 id="epqnlol">


                                <label id="l-aircraft_registration_number" for="aircraft_registration_number" class="col-form-label"
                                       ref="label">
                                    Aircraft Reg.


                                </label>


                                <div ref="element">


                                    <input aria-required="false" aria-labelledby="l-aircraft_registration_number"
                                           id="aircraft_registration_number" value="" spellcheck="true" lang="en"
                                           class="form-control" type="search" name="aircraftReg" ref="input" list="ac_regs">


                                    <datalist id="ac_regs">
                                        @foreach($aircrafts as $aircraft)
                                            <option value="{{ $aircraft->registration_number }}">{{ $aircraft->registration_number }}</option>
                                        @endforeach
                                    </datalist>
                                </div>


                                <div class="formio-errors invalid-feedback" ref="messageContainer"></div>

                            </div>
                            <div ref="component"
                                 class="form-group has-feedback formio-component formio-component-textfield formio-component-aircraftType"
                                 id="e97w7ny">


                                <label id="l-ac_type" for="ac_type" class="col-form-label"
                                       ref="label">
                                    Aircraft Type


                                </label>


                                <div ref="element">


                                    <input aria-required="false" aria-labelledby="l-ac_type"
                                           id="ac_type" value="" spellcheck="true" lang="en"
                                           class="form-control" type="text" name="aircraftType" ref="input">


                                </div>


                                <div class="formio-errors invalid-feedback" ref="messageContainer"></div>

                            </div>
                            <div ref="component"
                                 class="form-group has-feedback formio-component formio-component-button formio-component-submit  form-group"
                                 id="e75cmy">


                                <button lang="en" class="btn btn-primary btn-md btn-block" type="submit" name="data[submit]"
                                        ref="button">

                                    Submit


                                </button>
                                <div ref="buttonMessageContainer">
                                    <span ref="buttonMessage" class="help-block"></span>
                                </div>


                                <div class="formio-errors invalid-feedback" ref="messageContainer"></div>

                            </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    {{--    <script>--}}
    {{--        $('#OFP_amount').html({{ number_format($ofp_dispatches) }});--}}
    {{--        $('#carried_amount').html({{ number_format($carried) }});--}}
    {{--        $('#discrepancy_amount').html({{ number_format($discrepancies) }});--}}
    {{--    </script>--}}

    <script type="text/javascript">
        $(function () {

            $('input[name="date_range"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="date_range"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            });

            $('input[name="date_range"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

        });
    </script>

    @include('includes.partials._datatables-js')

    <script>
        $("#fuel-records-data").DataTable({
            "responsive": false, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465,
            "buttons": ["colvis"]
        }).buttons().container().appendTo('#fuel-records-data_wrapper .col-md-6:eq(0)');
    </script>

    @include('frontend.includes.aircraft_selector-js')
@endpush
