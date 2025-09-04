@extends('frontend.layouts.app')

@section('title', 'Applications for '.$vacancy->position.' at '.$vacancy->department)

@push('after-styles')
{{--    @include('includes.partials._datatables-css')--}}
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card arik-card">
                        <div class="card-header">
                            <h3 class="card-title">Applications</h3>
                            <button type="button" class="btn btn-primary float-right" onclick="exportTableToXLSX('job_applications-table')">Export to Excel</button>
                        </div>

                        <div class="card-body table-responsive">
                        <table id="job_applications-table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Staff</th>
                                <th>ARA ID</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Current Position</th>
                                <th>Academic Level</th>
                                <th>Highest Qualification</th>
                                <th>Professional training and certifications</th>
                                <th>Relevant experience</th>
                                <th>Supervisory experience</th>
                                <th>Skills</th>
                                <th>Line Manager Name</th>
                                <th>Line Manager Email</th>
                                <th>CV</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($job_applications as $job_application)
                                @if($job_application->staffMember)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $job_application->staffMember->full_name }}</td>
                                    <td>{{ $job_application->staff_ara_id }}</td>
                                    <td>{{ $job_application->staffMember->email }}</td>
                                    <td>{{ $job_application->staffMember->department_name }}</td>
                                    <td>{{ $job_application->staffMember->job_title }}</td>
                                    <td>{{ $job_application->academic_level }}</td>
                                    <td>{{ $job_application->highest_qualification }}</td>
                                    <td>{{ $job_application->professional_training_and_certifications }}</td>
                                    <td>{{ $job_application->relevant_experience }}</td>
                                    <td>{{ $job_application->supervisory_experience }}</td>
                                    <td>{{ $job_application->skils }}</td>
                                    <td>{{ $job_application->lineManager->full_name }}, ARA{{ $job_application->line_manager }}</td>
                                    <td>{{ $job_application->lineManager->email }} </td>
                                    <td><a href="{{ asset('job_vacancies/cv_files/'.$job_application->cv_file) }}" class="btn btn-sm btn-primary" target="_blank">View CV</a></td>

                                </tr>
                                @endif

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


@push('after-scripts')
{{--    @include('includes.partials._datatables-js')--}}
    <script src="{{ asset('js/html-table-xlsx.js') }}"></script>
{{--    <script>--}}
{{--        $(".table").DataTable({--}}
{{--            "responsive": false, "lengthChange": false, "autoWidth": false, paging: false, scrollY: 465--}}
{{--        });--}}
{{--    </script>--}}

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
