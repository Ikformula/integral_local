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
                    <div class="col">
                        <div class="form-group">
                            <select name="pilot_id" class="form-control">
                                <option {{ isset($_GET['pilot_id']) ? $_GET['pilot_id'] : 'selected' }} disabled>{{ isset($pilot) ? $pilot->user->full_name : 'Select One' }}</option>
                                @foreach($pilots as $pilot)
                                    <option value="{{ $pilot->id }}">{{ $pilot->user->full_name }}</option>
                                @endforeach
                            </select>
                            <label>Pilot</label>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <input type="text" name="date_range" class="form-control" value="{{ $from_date->format('d/m/Y') }} - {{ $to_date->format('d/m/Y') }}">
                            <label>Date Range</label>
                        </div>
                    </div>

                    <div class="col">

                        <div class="form-group">
                            <select name="aircraft_id" class="form-control">
                                <option {{ isset($_GET['aircraft_id']) ? $_GET['aircraft_id'] : 'selected' }} disabled>{{ isset($aircraft) ? $aircraft->registration_number : 'Select One' }}</option>
                                @foreach($aircrafts as $aircraft)
                                    <option value="{{ $aircraft->id }}">{{ $aircraft->registration_number }}</option>
                                @endforeach
                            </select>
                            <label>Aircraft Reg. Number</label>
                        </div>
                    </div>

                    <div class="col">

                        <div class="form-group">
                            <select name="departure_location_id" class="form-control">
                                <option {{ isset($_GET['departure_location_id']) ? 'value="'.$_GET['departure_location_id'].'"' : 'disabled' }} selected>{{ isset($departure_location_id) ? $departure_location_id->name : 'Select One' }}</option>
                                @foreach($locations as $departure_location)
                                    <option value="{{ $departure_location->id }}">{{ $departure_location->name }}</option>
                                @endforeach
                            </select>
                            <label>Departure Location</label>
                        </div>
                        </div>

                    <div class="col">
                        <div class="form-group">
                            <select name="arrival_location_id" class="form-control">
                                <option {{ isset($_GET['arrival_location_id']) ? 'value="'.$_GET['arrival_location_id'].'"' : 'disabled' }} selected>{{ isset($arrival_location_id) ? $arrival_location_id->name : 'Select One' }}</option>
                                @foreach($locations as $arrival_location)
                                    <option value="{{ $arrival_location->id }}">{{ $arrival_location->name }}</option>
                                @endforeach
                            </select>
                            <label>Arrival Location</label>
                        </div>

                    </div>
                    <div class="col form-group">
                        <button type="submit" class="btn btn-primary btn">Apply filters</button>
                    </div>
                </div>

            </form>
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
                        <span class="info-box-icon bg-{{ $colours[array_rand($colours)] }}"><i class="fas fa-poll"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">OFP</span>
                            <span class="info-box-number" id="OFP_amount"></span>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="info-box shadow">
                        <span class="info-box-icon bg-{{ $colours[array_rand($colours)] }}"><i class="fas fa-poll"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Carried</span>
                            <span class="info-box-number" id="carried_amount"></span>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="info-box shadow">
                        <span class="info-box-icon bg-{{ $colours[array_rand($colours)] }}"><i class="fas fa-poll"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Discrepancy</span>
                            <span class="info-box-number" id="discrepancy_amount"></span>
                        </div>
                    </div>
                </div>

            </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header" id="fuel-records-header">
                            <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#addRecordModal">Add New Record <i class="fa fa-plus"></i> </button>
                        </div>
                        <div class="card-body">
<div class="table-responsive">
    <table class="table table-striped" id="fuel-records-data">
        <thead>
        <tr>
            <td>S/N</td>
            <td>Flight Date</td>
            <td>A/C Reg. Num.</td>
            <td>Flight Number</td>
            <td>Departure</td>
            <td>Arrival</td>
            <td>Pilot</td>
            <td>OFP Dispatch</td>
            <td>OFP Pilot Agreed</td>
            <td>Actual Carried</td>
            <td>Discrepancy</td>
{{--            <td>Action</td>--}}
        </tr>
        </thead>

        <tbody>
        @php
        $discrepancies = 0;
        $ofp_dispatches = 0;
        $carried = 0;
        @endphp
        @foreach($fueling_reports as $fueling_report)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $fueling_report->flight_date }}</td>
                <td>{{ $fueling_report->aircraft->registration_number }}</td>
                <td>{{ $fueling_report->flight_number }}</td>
                <td>{{ $fueling_report->departure_location() }}</td>
                <td>{{ $fueling_report->arrival_location() }}</td>
                <td>{{ $fueling_report->pilot_user->full_name }}</td>
                <td>{{ number_format($fueling_report->ofp_dispatch) }}</td>
                <td>{{ number_format($fueling_report->ofp_pilot_agreed) }}</td>
                <td>{{ number_format($fueling_report->actual_fuel_amount_carried) }}</td>
                @php
                    $ofp_dispatches += $fueling_report->ofp_dispatch;
                    $carried += $fueling_report->actual_fuel_amount_carried;
                    $discrepancy =  $fueling_report->actual_fuel_amount_carried - $fueling_report->ofp_dispatch;
                    $discrepancies += $discrepancy;
                @endphp
                <td>{{ number_format($discrepancy) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
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
                    <h4 class="modal-title">Modal title</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>What follows is just some placeholder text for this modal dialog. Sipping on Rosé, Silver Lake sun, coming up all lazy. It’s in the palm of your hand now baby. So we hit the boulevard. So make a wish, I'll make it like your birthday everyday. Do you ever feel already buried deep six feet under? It's time to bring out the big balloons. You could've been the greatest. Passport stamps, she's cosmopolitan. Your kiss is cosmic, every move is magic.</p>
                    <p>We're living the life. We're doing it right. Open up your heart. I was tryna hit it and quit it. Her love is like a drug. Always leaves a trail of stardust. The girl's a freak, she drive a jeep in Laguna Beach. Fine, fresh, fierce, we got it on lock. All my girls vintage Chanel baby.</p>
                    <p>Before you met me I was alright but things were kinda heavy. Peach-pink lips, yeah, everybody stares. This is no big deal. Calling out my name. I could have rewrite your addiction. She's got that, je ne sais quoi, you know it. Heavy is the head that wears the crown. 'Cause, baby, you're a firework. Like thunder gonna shake the ground.</p>
                    <p>Just own the night like the 4th of July! I’m gon’ put her in a coma. What you're waiting for, it's time for you to show it off. Can't replace you with a million rings. You open my eyes and I'm ready to go, lead me into the light. And here you are. I’m gon’ put her in a coma. Come on, let your colours burst. So cover your eyes, I have a surprise. As I march alone to a different beat. Glitter all over the room pink flamingos in the pool.</p>
                    <p>You just gotta ignite the light and let it shine! Come just as you are to me. Just own the night like the 4th of July. Infect me with your love and fill me with your poison. Come just as you are to me. End of the rainbow looking treasure.</p>
                    <p>I can't sleep let's run away and don't ever look back, don't ever look back. I can't sleep let's run away and don't ever look back, don't ever look back. Yes, we make angels cry, raining down on earth from up above. I'm walking on air (tonight). Let you put your hands on me in my skin-tight jeans. Stinging like a bee I earned my stripes. I went from zero, to my own hero. Even brighter than the moon, moon, moon. Make 'em go, 'Aah, aah, aah' as you shoot across the sky-y-y! Why don't you let me stop by?</p>
                    <p>Boom, boom, boom. Never made me blink one time. Yeah, you're lucky if you're on her plane. Talk about our future like we had a clue. Oh my God no exaggeration. You're original, cannot be replaced. The girl's a freak, she drive a jeep in Laguna Beach. It's no big deal, it's no big deal, it's no big deal. In another life I would make you stay. I'm ma get your heart racing in my skin-tight jeans. I wanna walk on your wave length and be there when you vibrate Never made me blink one time.</p>
                    <p>We'd keep all our promises be us against the world. If you get the chance you better keep her. It's time to bring out the big, big, big, big, big, big balloons. I hope you got a healthy appetite. Don't let the greatness get you down, oh, oh yeah. Yeah, she's footloose and so fancy free. I want the jaw droppin', eye poppin', head turnin', body shockin'. End of the rainbow looking treasure.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $('#OFP_amount').html({{ number_format($ofp_dispatches) }});
        $('#carried_amount').html({{ number_format($carried) }});
        $('#discrepancy_amount').html({{ number_format($discrepancies) }});
    </script>

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
@endpush
