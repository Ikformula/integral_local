@extends('frontend.layouts.app')

@section('title', 'CRM Dashboard' )

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
            <iframe width="100%" height="450" src="https://lookerstudio.google.com/embed/reporting/83e0e8da-e297-4197-ac93-88989a23224e/page/TV4ED" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>


            @if(1 < 0)
            <div class="row">
                @foreach($stats as $stat)
                    <div class="col-md-6">
                        @component('frontend.components.dashboard_stat_widget', ['colour' => 'navy', 'icon' => $stat['icon'], 'title' => $stat['title']])
                            {{ $stat['value'] }}
                        @endcomponent
                    </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Most Recent Call Logs</div>

                        <div class="card-body">
            @include('frontend.call_center._logs_datatable')
                        </div>
                    </div>
                </div>
            </div>
                @endif
        </div>
    </section>

@endsection
