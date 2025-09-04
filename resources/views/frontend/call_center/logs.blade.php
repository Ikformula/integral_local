@extends('frontend.layouts.app')

@section('title', 'Logs' )

@section('content')

    <section class="content">
        <div class="container-fluid">

                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Call Logs</div>

                                <div class="card-body">
                                    @include('frontend.call_center._logs_datatable')
                                </div>
                            </div>
                        </div>
                    </div>
                                        </div><!--/. container-fluid -->
    </section>
@endsection

