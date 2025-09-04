@extends('frontend.layouts.app')

@section('title', 'Create Workflow')

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">

        <div class="row justify-content-center align-items-center mb-3">
        <div class="col col-sm-10 align-self-center">
            <div class="card">
                <div class="card-header">
                    <strong>
                       @yield('title')
                    </strong>
                </div>

                <div class="card-body">
                    <form action="{{ route('frontend.work_flows.workflow.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title" class="col-4 col-form-label">Title</label>
                                <input id="title" name="title" type="text" class="form-control" required="required">

                        </div>
                        <div class="form-group">
                            <label for="type" class="col-4 col-form-label">Type</label>

                                <select id="type" name="type" class="form-control select2" required="required">
                                    <option value="rabbit">SLA</option>
                                    <option value="duck">Memo Approval</option>
                                    <option value="fish">Payment Request Approval</option>
                                </select>
                        </div>
                        <div class="form-group">
                            <label for="approver" class="col-4 col-form-label">Approver</label>

                                <select id="approver" name="approver" class="form-control select2" required="required">
                                    @foreach($approvers as $approver)
                                    <option value="{{ $approver->staff_ara_id }}">{{ $approver->surname }} {{ $approver->other_names }}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="form-group">
                            <label for="file" class="col-4 col-form-label">File</label>

                                <input id="file" name="file" type="file" accept="application/pdf" class="form-control" required="required">
                        </div>
                        <div class="form-group float-right">
                            <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        </div>
    </section>
@endsection
@push('after-scripts')
    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush
