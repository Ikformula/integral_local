@extends('frontend.layouts.app')

@section('title', $title ?? 'Business Areas')

@push('after-styles')
    @include('frontend.business_goals.partials._groups-styles')
@endpush


@section('content')
    <div class="container-fluid">

        @include('frontend.business_goals.partials._groups-week-selection')


{{--        <div class="card-columns">--}}
        <div class="row">
            @foreach($business_areas_custom_order as $order)
                @php
                    $business_area = $accessible_business_areas[$order];
                    $business_area_id = $business_area->id;
                    $presentation_data = $multi_presentation_data[$business_area->id];
                    $form_fields = $multi_form_fields[$business_area->id];
                    $form_fields_collection = $multi_form_fields_collection[$business_area->id];
                @endphp

                <div class="col{{ count($accessible_business_areas) > 1 ? '-md-6' : ''  }}">
                    <div class="card arik-card">
                        <div class="card-header shadow-sm">
                            <div class="text-right">

                                <button class="btn bg-gradient-maroon zoom-btn" data-toggle="modal" data-target="#zoomModal"
                                        data-content-id="bsc-stats-content-{{ $business_area_id }}"
                                        data-report-title="{{ $business_area->name }}"
                                >
                                    <i class="fa fa-search-plus"></i> Zoom
                                </button>
                            </div>
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

</div>
        @endsection

@push('after-scripts')
    @include('frontend.business_goals.partials._modal-zoom-js')
@endpush
