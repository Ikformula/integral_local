@extends('frontend.layouts.app')

@section('title', $vacancy->position.' - '.$vacancy->date_advertised->toDateString() )
@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

        <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    @endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card arik-card card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-vacancy-details-tab" data-toggle="pill" href="#custom-tabs-vacancy-details" role="tab" aria-controls="custom-tabs-vacancy-details" aria-selected="true">Vacancy Details</a>
                                </li>

                                @if(isset($vacancy->job_description_doc_path))
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-job-description-tab" href="{{ asset('job_vacancies/job_description_docs/'.$vacancy->job_description_doc_path) }}" role="tab" target="_blank" aria-selected="false">Job description Doc.</a>
                                </li>
                                @endif

                                @if($logged_in_user->staff_member)
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-apply-to-vacancy-tab" data-toggle="pill" href="#custom-tabs-apply-to-vacancy" role="tab" aria-controls="custom-tabs-apply-to-vacancy" aria-selected="false">Apply To Vacancy</a>
                                </li>
                                @endif

                                @if($logged_in_user->isAdmin() || $logged_in_user->can('manage vacancy postings'))
                                    @php
                                        $applications_count[$vacancy->id] = $vacancy->applicationsCount();
                                    @endphp
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-email-sending-tab" data-toggle="pill" href="#custom-tabs-email-sending" role="tab" aria-controls="custom-tabs-email-sending" aria-selected="false">Email Sending</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link @if(!isset($applications_count[$vacancy->id]) || $applications_count[$vacancy->id] == 0) disabled @endif" id="custom-tabs-one-settings-tab" href="{{ route('frontend.vacancies.backend.vacancy.applications', $vacancy->id) }}" role="tab" aria-selected="false">Applications @if(isset($applications_count[$vacancy->id]) && $applications_count[$vacancy->id] != 0)<span class="badge bg-warning">{{ $applications_count[$vacancy->id] }}</span>@endif</a>
                                </li>
                                    @endif
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active p-0" id="custom-tabs-vacancy-details" role="tabpanel" aria-labelledby="custom-tabs-vacancy-details-tab">
                                    <table class="table table-hover table-sm">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <strong>Position</strong></td><td>
                                                <p>
                                                    {{ $vacancy->position }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Proposed Grade</strong></td><td>
                                                <p>
                                                    {{ $vacancy->proposed_grade }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Eligible Grade</strong></td><td>
                                                <p>
                                                    {{ $vacancy->eligible_grade }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Date Advertised</strong></td><td>
                                                <p>
                                                    {{ $vacancy->date_advertised->toDateString() }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Date of Closing</strong></td><td>
                                                <p>
                                                    {{ $vacancy->date_of_closing->toDateString() }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Mode of Sourcing</strong></td><td>
                                                <p>
                                                    {{ $vacancy->mode_of_sourcing }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Department</strong></td><td>
                                                <p>
                                                    {{ $vacancy->department }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Recruiter</strong></td><td>
                                                <p>
                                                    {{ $vacancy->recruiter }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Location</strong></td><td>
                                                <p>
                                                    {{ $vacancy->location }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Job Description</strong></td><td>
                                                <p>
                                                    {!! $vacancy->job_description !!}
                                                </p>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                @if($logged_in_user->staff_member)
                                <div class="tab-pane fade" id="custom-tabs-apply-to-vacancy" role="tabpanel" aria-labelledby="custom-tabs-apply-to-vacancy-tab">
                                    <h4 class="modal-title">Apply for the Position of {{ $vacancy->position }} ({{ $vacancy->mode_of_sourcing }})</h4>

                                    <form action="{{ route('frontend.job_applications.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="vacancy_id" value="{{ $vacancy->id }}">
                                        <div class="form-group">
                                            <label>Staff ARA ID</label>
                                            <input type="text" class="form-control" name="staff_ara_id" @if(!is_null($staff_member)) value="{{ $staff_member->staff_ara_id }}" @endif maxlength="8" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="academic_level">ACADEMIC LEVEL</label>
                                            <div>
                                                <select id="academic_level" name="academic_level" class="custom-select" required="required">
                                                    <option selected disabled>Choose one</option>
                                                    <option value="Non Graduate (FSLC, O'LEVEL, OND, NCE)">Non Graduate (FSLC, O'LEVEL, OND, NCE)</option>
                                                    <option value="Graduate (HND, BS.c, B.Eng)">Graduate (HND, BS.c, B.Eng)</option>
                                                    <option value="CPL Certified">CPL Certified</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="highest_qualification">HIGHEST QUALIFICATION</label>
                                            <div>
                                                <select id="highest_qualification" name="highest_qualification" required="required" class="custom-select">
                                                    <option selected disabled>Choose one</option>
                                                    <option>MASTERS (MBA, MSc, MA)</option>
                                                    <option>BACHELORS DEGREE (BA, BSc, B.Eng)</option>
                                                    <option>HIGHER NATIONAL DIPLOMA</option>
                                                    <option>NIGERIAN CERTIFICATE OF EDUCATION</option>
                                                    <option>ORDINARY NATIONAL DIPLOMA</option>
                                                    <option>O'LEVEL/SSCE/GCE</option>
                                                    <option>FIRST SCHOOL LEAVING CERTIFICATE</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="professional_training_and_certifications">PROFESSIONAL TRAINING AND CERTIFICATIONS</label>
                                            <textarea id="professional_training_and_certifications" name="professional_training_and_certifications" cols="40" rows="5" class="form-control"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="relevant_experience">RELEVANT EXPERIENCE</label>
                                            <textarea id="relevant_experience" name="relevant_experience" cols="40" rows="5" class="form-control" aria-describedby="relevant_experienceHelpBlock" required="required"></textarea>
                                            <span id="relevant_experienceHelpBlock" class="form-text text-muted">kindly indicate role &amp; years of experience (e.g Flight dispatch at Arik Air; Feb. 2017-Mar. 2023)</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="supervisory_experience">SUPERVISORY EXPERIENCE</label>
                                            <textarea id="supervisory_experience" name="supervisory_experience" cols="40" rows="2" class="form-control"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="skills">SKILLS</label>
                                            <textarea id="skills" name="skills" cols="40" rows="2" class="form-control" aria-describedby="skillsHelpBlock"></textarea>
                                            <span id="skillsHelpBlock" class="form-text text-muted">seperate each with a comma</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="line_manager">LINE MANAGER/ SUPERVISOR</label>
                                            <div>
                                                <select id="line_manager" name="line_manager" class="custom-select select2" required="required">
                                                    @foreach($possible_line_managers as $staff_member)
                                                        <option value="{{ $staff_member->staff_ara_id }}">{{ $staff_member->name }}, {{ $staff_member->staff_ara_id }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="cv_file">CURRICULUM VITAE</label>
                                            <input id="cv_file" name="cv_file" type="file" class="form-control">
                                        </div>
                                        <button type="submit" class="btn btn-primary float-right">Submit Application</button>
                                    </form>
                                </div>
                                @endif

                                @if($logged_in_user->isAdmin() || $logged_in_user->can('manage vacancy postings'))
                                <div class="tab-pane fade" id="custom-tabs-email-sending" role="tabpanel" aria-labelledby="custom-tabs-email-sending-tab">
                                    <form action="{{ route('frontend.vacancies.backend.email.processing', $vacancy->id) }}" method="POST" id="email_form">
                                        @csrf
                                        <div class="row">
                                            <div class="col">
                                                <h4>Dear Colleagues,</h4>
                                                <p>The following position is for placement.</p>
                                                <table style="direction: ltr; text-align: left; text-indent: 0px; width: 593.5pt; box-sizing: border-box; border-collapse: collapse; border-spacing: 0px; transform: scale(1); transform-origin: left top 0px;">
                                                    <tbody>
                                                    <tr>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid solid; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 33.35pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>S/N</strong></div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90.7pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>POSITION</strong></div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>PROPOSED GRADE</strong></div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>ELIGIBLE GRADE</strong></div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; vertical-align: top; width: 103.5pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>DATE ADVERTISED</strong></div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 85.5pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>DATE OF CLOSING</strong></div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 72pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>MODE OF SOURCING</strong></div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>DEPARTMENT</strong></div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: solid solid solid none; border-color: initial; vertical-align: top; width: 67.5pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)"><strong>RECRUITER</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-width: 1pt; border-style: none solid solid; border-color: initial; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 33.35pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">1</div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90.7pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">{{ $vacancy->position}}</div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">{{ $vacancy->proposed_grade}}</div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 50.95pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">{{ $vacancy->eligible_grade}}</div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; vertical-align: top; width: 103.5pt; height: 20.9pt;">
                                                            <div class="x_elementToProof" style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">{{ $vacancy->date_advertised->toDateString()}}</div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 85.5pt; height: 20.9pt;">
                                                            <div class="x_elementToProof" style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">{{ $vacancy->date_of_closing->toDateString()}}</div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 72pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">{{ $vacancy->mode_of_sourcing}}</div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; padding-right: 5.4pt; padding-left: 5.4pt; vertical-align: top; width: 90pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">{{ $vacancy->department}}</div>
                                                        </td>
                                                        <td style="direction: ltr; text-align: left; text-indent: 0px; border-right: 1pt solid; border-bottom: 1pt solid; vertical-align: top; width: 67.5pt; height: 20.9pt;">
                                                            <div style="direction: ltr; text-align: left; text-indent: 0px; margin: 0px; font-family: Aptos, Aptos_EmbeddedFont, Aptos_MSFontService, Calibri, Helvetica, sans-serif; font-size: 12pt; color: #000000 !important;" data-ogsc="rgb(0, 0, 0)">{{ $vacancy->recruiter}}</div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                                    <textarea name="email_body" class="form-control" id="email_body" required>
                                                        {!! $email_preview !!}
                                                    </textarea>
                                        </div>
                                        <button type="submit" class="btn btn-info btn-block">Save Draft</button>

                                        <div class="form-group mt-3">
                                            <label>Email Address(es) to Send to</label>
                                            <textarea class="form-control" name="emails"></textarea>
                                            <small class="text-muted">If more than one email address, separate each using commas.</small>
                                        </div>
                                        <div class="form-group">
                                            <label>Cc</label>
                                            <textarea class="form-control" name="cc_emails"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Bcc</label>
                                            <textarea class="form-control" name="bcc_emails"></textarea>
                                        </div>

                                        <input type="hidden" name="send_email_input" value="0" id="send_email_input">

                                        <button type="button" class="btn btn-outline-info btn-block" onclick="processSendEmail()">Save and Send Email</button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('after-scripts')
    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('#line_manager').select2({
            theme: 'bootstrap4'
        });
    </script>


    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#email_body'))
            .catch(error => {
                console.error(error);
            });

        function processSendEmail(){
            document.getElementById('send_email_input').value = '1';
            document.getElementById('email_form').submit();
        }
    </script>
@endpush
