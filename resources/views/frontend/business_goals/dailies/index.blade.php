@extends('frontend.layouts.app')

@section('title', isset($business_area) ? $business_area->name : 'Business Areas')

@push('after-styles')
    <style>
        .scrollable-div {
            height: 70vh; /* Change this value to set the desired fixed height */
            overflow: auto; /* This will enable vertical scrolling if content exceeds the height */
        }

        thead {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 9;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
            -webkit-box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
            -moz-box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
        }

    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card my-2">
                    @if(isset($business_area_id))
                    <div class="card-header">
                        <h4 class="card-title">Filter by Business area and Date</h4>
                    </div>
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
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group mb-3">
                                        <input type="date" name="for_date" min="{{ substr($weeks->last()->from_date, 0, 10) }}" max="{{ now()->toDateString() }}" id="for_date" @if(isset($_GET['for_date'])) value="{{ $_GET['for_date'] }}" @endif class="form-control" placeholder=""
                                               aria-describedby="for_date_helpId">
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
                    @else
                    <div class="card-header bg-navy">
                        Select a Business Area
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($accessible_business_areas as $biz_area)
                                <div class="col-sm-6">
                                    <a href="{{ route('frontend.business_goals.single.quadrant') }}?business_area_id={{ $biz_area->id }}" class="btn btn-outline-secondary btn-block btn-lg mb-3">
                                        {{ $biz_area->name }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if(isset($business_area_id))
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body scrollable-div">
                        @include('frontend.business_goals.dailies._'.$business_area_id)
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('after-scripts')
    <script src="{{ asset('js/html-table-xlsx.js') }}"></script>
@endpush
