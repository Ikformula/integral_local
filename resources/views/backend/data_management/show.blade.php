@extends('backend.layouts.app')


@push('after-styles')
    @include('includes.partials._datatables-css')
@endpush


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">{{ $model }}</span>

                        <a href="{{ route('admin.database_admin.create', $model) }}" class="btn btn-primary float-right">Add</a>
                    </div>
                    <div class="card-body">
    <table class="table">
        <thead>
        <tr>
            @foreach($columns as $column)
                <th>{{ $column }}</th>
            @endforeach
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($records as $record)
            <tr>
                @foreach($columns as $column)
                    <td>{{ $record->$column }}</td>
                @endforeach
                <td>
                    <div class="d-flex">
                        {!! html()->a(route('admin.database_admin.edit', [$model, $record->id]), 'Edit')->class('btn btn-warning btn-sm mr-1') !!}
                        {!! html()->form('DELETE', route('admin.database_admin.destroy', [$model, $record->id]))->open() !!}
                        {!! html()->submit('Delete')->class('btn btn-danger btn-sm')->attributes(['onclick' => 'return confirm("Are you sure?")']) !!}
                    </div>

                    {!! html()->form()->close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $records->links() }}

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@push('after-scripts')
    @include('includes.partials._datatables-js')

    <script>
        $(".table").DataTable({
            "responsive": false, "lengthChange": false, "autoWidth": true, paging: false, scrollY: 465, scrollX: true,
            "buttons": ["colvis"]
        }).buttons().container().appendTo('.table_wrapper .col-md-6:eq(0)');
    </script>
@endpush
