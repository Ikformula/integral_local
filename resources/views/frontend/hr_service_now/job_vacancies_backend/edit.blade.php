@extends('frontend.layouts.app')

@section('title', 'Update Vacancy' )

@push('after-styles')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card arik-card">
                    <div class="card-header">
                        <h4 class="card-title">@yield('title')</h4>
                    </div>
                     <div class="card-body">
                         <form action="{{ route('frontend.vacancies.backend.update', $vacancy->id) }}" method="POST" enctype="multipart/form-data" enctype="multipart/form-data">
                             @csrf
                             @method('PATCH')

                             <div class="form-group">
                                 <label>Position</label>
                                 <input type="text" name="position" class="form-control" value="{{ old('position', $vacancy->position) }}" />
                             </div>

                             <div class="form-group">
                                 <label>Eligible grade</label>
                                 <input type="text" name="eligible_grade" class="form-control" value="{{ old('eligible_grade', $vacancy->eligible_grade) }}" />
                             </div>
                             <div class="form-group">
                                 <label>Proposed grade</label>
                                 <input type="text" name="proposed_grade" class="form-control" value="{{ old('proposed_grade', $vacancy->proposed_grade) }}" />
                             </div>

                             <div class="form-group">
                                 <label>Date Advertised</label>
                                 <input type="date" name="date_advertised" class="form-control" value="{{ old('date_advertised', date_format($vacancy->date_advertised, 'Y-m-d')) }}" />
                             </div>

                             <div class="form-group">
                                 <label>Date Of Closing</label>
                                 <input type="date" name="date_of_closing" class="form-control" value="{{ old('date_of_closing', date_format($vacancy->date_of_closing, 'Y-m-d')) }}" />
                             </div>

                             <div class="form-group">
                                 <label>Mode Of Sourcing</label>
                                 <div class="custom-control custom-radio">
                                     <input class="custom-control-input" value="internal" type="radio" id="internal" name="mode_of_sourcing"
                                         {{ old('mode_of_sourcing', $vacancy->mode_of_sourcing) == 'internal' ? 'checked' : '' }}>
                                     <label for="internal" class="custom-control-label">Internal</label>
                                 </div>
                                 <div class="custom-control custom-radio">
                                     <input class="custom-control-input" type="radio" value="external" id="external" name="mode_of_sourcing"
                                         {{ old('mode_of_sourcing', $vacancy->mode_of_sourcing) == 'external' ? 'checked' : '' }}>
                                     <label for="external" class="custom-control-label">External</label>
                                 </div>
                             </div>

                             <div class="form-group">
                                 <label>Department</label>
                                 <select name="department" class="form-control">
                                     <option selected>{{ $vacancy->department }}</option>
                                     @include('includes.partials._departments-option-list', ['selected' => old('department', $vacancy->department)])
                                 </select>
                             </div>

                             <div class="form-group">
                                 <label>Recruiter</label>
                                 <input type="text" name="recruiter" class="form-control" value="{{ old('recruiter', $vacancy->recruiter) }}" />
                             </div>
                             <div class="form-group">
                                 <label>Location</label>
                                 <input type="text" name="location" class="form-control" value="{{ old('location', $vacancy->location) }}" />
                             </div>

                             <div class="form-group">
                                 <label>Job Description</label>
                                 <textarea name="job_description" id="job_description" class="form-control">{{ old('job_description', $vacancy->job_description) }}</textarea>
                             </div>

                             <div class="form-group">
                                 <label class="input-group-text" for="job_description_doc">Job Description File</label>
                                 <input type="file" class="form-control" name="job_description_doc" id="job_description_doc" placeholder=""
                                        aria-describedby="job_description_doc_id">
                                 <small id="job_description_doc_id" class="form-text text-muted">docx, pdf...</small>
                                 @if($vacancy->job_description_doc_path)
                                     <p>Current file: <a href="{{ asset('job_vacancies/job_description_docs/'.$vacancy->job_description_doc_path) }}" target="_blank">Download</a></p>
                                 @endif
                             </div>

                             <button type="submit" class="btn btn-primary float-right">Update</button>
                         </form>

                     </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@push('after-scripts')

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
