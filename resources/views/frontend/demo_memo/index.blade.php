@extends('frontend.layouts.app')

@section('title', 'Workflow Demo')

@section('content')
    <section class="content">
        <div class="container-fluid">

        <div class="row justify-content-center align-items-center mb-3">
        <div class="col col-sm-10 align-self-center">
            <div class="card">
                <div class="card-header">
                    <strong>
                       Workflows
                    </strong>
                </div>

                <div class="card-body">
                    <table class="table table-stripped">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>View</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($workflows as $workflow)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $workflow->title }}</td>
                                <td>{{ isset($workflow->approved_at) ? 'Approved at: '.$workflow->approved_at->toDateDayTimeString() : (isset($workflow->rejected_at) ? 'Rejected at: '.$workflow->rejected_at->toDateDayTimeString() : 'Pending') }}</td>
                                <td><a href="{{ route('frontend.work_flows.workflow.show', $workflow) }}" class="btn btn-primary">View</a></td>
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
@endsection
