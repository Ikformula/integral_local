@extends('frontend.layouts.app')

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('title', 'ECS Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-sm-12 col-md-4">
                <div class="card mb-2 bg-gradient-dark" style="border-radius: 15px;">
                    <img class="card-img-top rounded d-md-none" src="https://res.cloudinary.com/dr4hntqwu/image/upload/v1754648389/global-business-9062781_640_xjo4fp.jpg" alt="Bg Welcome Image">
                    <img class="card-img-top rounded d-none d-md-block" src="https://res.cloudinary.com/dr4hntqwu/image/upload/v1754648389/global-business-9062781_640_xjo4fp.jpg" alt="Bg Welcome Image">
                    <div class="card-img-overlay d-flex flex-column justify-content-end">
                        <h5 class="card-title text-primary text-white">Welcome to your ECS Dashboard,</h5>
                        <p class="card-text text-white pb-2 pt-1">{{ $logged_in_user->name }}</p>
                        {{--                    <a href="#" class="text-white">Last update 2 mins ago</a>--}}
                    </div>
                </div>

                @include('frontend.ecs_reconciliations._create-form')
            </div><!--col-->

            <div class="col">

                @php
                $sales_colours = ['teal', 'primary'];
                @endphp
                <div class="row">
                    @foreach($stats_sales as $key)
                        <div class="col">
                        @include('frontend.components.dashboard_stat_widget-small-box', ['title' => ucfirst(str_replace('_', ' ', $key)), 'slot' => number_format($sales[$key]), 'colour' => $sales_colours[$loop->index]])
                        </div>
                    @endforeach
                </div>
{{--                <div class="row">--}}
{{--                    @foreach($stats_numbers as $key)--}}
{{--                        <div class="col">--}}
{{--                        @include('frontend.components.dashboard_stat_widget', ['title' => ucfirst(str_replace('_', ' ', $key)), 'slot' => number_format($numbers[$key])])--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                </div>--}}

                <form action="" method="get">
                    <div class="card">
                        <div class="card-body pb-0">

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <select class="form-control select2" name="client_id">
                                            @if(!isset($params['client_id']))
                                                <option selected disabled>-- Select One --</option>
                                            @endif
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" {{ isset($params['client_id']) && $params['client_id'] == $client->id ? 'selected' : ''}}>{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                        <label>Client</label>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <select class="form-control select2" name="agent_user_id">
                                            @if(!isset($params['agent_user_id']))
                                                <option selected disabled>-- Select One --</option>
                                            @endif
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}" {{ isset($params['agent_user_id']) && $params['agent_user_id'] == $agent->id ? 'selected' : ''}}>{{ $agent->full_name }}</option>
                                            @endforeach
                                        </select>
                                        <label>Agent</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <input type="date" name="from_date" class="form-control" required value="{{ isset($params['from_date']) ? $params['from_date'] : '' }}">
                                        <label>From Date</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input type="date" name="to_date" class="form-control" required value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}">
                                        <label>To Date</label>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn bg-maroon btn-block">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Reconciliations</h3>
                                <div class="card-tools">
                                    <a href="{{ route('frontend.ecs_reconciliations.index') }}" class="btn btn-primary">View All Reconciliations</a>

                                </div>
                            </div>
                            <div class="card-body p-0">
                                @include('frontend.ecs_reconciliations._table', ['items' => $reconciliations])
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header bg-warning">
                                <h3 class="card-title">Links</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('frontend.ecs_clients.index') }}" class="list-group-item list-group-item-action">
                                        <i class="fa fa-arrow-alt-circle-right"></i> Clients
                                    </a>
                                    <a href="{{ route('frontend.ecs_flight_transactions.index', ['filter' => 'view']) }}" class="list-group-item list-group-item-action"><i class="fa fa-arrow-alt-circle-right"></i> Requests</a>
                                    <a href="{{ route('frontend.ecs_client_account_summaries.index') }}" class="list-group-item list-group-item-action"><i class="fa fa-arrow-alt-circle-right"></i> Account Summaries</a>
                                    <a href="{{ route('frontend.ecs_flight_transactions.index', ['filter' => 'refunds']) }}" class="list-group-item list-group-item-action"><i class="fa fa-arrow-alt-circle-right"></i> Refunds</a>
                                    <a href="{{ route('frontend.ecs_reconciliations.index') }}" class="list-group-item list-group-item-action"><i class="fa fa-arrow-alt-circle-right"></i> Reconciliations</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div><!--row-->




    </div>
@endsection

@push('after-scripts')
    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush
