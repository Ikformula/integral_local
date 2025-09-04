@extends('frontend.layouts.app')

@section('title', 'Vacancies')

@push('after-styles')
    @include('includes.partials._datatables-css')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card arik-card">
                        <div class="card-header">
                            <h3 class="card-title">Vacancies</h3>
                            <button type="button" class="btn bg-navy float-right" data-toggle="modal" data-target="#modal-lg">Add New Vacancy Notice</button>
                        </div>
                        <div class="card-body">
                        <table id="vacancies-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Position</th>
                                <th>Eligible Grade</th>
                                <th>Proposed Grade</th>
                                <th>Date Advertised</th>
                                <th>Date of Closing</th>
                                <th>Mode of Sourcing</th>
                                <th>Department</th>
                                <th>Recruiter</th>
                                <th>Location</th>
                                <th>Applications</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($vacancies as $vacancy)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $vacancy->position }}</td>
                                    <td>{{ $vacancy->eligible_grade }}</td>
                                    <td>{{ $vacancy->proposed_grade }}</td>
                                    <td>{{ $vacancy->date_advertised->toDateString() }}</td>
                                    <td>{{ $vacancy->date_of_closing->toDateString() }}</td>
                                    <td>{{ $vacancy->mode_of_sourcing }}</td>
                                    <td>{{ $vacancy->department }}</td>
                                    <td>{{ $vacancy->recruiter }}</td>
                                    <td>{{ $vacancy->location }}</td>
                                    <td>
                                        @php
                                            $applications_count[$vacancy->id] = $vacancy->applicationsCount();
                                        @endphp

                                        <a href="{{ route('frontend.vacancies.backend.vacancy.applications', $vacancy->id) }}" class="btn btn-sm btn-info @if(!isset($applications_count[$vacancy->id]) || $applications_count[$vacancy->id] == 0) disabled @endif">Applications @if(isset($applications_count[$vacancy->id]) && $applications_count[$vacancy->id] != 0)<span class="badge bg-warning">{{ $applications_count[$vacancy->id] }}</span>@endif</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('frontend.job_applications.show.vacancy', $vacancy->id) }}" class="btn btn-sm btn-primary">View</a>
                                        <a href="{{ route('frontend.vacancies.backend.edit', $vacancy->id) }}" class="btn btn-sm btn-warning">Edit</a>


                                        <form action="{{ route('frontend.vacancies.backend.destroy', $vacancy->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="9">No data available for now</td>
                                    </tr>
                            @endforelse
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Vacancy</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('frontend.vacancies.backend.index') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Position</label
                            ><input type="text" name="position" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Eligible Grade</label
                            ><input type="text" name="eligible_grade" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Proposed Grade</label
                            ><input type="text" name="proposed_grade" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Date Advertised</label
                            ><input type="date" name="date_advertised" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Date Of Closing</label
                            ><input type="date" name="date_of_closing" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Mode Of Sourcing</label>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" value="internal" type="radio" id="internal" name="mode_of_sourcing" checked="checked">
                                <label for="internal" class="custom-control-label">Internal</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" disabled type="radio" value="external" id="external" name="mode_of_sourcing">
                                <label for="external" class="custom-control-label">External</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Department</label
                            ><select name="department" class="form-control">
                                @include('includes.partials._departments-option-list')
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Recruiter</label><input type="text" name="recruiter" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Location</label><input type="text" name="location" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Job Description</label><textarea name="job_description" id="job_description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="input-group-text" for="job_description_doc">Job Description File</label>
                            <input type="file" class="form-control" name="job_description_doc" id="job_description_doc" placeholder=""
                                   aria-describedby="job_description_doc_id">
                            <small id="job_description_doc_id" class="form-text text-muted">docx, pdf...</small>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection


@push('after-scripts')
    @include('includes.partials._datatables-js')
    <script src="{{ asset('adminlte3.2/plugins/summernote/summernote-bs4.js') }}"></script>

    <script>
        $(".table").DataTable({
            "responsive": false, "lengthChange": false, "autoWidth": true, paging: false, scrollY: 465,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('.table_wrapper .col-md-6:eq(0)');
    </script>


    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#job_description'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
