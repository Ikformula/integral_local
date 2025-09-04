@extends('frontend.layouts.app')

@section('title', 'Form Builder' )
@push('after-styles')
{{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">--}}
{{--    <script src="https://cdn.form.io/js/formio.embed.js"></script>--}}
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-6">

                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card arik-card">
                        <div class="card-header">
                            Fields
                        </div>
                        <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Field Name</th>
                            <th>Field type</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="4" class="bg-danger"><i class="fa fa-plus"></i> Add new field</td>
                        </tr>
                        </tbody>
                    </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit field</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('after-scripts')
@endpush
