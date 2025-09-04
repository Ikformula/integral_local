{{-- filepath: c:\laragon\www\arik_web_portals\resources\views\frontend\l_and_d_training_courses\index.blade.php --}}
@extends('frontend.layouts.app')

@push('after-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

    @section('title', 'Training Courses')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('frontend.l_and_d_training_courses.create') }}" class="btn btn-primary">Add New Training Course</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Training Courses</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Held From</th>
                                <th>Ended At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $key => $course)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->held_from }}</td>
                                    <td>{{ $course->ended_at }}</td>
<td>
    <a href="{{ route('frontend.l_and_d_training_courses.show', $course->id) }}" class="btn btn-sm btn-info">Details</a>
    <a href="{{ route('frontend.l_and_d_training_courses.edit', $course->id) }}" class="btn btn-sm btn-primary">Edit</a>
    <form action="{{ route('frontend.l_and_d_training_courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
    </form>
</td>
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
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/dataTables.searchBuilder.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/searchBuilder.dataTables.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            var table = new DataTable('.table', {
                "paging": false,
                scrollY: 465,
                layout: {
                    top: {
                        searchBuilder: {
                            // columns: [6],
                            @if(isset($_GET['days_left']))
                            preDefined: {
                                {{--criteria: [--}}
                                {{--    {--}}
                                {{--        data: 'Days Left to End',--}}
                                {{--        condition: '=',--}}
                                {{--        value: [{{ $_GET['days_left'] }}]--}}
                                {{--    }--}}
                                {{--]--}}
                            }
                            @endif
                        }
                    },
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                }
            });
        });

    </script>
@endpush
