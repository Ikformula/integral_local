
<div class="card text-bg-theme">

    <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">CBT Exam Candidates</h4>
        <div>
            <a href="{{ route('cbt_exam_candidates.cbt_exam_candidate.create') }}" class="btn btn-secondary" title="Create New CBT Exam Candidate">
                <span class="fa-solid fa-plus" aria-hidden="true"></span>
            </a>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importCandidatesModalId">Import from CSV</button>

            <!-- Modal -->
            <div class="modal fade" id="importCandidatesModalId" tabindex="-1" role="dialog" aria-labelledby="importCandidatesModalTitleId"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="importCandidatesModalTitleId"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">Select Candidates CSV File</label>
                                    <input
                                        type="file"
                                        class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                                    <small id="helpId" class="form-text text-muted">See sample CSV <a href="#">here</a></small>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Upload</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(count($cbtExamCandidates) == 0)
        <div class="card-body text-center">
            <h4>No CBT Exam Candidates Available.</h4>
        </div>
    @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                    <tr>
                        <th>Email</th>
                        <th>Staff Ara</th>
                        <th>CBT Exam</th>
                        <th>Surname</th>
                        <th>First Name</th>
                        <th>Other Names</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>State</th>

                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($cbtExamCandidates as $cbtExamCandidate)
                        <tr>
                            <td class="align-middle">{{ $cbtExamCandidate->email }}</td>
                            <td class="align-middle">{{ optional($cbtExamCandidate->staffAra)->id }}</td>
                            <td class="align-middle">{{ optional($cbtExamCandidate->CbtExam)->title }}</td>
                            <td class="align-middle">{{ $cbtExamCandidate->surname }}</td>
                            <td class="align-middle">{{ $cbtExamCandidate->first_name }}</td>
                            <td class="align-middle">{{ $cbtExamCandidate->other_names }}</td>
                            <td class="align-middle">{{ $cbtExamCandidate->age }}</td>
                            <td class="align-middle">{{ $cbtExamCandidate->gender }}</td>
                            <td class="align-middle">{{ $cbtExamCandidate->state }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('cbt_exam_candidates.cbt_exam_candidate.destroy', $cbtExamCandidate->id) !!}" accept-charset="UTF-8">
                                    <input name="_method" value="DELETE" type="hidden">
                                    {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('cbt_exam_candidates.cbt_exam_candidate.show', $cbtExamCandidate->id ) }}" class="btn btn-info" title="Show CBT Exam Candidate">
                                            <span class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('cbt_exam_candidates.cbt_exam_candidate.edit', $cbtExamCandidate->id ) }}" class="btn btn-primary" title="Edit CBT Exam Candidate">
                                            <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete CBT Exam Candidate" onclick="return confirm(&quot;Click Ok to delete CBT Exam Candidate.&quot;)">
                                            <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                                        </button>
                                    </div>

                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

           @if(!isset($no_pagination)) {!! $cbtExamCandidates->links('pagination') !!} @endif
        </div>

    @endif

</div>
