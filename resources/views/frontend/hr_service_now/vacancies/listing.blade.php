@extends('frontend.layouts.app')

@section('title', 'Vacancies')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card arik-card">
                        <div class="card-header">
                            <h3 class="card-title">Vacancies</h3>
                        </div>
                        <div class="card-body p-0">
                            <table id="vacancies-table" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Position</th>
                                    <th>Proposed Grade</th>
                                    <th>Eligible Grade</th>
                                    <th>Date Advertised</th>
                                    <th>Date of Closing</th>
                                    <th>Mode of Sourcing</th>
                                    <th>Department</th>
                                    <th>Recruiter</th>
                                    <th>Location</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($vacancies as $vacancy)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $vacancy->position }}</td>
                                        <td>{{ $vacancy->proposed_grade }}</td>
                                        <td>{{ $vacancy->eligible_grade }}</td>
                                        <td>{{ $vacancy->date_advertised->toDateString() }}</td>
                                        <td>{{ $vacancy->date_of_closing->toDateString() }}</td>
                                        <td>{{ $vacancy->mode_of_sourcing }}</td>
                                        <td>{{ $vacancy->department }}</td>
                                        <td>{{ $vacancy->recruiter }}</td>
                                        <td>{{ $vacancy->location }}</td>
                                        <td>
                                            <a href="{{ route('frontend.job_applications.show.vacancy', $vacancy) }}" class="btn btn-sm btn-primary">View/Apply</a>
                                            @if(isset($vacancy->job_description_doc_path))
                                            <a href="{{ asset('job_vacancies/job_description_docs/'.$vacancy->job_description_doc_path) }}" class="btn btn-sm btn-info" target="_blank">J.D.</a>
                                            @endif
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

@endsection
