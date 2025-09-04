<!-- resources/views/frontend/staff_travel_beneficiaries/index.blade.php -->
@extends('frontend.layouts.app')

@push('after-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('title', 'Staff Travel Beneficiary List')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('frontend.staff_travel_beneficiaries.create') }}?{{ isset($mode) && $mode == 'personal' ? 'personal=1' : '' }}" class="btn btn-primary">Add New Staff Travel Beneficiary</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Staff Travel Beneficiary List</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-sm table-striped table-striped-columns w-100 text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Staff Member</th>
                                <th>Dept.</th>
                                <th>Firstname</th>
                                <th>Surname</th>
                                <th>Other Name</th>
                                <th>Date of Birth</th>
                                <th>Gender</th>
                                <th>Relationship</th>
                                <th>Photo</th>
{{--                                <th>Posted By</th>--}}
                                <th>Status</th>
                                <th>Actioned By</th>
                                <th>Actioned On</th>
                                <th>Actioned Comment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
    @foreach($items as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->staff_member->name_and_ara }}</td>
            <td>{{ $item->staff_member->department_name }}</td>
            <td>{{ $item->firstname }}</td>
            <td>{{ $item->surname }}</td>
            <td>{{ $item->other_name }}</td>
            <td>{{ substr($item->dob, 0, 10) }}</td>
            <td>{{ $item->gender }}</td>
            <td>{{ $item->relationship }}</td>
            <td>
                <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->surname }} {{ $item->other_name }}'s Photo" class="img-thumbnail" style="max-width: 100px;">
            </td>
{{--            <td>{{ $item->posted_by }}</td>--}}
            <td>
    @if ($item->status === null && ($logged_in_user->can('manage staff travel portal') || $logged_in_user->hasRole('hr advisor')))
            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#approveModal-{{ $item->id }}">Approve</button>

            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#disapproveModal-{{ $item->id }}">Disapprove</button>

    @else
        <span class="badge badge-{{ $item->status ? ($item->status === 'approved' ? 'success' : 'danger') : 'warning' }}">
            {{ $item->status ? ucfirst($item->status) : 'Pending' }}
        </span>
    @endif
</td>
            <td>{{ $item->actionedBy ? $item->actionedBy->name : 'N/A' }}</td>
            <td>{{ $item->actioned_time ? $item->actioned_time->format('Y-m-d') : 'N/A' }}</td>
            <td>{{ $item->actioned_comment }}</td>
            <td>
                <a href="{{ route('frontend.staff_travel_beneficiaries.show', $item->id) }}?{{ isset($mode) && $mode == 'personal' ? 'personal=1' : '' }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('frontend.staff_travel_beneficiaries.edit', $item->id) }}?{{ isset($mode) && $mode == 'personal' ? 'personal=1' : '' }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('frontend.staff_travel_beneficiaries.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>

        @if ($item->status === null && ($logged_in_user->can('manage staff travel portal') || $logged_in_user->hasRole('hr advisor')))
        <div class="modal fade" id="approveModal-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="approveModalTitle-{{ $item->id }}"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="approveModalTitle-{{ $item->id }}">Approve Beneficiary</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <form action="{{ route('frontend.staff_travel_beneficiaries.approve', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <div class="form-group">
                                            <textarea name="actioned_comment" class="form-control form-control-sm mb-2" placeholder="Add comment (optional)"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-success btn-block">Approve</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
{{--                        <button type="button" class="btn btn-primary">Save</button>--}}
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="disapproveModal-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="disapproveModalTitle-{{ $item->id }}"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="disapproveModalTitle-{{ $item->id }}">Disapprove Beneficiary</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <form action="{{ route('frontend.staff_travel_beneficiaries.disapprove', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <div class="form-group">
                                            <textarea name="actioned_comment" class="form-control form-control-sm mb-2" placeholder="Add comment (optional)"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-danger btn-block">Disapprove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
{{--                        <button type="button" class="btn btn-primary">Save</button>--}}
                    </div>
                </div>
            </div>
        </div>


        @endif
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
                        searchBuilder: { }
                    },
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                }
            });
        });
    </script>
@endpush
