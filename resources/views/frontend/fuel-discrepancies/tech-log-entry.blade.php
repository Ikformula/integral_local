@extends('frontend.layouts.app')

@section('title', 'Flight Envelope - '.$flightEnvelope->flight_envelope_number )

@push('after-styles')
    <link href="{{ asset('plugins/jq-timepicker/timepicker.css') }}" rel="stylesheet">
    <style>
        .table thead th{
            vertical-align: middle;
            border: 1px solid #a9a9a4;
        }

        th {
         align-content: center;
        }


        .table-input-cell {
            position: relative;
        }

        .table-input-cell input {
            /*border: none;*/
            width: 100%;
            height: 100%;
            padding: 5px;
            box-sizing: border-box;
            position: absolute;
            top: 0;
            left: 0;
            /*opacity: 0;*/
            cursor: pointer;
        }

        td .form-control-sm{
            border-radius: 0;
            border: 1px solid #858583;
            /*border-bottom: 1px solid #858583;*/
            /*border: none;*/
        }

        td {
            padding: 0;
            margin: 1px;
        }

        .bordered-cell {
            border: 1px solid #0a0e14;
        }

        .card-header{
            padding-top: 2px;
            padding-bottom: 2px;
        }

        /* Remove arrow buttons from number input fields */
        input[type="number"] {
            -moz-appearance: textfield;
            -webkit-appearance: textfield;
            appearance: textfield;
            margin: 0; /* Optional: adjust spacing as needed */
        }

        /* WebKit inner spin button */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0; /* Optional: adjust spacing as needed */
        }

    </style>

@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body p-0 table-responsive text-nowrap">
                            <table class="table table-sm table-hover">
                                <tbody>
                                <tr>
                                    <td class="col-sm-4 text-right">A/C REG</td>
                                    <td class="col-sm-8 table-input-cell"><input type="search" name="" id="aircraft_registration_number" class="form-control-sm" value="{{ $flightEnvelope->aircraft->registration_number }}" list="ac_regs">

                                        <datalist id="ac_regs">
                                            @foreach($aircrafts as $aircraft)
                                                <option value="{{ $aircraft->registration_number }}">{{ $aircraft->registration_number }}</option>
                                            @endforeach
                                        </datalist>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-4 text-right">A/C TYPE</td>
                                    <td class="col-sm-8 table-input-cell"><input type="text" name="" id="ac_type" class="form-control-sm" value="{{ $flightEnvelope->aircraft->ac_type }}"> </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card arik-card">
                        <div class="card-header">
<strong class="align-content-center">FLIGHT DECK</strong>
                        </div>
                        <div class="card-body p-0 table-responsive text-nowrap">
<table class="table table-sm table-hover">
    <thead class="bg-gradient-maroon">
    <tr>
        <th rowspan="2" class="col-1">NO.</th>
        <th class="text-center" rowspan="2">RANK</th>
        <th class="text-center" rowspan="2">ID NO.</th>
        <th class="text-center" rowspan="2">NAME CODE</th>
        <th class="text-center" colspan="2">DUTY</th>
        <th class="text-center" rowspan="2">FDR</th>
        <th class="text-center" rowspan="2">T /</th>
        <th class="text-center" rowspan="2">L</th>
    </tr>
    <tr>
        <th class="text-center">ON</th>
        <th class="text-center">OFF</th>
    </tr>
    </thead>
    <tbody>
    @for($i = 1; $i <= 5; $i++)
    <tr>
        <td class="table-input-cell">{{ $i }}</td>
        <td class="table-input-cell"><input class="form-control-sm pilots-table" type="text" name="" id="flight_deck_rank_{{ $i }}" list="flight_deck_rank_{{ $i }}_list"></td>
        <td class="table-input-cell"><input class="form-control-sm pilots-table" type="text" name="" id="flight_deck_company_id_no_{{ $i }}" list="flight_deck_company_id_no_{{ $i }}_list" oninput="fillPilotData({{ $i }}, 'company_id')">
            <datalist id="flight_deck_company_id_no_{{ $i }}_list">
                @foreach($pilots as $pilot)
                    <option value="{{ $pilot->company_id }}">{{ $pilot->company_id }}</option>
                @endforeach
            </datalist>
        </td>
        <td class="table-input-cell"><input class="form-control-sm pilots-table" type="text" name="" id="flight_deck_name_code_{{ $i }}" list="flight_deck_name_code_{{ $i }}_list" oninput="fillPilotData({{ $i }}, 'name_code')">
            <datalist id="flight_deck_name_code_{{ $i }}_list">
                @foreach($pilots as $pilot)
                    <option value="{{ $pilot->name_code }}">{{ $pilot->name_code }}</option>
                @endforeach
            </datalist>
        </td>
        <td class="table-input-cell"><input class="form-control-sm time-picker" type="text" pattern="(?:[01]\d|2[0-3]):[0-5]\d"  name="" id="flight_deck_duty_on_{{ $i }}"> </td>
        <td class="table-input-cell"><input class="form-control-sm time-picker" type="text" pattern="(?:[01]\d|2[0-3]):[0-5]\d"  name="" id="flight_deck_duty_off_{{ $i }}"> </td>
        <td class="table-input-cell"><input class="form-control-sm time-picker" type="text" pattern="(?:[01]\d|2[0-3]):[0-5]\d"  name="" id="flight_deck_fdr_{{ $i }}"> </td>
        <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="flight_deck_t_{{ $i }}"> </td>
        <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="flight_deck_l_{{ $i }}"> </td>

    </tr>
        @endfor
    </tbody>
</table>
                        </div>
                        <div class="card-footer" style="padding-top: 1px; padding-bottom: 1px; ">
                            <i>** T/L = Number of Take-Offs and Landings per crew</i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header text-center bg-gradient-green">
                            <strong>Pre Flight Inspection carried out</strong>
                        </div>
                        <div class="card-body p-0 table-responsive text-nowrap">
                            <table class="table table-sm">
                                <tbody>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Name</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="text" id="pre_flight_inspection_name">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Rank</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="text" id="pre_flight_inspection_rank">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Date</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="date" id="pre_flight_inspection_date">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Time</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="text" step="1" id="pre_flight_inspection_time">
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header text-center bg-gradient-indigo ">
                            <strong>Fuel for next flight</strong>
                        </div>
                        <div class="card-body p-0 table-responsive text-nowrap">
                            <table class="table table-sm">
                                <tbody>
                                <tr>
                                    <td colspan="2" class="text-center">Fuel required in kilos/pounds</td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Pre-Uplift</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="number" id="fuel_for_next_flight_pre_uplift">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Uplift</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="number" id="fuel_for_next_flight_uplift" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Adjust</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="number" id="fuel_for_next_flight_adjust">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Depart</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="number" step="1" id="fuel_for_next_flight_depart">
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">

                        <div class="card-body p-0 table-responsive text-nowrap">
                            <table class="table table-sm">
                                <tbody>
                                <tr>
                                    <th><small>Fuel S.G.</small></th>
                                    <td class="col-sm-3 table-input-cell">
                                        <input class="form-control-sm" type="number" id="fuel_specific_gravity">
                                    </td>
                                </tr>
                                <tr>
                                    <th><small>Fuel Grade</small></th>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="text" id="fuel_grade">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header text-center bg-gradient-dark">
                            <strong>Fuel Quantity Check In Litres</strong>
                        </div>
                        <div class="card-body p-0 table-responsive text-nowrap">
                            <table class="table table-sm">
                                <tbody>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Calculated</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="number" id="fuel_qty_check_litres_calculated">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Actual</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="number" id="fuel_qty_check_litres_actual">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Discrepancy</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="number" id="fuel_qty_check_litres_discrepancy" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Adjustment</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="number" step="1" id="fuel_qty_check_litres_adjustment">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header text-center bg-gradient-cyan ">
                            <strong>Pre-departure Gauges in Kilos</strong>
                        </div>
                        <div class="card-body p-0 table-responsive text-nowrap">
                            <table class="table table-sm">
                                <tbody>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Outer LHS Tank</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm tank_gauges" type="number" id="pre_departure_gauge_kgs_outer_LHS_tank">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Inner LHS Tank</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm tank_gauges" type="number" id="pre_departure_gauge_kgs_inner_lhs_tank">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Center Tank(s)</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm tank_gauges" type="number" id="pre_departure_gauge_kgs_center_tank">
                                    </td>
                                </tr>
                                <tr style="border-bottom: 2px solid #858583">
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Inner RHS Tank</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm tank_gauges" type="number" step="1" id="pre_departure_gauge_kgs_inner_rhs_tank">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Outer RHS Tank</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm tank_gauges" type="number" id="pre_departure_gauge_kgs_outer_rhs_tank">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Trim Tank</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm tank_gauges" type="number" id="pre_departure_gauge_kgs_trim_tank">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Total Fuel</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input class="form-control-sm" type="number" id="pre_departure_gauge_kgs_total_fuel">
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-sm text-center">
                                <tbody>
                                <tr class="bg-gradient-primary">
                                    <th rowspan="2" class="bordered-cell"> </th>
                                    <th colspan="4" class="bordered-cell">Eng. Oil (Qtrs)</th>
                                    <th colspan="4" class="bordered-cell">IDG/GSDS Oil (Qtrs)</th>
                                    <th colspan="3" class="bordered-cell">Hydraulic Fluid (Qtrs)</th>
                                    <th class="bordered-cell">APU</th>
                                </tr>
                                <tr>
                                    @for($i = 1; $i <= 4; $i++)
                                        <td class="table-input-cell bordered-cell"><small>{{ $i }}</small></td>
                                    @endfor

                                    @for($i = 1; $i <= 4; $i++)
                                        <td class="table-input-cell bordered-cell"><small>{{ $i }}</small></td>
                                    @endfor

                                    @for($i = 1; $i <= 3; $i++)
                                        <td class="table-input-cell bordered-cell"><small>Sys</small></td>
                                    @endfor

                                    <td class="table-input-cell"><input type="number" name="" id="apu_" class="form-control-sm"> </td>
                                </tr>
                                <tr>
                                    <td class="table-input-cell"><small>Uplift</small></td>
                                    <td class="table-input-cell"><input type="number" name="" id="uplift_eng_oil_1" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="uplift_eng_oil_2" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="uplift_eng_oil_3" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="uplift_eng_oil_4" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="uplift_idg_gsds_oil_1" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="uplift_idg_gsds_oil_2" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="uplift_idg_gsds_oil_3" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="uplift_idg_gsds_oil_4" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="uplift_hydraulic_fluid_1" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="uplift_hydraulic_fluid_2" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="uplift_hydraulic_fluid_3" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="uplift_apu" class="form-control-sm"> </td>
                                </tr>
                                <tr>
                                    <td class="table-input-cell"><small>Departure</small></td>
                                    <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="departure_eng_oil_1"> </td>
                                    <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="departure_eng_oil_2"> </td>
                                    <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="departure_eng_oil_3"> </td>
                                    <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="departure_eng_oil_4"> </td>
                                    <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="departure_idg_gsds_oil_1"> </td>
                                    <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="departure_idg_gsds_oil_2"> </td>
                                    <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="departure_idg_gsds_oil_3"> </td>
                                    <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="departure_idg_gsds_oil_4"> </td>
                                    <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="departure_hydraulic_fluid_1"> </td>
                                    <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="departure_hydraulic_fluid_2"> </td>
                                    <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="departure_hydraulic_fluid_3"> </td>
                                    <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="departure_apu"> </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card arik-card">
                        <div class="card-header">

                            <strong class="align-content-center">SECTORS FLOWN / PASSENGER CARGO & FUEL INFORMATION</strong>
                        </div>
                        <div class="card-body p-0 table-responsive text-nowrap">
                            <table class="table table-sm table-hover">
                                <thead class="bg-gradient-navy">
                                <tr>
                                    <th rowspan="2" class="col-1">SECTOR<br>
                                        NO.</th>
                                    <th class="text-center" rowspan="2">DATE</th>
                                    <th class="text-center" rowspan="2">FLIGHT NO.</th>
                                    <th class="text-center" rowspan="2">PF (CODE)</th>
                                    <th class="text-center" colspan="2">AIRPORT</th>
                                    <th class="text-center" colspan="2">SCHEDULED</th>
                                    <th class="text-center" colspan="4">ACTUAL TIMES</th>
                                    <th class="text-center" rowspan="2">FLIGHT HRS</th>
                                    <th class="text-center" rowspan="2">BLOCK HRS</th>
                                    <th class="text-center" rowspan="2">DELAY CODE</th>
                                    <th class="text-center" colspan="2">PASSENGERS</th>
                                    <th class="text-center" rowspan="2">CARGO - PAX (Kg)</th>
                                    <th class="text-center" rowspan="2">CARGO (Kg)</th>
                                    <th class="text-center" colspan="5">FUEL</th>
                                </tr>
                                <tr>
                                    <th class="text-center">DEP</th>
                                    <th class="text-center">ARR</th>
                                    <th class="text-center">DEP</th>
                                    <th class="text-center">ARR</th>
                                    <th class="text-center">OFF BLOCK</th>
                                    <th class="text-center">TAKE OFF</th>
                                    <th class="text-center">LDG</th>
                                    <th class="text-center">ON BLOCK</th>
                                    <th class="text-center">C</th>
                                    <th class="text-center">Y</th>
                                    <th class="text-center">TAKE OFF</th>
                                    <th class="text-center">LDG</th>
                                    <th class="text-center">BURN</th>
                                    <th class="text-center">P. TRIP</th>
                                    <th class="text-center">OFP Saved<br>Fuel %</th>
                                </tr>
                                </thead>
                                <tbody>
                                @for($i = 1; $i <= 7; $i++)
                                    <tr>
                                        <td class="table-input-cell">{{ $i }}</td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="date" name="" id="sectors_date_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="sectors_flight_number_{{ $i }}" onchange="setFlightRoute({{ $i }})"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="text" name="" id="sectors_pf_code_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="text" name="" id="sectors_airport_dep_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="text" name="" id="sectors_airport_arr_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm time-picker" type="text" pattern="(?:[01]\d|2[0-3]):[0-5]\d"  name="" id="sectors_scheduled_dep_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm time-picker" type="text" pattern="(?:[01]\d|2[0-3]):[0-5]\d"  name="" id="sectors_scheduled_arr_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm time-picker" type="text" pattern="(?:[01]\d|2[0-3]):[0-5]\d"  name="" id="sectors_actual_times_off_block_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm time-picker" type="text" pattern="(?:[01]\d|2[0-3]):[0-5]\d"  name="" id="sectors_actual_times_take_off_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm time-picker" type="text" pattern="(?:[01]\d|2[0-3]):[0-5]\d"  name="" id="sectors_actual_times_ldg_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm time-picker" type="text" pattern="(?:[01]\d|2[0-3]):[0-5]\d"  name="" id="sectors_actual_times_on_block_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm flight_hrs_fields time-picker" type="text" pattern="(?:[01]\d|2[0-3]):[0-5]\d"  name="" id="sectors_flight_hrs_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm block_hrs_fields time-picker" type="text" name="" id="sectors_block_hrs_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="sectors_delay_code_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="sectors_pax_c_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="sectors_pax_y_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="sectors_cargo_pax_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="sectors_cargo_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="sectors_fuel_take_off_{{ $i }}" onchange="getTakeOffLDGDifference({{ $i }})"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="sectors_fuel_ldg_{{ $i }}" onchange="getTakeOffLDGDifference({{ $i }})"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="sectors_fuel_burn_{{ $i }}"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm p_trip" type="number" name="" id="sectors_p_trip_{{ $i }}" onchange="getTakeOffLDGDifference({{ $i }})"> </td>
                                        <td class="table-input-cell"><input class="form-control-sm" type="number" name="" id="sectors_ofp_saved_fuel_percent_{{ $i }}"> </td>
                                    </tr>
                                @endfor
                                <tr>
                                    <td colspan="10"><i>*PF = Pilot Flying </i></td>
                                    <td colspan="2"><strong>TOTAL</strong></td>
                                    <td class="table-input-cell"><input class="form-control-sm time-picker" type="text" pattern="(?:[01]\d|2[0-3]):[0-5]\d"  name="" id="sector_total_flight_hrs"> </td>
                                    <td class="table-input-cell"><input class="form-control-sm time-picker" type="text" pattern="(?:[01]\d|2[0-3]):[0-5]\d"  name="" id="sector_total_block_hrs"> </td>
                                    <td colspan="7"></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection


@push('after-scripts')
    <script src="{{ asset('plugins/jq-timepicker/timepicker.js') }}"></script>
    <script>
        $( document ).ready(function() {
            $(".time-picker").timepicker().on("change",function(){
                sendDataToServer(this.id, this.value);
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // //Saving cell data -- Start
            // Get all input fields with a specific class
            const inputFields = document.querySelectorAll('.form-control-sm');

            // Add event listener to each input field
            inputFields.forEach(function (inputField) {
                inputField.addEventListener('change', handleChange);
                // inputField.addEventListener('keyup', handleChange);
                // Add more event listeners as needed (e.g., click, input, etc.)
            });

            // Function to handle changes in input fields
            function handleChange(event) {
                // Get the id and value of the changed input field
                const id = event.target.id;
                const value = event.target.value;

                // Send the data to the POST endpoint
                sendDataToServer(id, value);
            }
            // //Saving cell data -- End


            @if(count($flightEnvelope->cell_values))
            // // Populating cells -- Start
            const cells_data = {
                @foreach($flightEnvelope->cell_values as $cell_value)
                "{{ $cell_value->cell_name }}": "{{ $cell_value->cell_value }}",
                @endforeach
            }

            for (const key in cells_data) {
                $('#' + key).val(cells_data[key]);
            }
            // // Populating cells --
            @endif
        });

        // Function to send data to the server using fetch API
        function sendDataToServer(id, value) {
            console.log(id);
            const endpoint = '{{ route('saveFieldData') }}';
            const data = {
                cell_name: id,
                cell_value: value,
                flightEnvelopeId: {{ $flightEnvelope->id }}
            };

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                    // Add any other headers as needed
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    // Handle success response from the server if needed
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Handle error response from the server if needed
                });
            return true;
        }
    </script>

    @include('frontend.includes.aircraft_selector-js')

    <script>
        // flight numbers and routes
        const flightNumbers = {
          @foreach($flight_numbers as $flight_number)
            '{{ $flight_number->flight_number }}': {dep: "{{ $flight_number->departure }}", arrival: "{{ $flight_number->arrival }}"},
          @endforeach
        };

        function setFlightRoute(sn){
            const flight_number = $('#sectors_flight_number_' + sn ).val();
            const departure = flightNumbers[flight_number].dep;
            const arrival = flightNumbers[flight_number].arrival;
            $('#sectors_airport_dep_' + sn).val(departure);
            $('#sectors_airport_arr_' + sn).val(arrival);

            sendDataToServer('sectors_airport_dep_' + sn, departure);
            sendDataToServer('sectors_airport_arr_' + sn, arrival);
        }

        // JSON data containing details of all pilots
        const pilotsDataByCompanyID = {
            @foreach($pilots as $pilot)
        '{{ $pilot->company_id }}': { nameCode: "{{ $pilot->name_code }}", idNo: "{{ $pilot->company_id }}", rank: "{{ $pilot->rank_type }}" },
            @endforeach
        };

        const pilotsDataByNameCode = {
            @foreach($pilots as $pilot)
        '{{ $pilot->name_code }}': { nameCode: "{{ $pilot->name_code }}", idNo: "{{ $pilot->company_id }}", rank: "{{ $pilot->rank_type }}" },
            @endforeach
        };

        // Function to fill pilot data based on the entered input
        function fillPilotData(rowNumber, field_name) {
            const nameCodeInput = document.getElementById(`flight_deck_name_code_${rowNumber}`);
            const companyIdNoInput = document.getElementById(`flight_deck_company_id_no_${rowNumber}`);
            const rankInput = document.getElementById(`flight_deck_rank_${rowNumber}`);

            if(field_name == 'name_code') {
                const enteredNameCode = nameCodeInput.value.trim();
                // console.log(pilotsData[rowNumber].nameCode + ' )( ' + enteredNameCode);
                if (pilotsDataByNameCode[enteredNameCode]) {
                    // Fill in the corresponding data if the name code matches
                    companyIdNoInput.value = pilotsDataByNameCode[enteredNameCode].idNo;
                    rankInput.value = pilotsDataByNameCode[enteredNameCode].rank;
                    sendDataToServer(`flight_deck_company_id_no_${rowNumber}`, pilotsDataByNameCode[enteredNameCode].idNo);
                    sendDataToServer(`flight_deck_rank_${rowNumber}`, pilotsDataByNameCode[enteredNameCode].rank);
                }
            } else if(field_name == 'company_id'){
                const entered_company_id = companyIdNoInput.value.trim().toString();
                if (pilotsDataByCompanyID[entered_company_id]) {
                    // Fill in the corresponding data if the name code matches
                    nameCodeInput.value = pilotsDataByCompanyID[entered_company_id].nameCode;
                    rankInput.value = pilotsDataByCompanyID[entered_company_id].rank;
                    sendDataToServer(`flight_deck_name_code_${rowNumber}`, pilotsDataByCompanyID[entered_company_id].nameCode);
                    sendDataToServer(`flight_deck_rank_${rowNumber}`, pilotsDataByCompanyID[entered_company_id].rank);
                }
            } else {
                // Clear the values if there is no matching name code
                companyIdNoInput.value = "";
                rankInput.value = "";
                console.log(rowNumber + ' )( ' + enteredNameCode);
            }
        }

        function addValueToCell(cells_class, cell_id){
            totalCellValue = 0;
            let temp = 0;

            const relevantInputFields = document.querySelectorAll('.' + cells_class);
            // Add event listener to each input field
            relevantInputFields.forEach(function (inputField) {
                temp = inputField.value.trim();
                temp = Number(temp);
                if(temp && Number.isFinite(temp)){
                    totalCellValue += temp;
                }
            });

            $('#' + cell_id).val(totalCellValue);
            sendDataToServer(cell_id, totalCellValue);
            return totalCellValue;
        }


        $(document).ready(function(){
           $('.p_trip').change(function(){
               let totalPTrip = addValueToCell('p_trip', 'fuel_for_next_flight_depart');
               calcUplift();
           });

           //tank_gauges
            $('.tank_gauges').change(function(){
                let totalValue = addValueToCell('tank_gauges', 'pre_departure_gauge_kgs_total_fuel');
                $('#fuel_for_next_flight_pre_uplift').val(totalCellValue);
                sendDataToServer('fuel_for_next_flight_pre_uplift', totalCellValue);
                calcUplift();
            });

            $('.block_hrs_fields').change(function(){
                let totalValues = sumTimeFields('block_hrs_fields', 'sector_total_block_hrs');
            });

            $('.flight_hrs_fields').change(function(){
                let totalValues = sumTimeFields('flight_hrs_fields', 'sector_total_flight_hrs');
            });
        });

        function calcUplift(){
            const preUplift = Number($('#fuel_for_next_flight_pre_uplift').val());
            const departUplift = Number($('#fuel_for_next_flight_depart').val());
            const total = departUplift - preUplift;
            $('#fuel_for_next_flight_uplift').val(total);
            sendDataToServer('fuel_for_next_flight_uplift', total);
            calcFuelQtyInLtrs();
        }

        function calcFuelQtyInLtrs(){
            const fuelSpecificGravity = Number($('#fuel_specific_gravity').val());
            const upliftFuel = Number($('#fuel_for_next_flight_uplift').val());
            const calculated = Math.ceil(upliftFuel/fuelSpecificGravity);
            $('#fuel_qty_check_litres_calculated').val(calculated);
            calcDiscrepancy();
            sendDataToServer('fuel_qty_check_litres_calculated', calculated);
        }

        $('#fuel_specific_gravity').change(calcFuelQtyInLtrs);

        function sumTimeFields(fieldClasses, targetTotalFieldId) {
            const timeFields = document.querySelectorAll('.' + fieldClasses);
            let totalHours = 0;
            let totalMinutes = 0;

            timeFields.forEach(function (timeField) {
                const value = timeField.value.trim();

                if (isValidTimeFormat(value)) {
                    const [hours, minutes] = value.split(':');
                    totalHours += parseInt(hours, 10);
                    totalMinutes += parseInt(minutes, 10);
                }
            });

            // Adjust totalHours and totalMinutes
            totalHours += Math.floor(totalMinutes / 60);
            totalMinutes %= 60;

            // Display or use the total in your target element
            const targetTotalField = document.getElementById(targetTotalFieldId);
            if (targetTotalField) {
                targetTotalField.textContent = `${totalHours}:${totalMinutes.toString().padStart(2, '0')}`;
            }

            const totalValue = totalHours + ':' + totalMinutes;
            $('#' + targetTotalFieldId).val(totalValue);
            sendDataToServer(targetTotalFieldId, totalValue);

            return { totalHours, totalMinutes };
        }

        function isValidTimeFormat(value) {
            // Regular expression to check if the value matches the pattern "hh:mm"
            const timeRegex = /^([01]\d|2[0-3]):([0-5]\d)$/;

            return timeRegex.test(value);
        }

        function getTakeOffLDGDifference(sn){
            const take_off = $('#sectors_fuel_take_off_' + sn).val();
            const ldg = $('#sectors_fuel_ldg_' + sn).val();
            const burn_off = take_off - ldg;
            $('#sectors_fuel_burn_' + sn).val(burn_off);
            calcFuelPercentSaved(sn);
            sendDataToServer('sectors_fuel_burn_' + sn, burn_off);
        }

        function calcFuelPercentSaved(sn){
            const p_trip = $('#sectors_p_trip_' + sn).val();
            const burn_off = $('#sectors_fuel_burn_' + sn).val();
            console.log('burn_off' + burn_off);
            const percent_saved = ((p_trip - burn_off) / p_trip) * 100;
            console.log('OFP saved ' + percent_saved);
            $('#sectors_ofp_saved_fuel_percent_' + sn).val(percent_saved);
            sendDataToServer('sectors_ofp_saved_fuel_percent_' + sn, percent_saved);
            return true;
        }

        $('#fuel_qty_check_litres_actual').change(calcDiscrepancy);

        function calcDiscrepancy(){
            const fuel_qty_check_litres_calculated = $('#fuel_qty_check_litres_calculated').val();
            const fuel_qty_check_litres_actual = $('#fuel_qty_check_litres_actual').val();
            const discrepancy = fuel_qty_check_litres_actual - fuel_qty_check_litres_calculated;
            $('#fuel_qty_check_litres_discrepancy').val(discrepancy);
            sendDataToServer('fuel_qty_check_litres_discrepancy', discrepancy);
        }
    </script>
@endpush
