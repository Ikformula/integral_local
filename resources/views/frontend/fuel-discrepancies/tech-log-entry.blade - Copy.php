@extends('frontend.layouts.app')

@section('title', 'Flight Envelope - '.$flightEnvelope->flight_envelope_number )

@push('after-styles')
    <link href="{{ asset('plugins/24-hour-time-picker/css/timePicker.css') }}" rel="stylesheet">
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
        <td class="table-input-cell"><input type="text" name="" id="flight_deck_rank_{{ $i }}" class="form-control-sm pilots-table" list="flight_deck_rank_{{ $i }}_list">
        </td>
        <td class="table-input-cell"><input type="text" name="" id="flight_deck_company_id_no_{{ $i }}" class="form-control-sm pilots-table" list="flight_deck_company_id_no_{{ $i }}_list" oninput="fillPilotData({{ $i }}, 'company_id')">
            <datalist id="flight_deck_company_id_no_{{ $i }}_list">
                @foreach($pilots as $pilot)
                    <option value="{{ $pilot->company_id }}">{{ $pilot->company_id }}</option>
                @endforeach
            </datalist>
        </td>
        <td class="table-input-cell"><input type="text" name="" id="flight_deck_name_code_{{ $i }}" class="form-control-sm pilots-table" list="flight_deck_name_code_{{ $i }}_list" oninput="fillPilotData({{ $i }}, 'name_code')">
            <datalist id="flight_deck_name_code_{{ $i }}_list">
                @foreach($pilots as $pilot)
                    <option value="{{ $pilot->name_code }}">{{ $pilot->name_code }}</option>
                @endforeach
            </datalist>
        </td>
        <td class="table-input-cell"><input type="text" name="" id="flight_deck_duty_on_{{ $i }}" class="form-control-sm time-picker"> </td>
        <td class="table-input-cell"><input type="text" name="" id="flight_deck_duty_off_{{ $i }}" class="form-control-sm time-picker"> </td>
        <td class="table-input-cell"><input type="text" name="" id="flight_deck_fdr_{{ $i }}" class="form-control-sm time-picker"> </td>
        <td class="table-input-cell"><input type="number" name="" id="flight_deck_t_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="number" name="" id="flight_deck_l_{{ $i }}" class="form-control-sm"> </td>
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
                                       <input type="text" class="form-control-sm" id="pre_flight_inspection_name">
                                   </td>
                                </tr>
                                <tr>
                                   <td class="col-sm-3 table-input-cell">
                                       <small>Rank</small>
                                   </td>
                                   <td class="col-sm-9 table-input-cell">
                                       <input type="text" class="form-control-sm" id="pre_flight_inspection_rank">
                                   </td>
                                </tr>
                                <tr>
                                   <td class="col-sm-3 table-input-cell">
                                       <small>Date</small>
                                   </td>
                                   <td class="col-sm-9 table-input-cell">
                                       <input type="date" class="form-control-sm" id="pre_flight_inspection_date">
                                   </td>
                                </tr>
                                <tr>
                                   <td class="col-sm-3 table-input-cell">
                                       <small>Time</small>
                                   </td>
                                   <td class="col-sm-9 table-input-cell">
                                       <input type="text" step="1" class="form-control-sm" id="pre_flight_inspection_time">
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
                                       <input type="number" class="form-control-sm" id="fuel_for_next_flight_pre_uplift">
                                   </td>
                                </tr>
                                <tr>
                                   <td class="col-sm-3 table-input-cell">
                                       <small>Uplift</small>
                                   </td>
                                   <td class="col-sm-9 table-input-cell">
                                       <input type="number" class="form-control-sm" id="fuel_for_next_flight_uplift">
                                   </td>
                                </tr>
                                <tr>
                                   <td class="col-sm-3 table-input-cell">
                                       <small>Adjust</small>
                                   </td>
                                   <td class="col-sm-9 table-input-cell">
                                       <input type="number" class="form-control-sm" id="fuel_for_next_flight_adjust">
                                   </td>
                                </tr>
                                <tr>
                                   <td class="col-sm-3 table-input-cell">
                                       <small>Depart</small>
                                   </td>
                                   <td class=" table-input-cell">
                                       <input type="number" step="1" class="form-control-sm" id="fuel_for_next_flight_depart">
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
                                        <input type="number" class="form-control-sm" id="fuel_specific_gravity">
                                    </td>
                                </tr>
                                <tr>
                                    <th><small>Fuel Grade</small></th>
                                    <td class="col-sm-9 table-input-cell">
                                        <input type="text" class="form-control-sm" id="fuel_grade">
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
                                       <input type="number" class="form-control-sm" id="fuel_qty_check_litres_calculated">
                                   </td>
                                </tr>
                                <tr>
                                   <td class="col-sm-3 table-input-cell">
                                       <small>Actual</small>
                                   </td>
                                   <td class="col-sm-9 table-input-cell">
                                       <input type="number" class="form-control-sm" id="fuel_qty_check_litres_actual">
                                   </td>
                                </tr>
                                <tr>
                                   <td class="col-sm-3 table-input-cell">
                                       <small>Discrepancy</small>
                                   </td>
                                   <td class="col-sm-9 table-input-cell">
                                       <input type="number" class="form-control-sm" id="fuel_qty_check_litres_discrepancy">
                                   </td>
                                </tr>
                                <tr>
                                   <td class="col-sm-3 table-input-cell">
                                       <small>Adjustment</small>
                                   </td>
                                   <td class=" table-input-cell">
                                       <input type="number" step="1" class="form-control-sm" id="fuel_qty_check_litres_adjustment">
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
                                       <input type="number" class="form-control-sm" id="pre_departure_gauge_kgs_outer_LHS_tank">
                                   </td>
                                </tr>
                                <tr>
                                   <td class="col-sm-3 table-input-cell">
                                       <small>Inner LHS Tank</small>
                                   </td>
                                   <td class="col-sm-9 table-input-cell">
                                       <input type="number" class="form-control-sm" id="pre_departure_gauge_kgs_inner_lhs_tank">
                                   </td>
                                </tr>
                                <tr>
                                   <td class="col-sm-3 table-input-cell">
                                       <small>Center Tank(s)</small>
                                   </td>
                                   <td class="col-sm-9 table-input-cell">
                                       <input type="number" class="form-control-sm" id="pre_departure_gauge_kgs_center_tank">
                                   </td>
                                </tr>
                                <tr style="border-bottom: 2px solid #858583">
                                   <td class="col-sm-3 table-input-cell">
                                       <small>Inner RHS Tank</small>
                                   </td>
                                   <td class=" table-input-cell">
                                       <input type="number" step="1" class="form-control-sm" id="pre_departure_gauge_kgs_inner_rhs_tank">
                                   </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Outer RHS Tank</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input type="number" class="form-control-sm" id="pre_departure_gauge_kgs_outer_rhs_tank">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Trim Tank</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input type="number" class="form-control-sm" id="pre_departure_gauge_kgs_trim_tank">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col-sm-3 table-input-cell">
                                        <small>Total Fuel</small>
                                    </td>
                                    <td class="col-sm-9 table-input-cell">
                                        <input type="number" class="form-control-sm" id="pre_departure_gauge_kgs_total_fuel">
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
                                    <td class="table-input-cell"><input type="number" name="" id="departure_eng_oil_1" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="departure_eng_oil_2" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="departure_eng_oil_3" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="departure_eng_oil_4" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="departure_idg_gsds_oil_1" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="departure_idg_gsds_oil_2" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="departure_idg_gsds_oil_3" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="departure_idg_gsds_oil_4" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="departure_hydraulic_fluid_1" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="departure_hydraulic_fluid_2" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="departure_hydraulic_fluid_3" class="form-control-sm"> </td>
                                    <td class="table-input-cell"><input type="number" name="" id="departure_apu" class="form-control-sm"> </td>
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
                                    <th class="text-center" colspan="3">FUEL</th>
                                    <th class="text-center" rowspan="2">P. TRIP</th>
                                    <th class="text-center" rowspan="2">OFP Saved<br>Fuel %</th>
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
                                </tr>
                                </thead>
                                <tbody>
                                @for($i = 1; $i <= 7; $i++)
    <tr>
        <td class="table-input-cell">{{ $i }}</td>
        <td class="table-input-cell"><input type="date" name="" id="sectors_date_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="number" name="" id="sectors_flight_number_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="text" name="" id="sectors_pf_code_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="text" name="" id="sectors_airport_dep_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="text" name="" id="sectors_airport_arr_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="text" name="" id="sectors_scheduled_dep_{{ $i }}" class="form-control-sm time-picker"> </td>
        <td class="table-input-cell"><input type="text" name="" id="sectors_scheduled_arr_{{ $i }}" class="form-control-sm time-picker"> </td>
        <td class="table-input-cell"><input type="text" name="" id="sectors_actual_times_off_block_{{ $i }}" class="form-control-sm time-picker"> </td>
        <td class="table-input-cell"><input type="text" name="" id="sectors_actual_times_take_off_{{ $i }}" class="form-control-sm time-picker"> </td>
        <td class="table-input-cell"><input type="text" name="" id="sectors_actual_times_ldg_{{ $i }}" class="form-control-sm time-picker"> </td>
        <td class="table-input-cell"><input type="text" name="" id="sectors_actual_times_on_block_{{ $i }}" class="form-control-sm time-picker"> </td>
        <td class="table-input-cell"><input type="text" name="" id="sectors_flight_hrs_{{ $i }}" class="form-control-sm time-picker"> </td>
        <td class="table-input-cell"><input type="number" name="" id="sectors_block_hrs_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="number" name="" id="sectors_delay_code_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="number" name="" id="sectors_pax_c_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="number" name="" id="sectors_pax_y_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="number" name="" id="sectors_cargo_pax_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="number" name="" id="sectors_cargo_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="number" name="" id="sectors_fuel_take_off_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="number" name="" id="sectors_fuel_ldg_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="number" name="" id="sectors_fuel_burn_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="number" name="" id="sectors_p_trip_{{ $i }}" class="form-control-sm"> </td>
        <td class="table-input-cell"><input type="number" name="" id="sectors_ofp_saved_fuel_percent_{{ $i }}" class="form-control-sm"> </td>
    </tr>
                                @endfor
                                <tr>
                                    <td colspan="10"><i>*PF = Pilot Flying </i></td>
                                    <td colspan="2"><strong>TOTAL</strong></td>
                                    <td class="table-input-cell"><input type="text" name="" id="sector_total_flight_hrs" class="form-control-sm time-picker"> </td>
                                    <td class="table-input-cell"><input type="text" name="" id="sector_total_block_hrs" class="form-control-sm time-picker"> </td>
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
    <script src="{{ asset('plugins/24-hour-time-picker/js/jquery-timepicker.js') }}"></script>
    <script>
        $(".time-picker").hunterTimePicker();
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
        }
    </script>

    @include('frontend.includes.aircraft_selector-js')

    <script>
        // Sample JSON data containing details of all pilots
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
    </script>
@endpush
