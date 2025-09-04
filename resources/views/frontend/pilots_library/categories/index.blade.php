@extends('frontend.layouts.app')

@section('title', 'Staff Attendance Records' )

@push('after-styles')
    @include('includes.partials._datatables-css')
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Categories</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="categories-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Parent Category ID</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->parent_category_id }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@push('after-scripts')
    @include('includes.partials._datatables-js')

    <script>
        $("#categories-table").DataTable({
            "responsive": false, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#categories-table_wrapper .col-md-6:eq(0)');
    </script>
@endpush
