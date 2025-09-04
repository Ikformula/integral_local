@extends('frontend.layouts.app')

@section('title', 'Business Areas')

@push('after-styles')
    @include('frontend.business_goals.partials._groups-styles')
    <style>
        #btn-back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid">
        <script>
            let bscStats = @json($bsc_stats);
            let commRevenueStats = '';
        </script>
@include('frontend.business_goals.partials._groups-week-selection')

        <div id="bsc-stats" class="row"></div>
        <div id="commercial-stats" class="row"></div>

        @include('frontend.business_goals.parts._periodic-stats')
{{--        <div class="card-columns">--}}
        <div class="row">
            @foreach($business_areas_custom_order as $order)
                @php
                    $business_area = $all_business_area_array[$order];
                    $business_area_id = $business_area->id;
                    $presentation_data = $multi_presentation_data[$business_area->id];
                    $form_fields = $multi_form_fields[$business_area->id];
                    $form_fields_collection = $multi_form_fields_collection[$business_area->id];
                @endphp

                <div class="col{{ count($accessible_business_areas) == 1 || $business_area_id == 11 ? '-md-12' : '-md-6'  }} @if(!array_key_exists($business_area_id, $accessible_business_areas)) d-none @endif">
                    <div class="card arik-card">
                        <div class="card-header shadow-sm">
                            <div class="text-right">
                                @if($business_area_id == 3)
                                    <button class="btn bg-gradient-maroon flight-ops-zoom-btn" data-toggle="modal" data-target="#flight-ops-zoomModal"
                                            data-content-id="bsc-stats-content-{{ $business_area_id }}">
                                        <i class="fa fa-search-plus"></i> Zoom
                                    </button>
                                @else
                                <button class="btn bg-gradient-maroon zoom-btn" data-toggle="modal" data-target="#zoomModal"
                                        data-content-id="bsc-stats-content-{{ $business_area_id }}"
                                        data-report-title="{{ $business_area->name }}"
                                >
                                    <i class="fa fa-search-plus"></i> Zoom
                                </button>
                                @endif
                            </div>
{{--                            <div class="card-tools">--}}
{{--                                <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
                        </div>
                        <div class="card-body scrollable-div" style="height: 700px; overflow: scroll;">
                            <div id="bsc-stats-content-{{ $business_area_id }}">
                                @include('frontend.business_goals.quadrants._'.$business_area_id, ['bsc_stats' => &$bsc_stats, 'presentation_mode' => 'on'])
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        {{--        Aircraft Status--}}
{{--        @include('frontend.business_goals.quadrants._11', ['presentation_mode' => 'on'])--}}


        <!-- Modal Structure -->
        <div class="modal fade" id="zoomModal" tabindex="-1" aria-labelledby="zoomModalLabel" aria-hidden="true">
{{--          Zoom solution found on  https://bootsnipp.com/snippets/GagvV 17th of October, 2024 --}}
            <div class="modal-dialog modal-xl" style="-webkit-transform: translate(0,0)scale(1.2);transform: translate(0,0)scale(1.2); max-width: 80%;">
                <div class="modal-content">
                    <div class="modal-header shadow-sm pt-5">
                        <h5 class="modal-title" id="zoomModalLabel">Detailed View</h5>
                        <strong>Wk {{ $week_in_focus->week_number }}: {{ $week_in_focus->from_date->format('jS') }} - {{ $week_in_focus->to_date->format('jS \\of F Y') }} & Wk {{ $previousWeek->week_number }}: {{ $previousWeek->from_date->format('jS') }} - {{ $previousWeek->to_date->format('jS \\of F Y') }}</strong>
                        <button type="button" class="btn btn-secondary btn-sm btn-close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i> </button>
                    </div>
                    <div class="modal-body" style="padding-top: 0rem;">
                        <!-- Content will be injected here -->

                        <div id="zoomModalContent" class="mt-2"></div>
                    </div>
                </div>
            </div>
        </div>

{{-- for flight ops operations delivery--}}
        <div class="modal fade" id="flight-ops-zoomModal" tabindex="-1" aria-labelledby="flight-ops-zoomModalLabel" aria-hidden="true">
{{--          Zoom solution found on  https://bootsnipp.com/snippets/GagvV 17th of October, 2024 --}}
            <div class="modal-dialog modal-xl" style="-webkit-transform: translate(0,0)scale(1.2);transform: translate(0,0)scale(1.2); max-width: 80%;">
                <div class="modal-content">
                    <div class="modal-header shadow-sm pt-5">
                        <h5 class="modal-title" id="flight-ops-zoomModalLabel">Flight Ops - Operational Delivery / Customer Service</h5>
                        <strong>Wk {{ $week_in_focus->week_number }}: {{ $week_in_focus->from_date->format('jS') }} - {{ $week_in_focus->to_date->format('jS \\of F Y') }} & Wk {{ $previousWeek->week_number }}: {{ $previousWeek->from_date->format('jS') }} - {{ $previousWeek->to_date->format('jS \\of F Y') }}</strong>
                        <button type="button" class="btn btn-secondary btn-sm btn-close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i> </button>
                    </div>
                    <div class="modal-body" style="padding-top: 0;">
                        <!-- Content will be injected here -->
                        <div class=" zoom-content">
                            @php
                                $business_area_id = 3;
                                $business_area = $all_business_area_array[$business_area_id];
                                $presentation_data = $multi_presentation_data[$business_area_id];
                                $form_fields = $multi_form_fields[$business_area_id];
                                $form_fields_collection = $multi_form_fields_collection[$business_area_id];
                            @endphp
                            @include('frontend.business_goals.quadrants._3', ['presentation_mode' => 'off'])
                        </div>
                    </div>
                </div>
            </div>
        </div>


</div>
        @endsection

@push('after-scripts')
    <script>
        $(document).ready(function(){

            // Keys for which we want to create widgets
            let keysToDisplay = [
            @if($logged_in_user->can('see all business score cards') || in_array($logged_in_user->email, ['ailemen.arumemi-johnson@arikair.com', 'henry.ejiogu@arikair.com', 'prince.ahuchaogu@arikair.com']))
                68, // Available Aircraft
                62, // No. of Flights operated
                66, // Total PAX
                'Total Sales',
                56, // Average Fare
                55, // Revenue
                // 'OTP (Flt. Ops)', // OTP
                'OTP (Planning)', // OTP
                'Load Factor',
                // 'Completion Factor',
                'Flight completion (Planning)'

            @else

                68, // Available Aircraft
                62, // No. of Flights operated
                66, // Total PAX
                'OTP (Flt. Ops)', // OTP
                // 'OTP (Planning)', // OTP
                'Load Factor',
                'Completion Factor',
                // 'Flight completion (Planning)'
            @endif
                ];

            // Get the container where the widgets will be appended
            let container = document.getElementById('bsc-stats');
            console.log(bscStats);
            // Generate widgets for each key
            keysToDisplay.forEach(function(key) {
                // Ensure the key exists in the bscStats data
                if (bscStats[key]) {
                    let title = bscStats[key].title;
                    let currentWeek = bscStats[key].weeks['Wk {{ $week_in_focus->week_number }}'];
                    let previousWeek = bscStats[key].weeks['Wk {{ $previousWeek->week_number }}'];
                    let variance = bscStats[key].variance;
                    let targetColourA = bscStats[key].target_colours ? bscStats[key].target_colours.a : '';
                    let targetColourB = bscStats[key].target_colours ? bscStats[key].target_colours.b : '';
                    let unitSymbol = bscStats[key].unit_symbol ?? '';
                    let targetAmount = bscStats[key].target;
                    let targetContent = '';
                    if(targetAmount && typeof targetAmount == "number"){
                        targetContent = `<button class="ml-5 btn bg-navy" style="display:inline;">${targetAmount ? 'Target: ' + targetAmount + unitSymbol: ''}</button>`;
                    }

                    // Create the widget HTML structure
                    let widgetHtml = `
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header border-0" style="">
                            <h4 class="mb-0" style="display:inline;">${title}</h4>
                                    ${targetContent}
                                    ${variance}

                        </div>
                        <div class="card-body pt-0 pb-1">
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-muted mb-0">Wk {{ $week_in_focus->week_number }}</p>
                                    <strong class="d-lg-none ${targetColourA}">${currentWeek}</strong>
                                    <h3 class="text-bold d-none d-lg-block ${targetColourA}">${currentWeek}</h3>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-0">Wk {{ $previousWeek->week_number }}</p>
                                    <strong class="d-lg-none ${targetColourB}">${previousWeek}</strong>
                                    <h3 class="text-bold d-none d-lg-block ${targetColourB}">${previousWeek}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                    // Append the widget to the container
                    container.innerHTML += widgetHtml;
                }
            });

            document.getElementById('commercial-stats').innerHTML = commRevenueStats;
        });


    </script>
    @include('frontend.business_goals.partials._modal-zoom-js')
@endpush

@push('before-scripts')
    <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-danger btn-floating btn-lg" title="Scroll back to the top of the page" id="btn-back-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // https://mdbootstrap.com/docs/standard/extended/back-to-top/
        //Get the button
        let mybutton = document.getElementById("btn-back-to-top");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function () {
            scrollFunction();
        };

        function scrollFunction() {
            if (
                document.body.scrollTop > 20 ||
                document.documentElement.scrollTop > 20
            ) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }
        // When the user clicks on the button, scroll to the top of the document
        mybutton.addEventListener("click", backToTop);

        function backToTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
@endpush
