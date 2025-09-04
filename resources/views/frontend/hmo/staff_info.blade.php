@extends('frontend.layouts.app')

@section('title', 'HMO | Staff Data Update' )

@push('after-styles')

@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card arik-card">
                        <div class="card-header">Employee Data Update</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('frontend.hmo.update.staff_member', $staff_member->staff_ara_id) }}">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $staff_member->user->id ?? '' }}">
                                <h6 class="text-uppercase muted">Personal Data</h6>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="surname">Name</label>
                                            <input id="surname" value="{{ $staff_member->name }}" readonly type="text" class="form-control" required="required">
                                        </div>

                                        <div class="form-group">
                                            <label for="text">Email ID</label>
                                            <input id="text" readonly type="email" value="{{ $staff_member->email }}" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="date_of_joining">Date of Joining</label>
                                            <input id="date_of_joining" name="date_of_joining" value="{{ $staff_member->date_of_joining }}" type="date" class="form-control" required="required">
                                        </div>

                                        <div class="form-group">
                                            <label for="unit">Unit</label>
                                            <input id="unit" name="unit" value="{{ $staff_member->unit }}" type="text" required="required" class="form-control">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact_number">Contact Number (Official)</label>
                                            <input id="contact_number_official" name="contact_number[official]" placeholder="+234 818 449 6562" value="{{ $staff_member->contact_info('official') ?? '' }}" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="contact_number">Contact Number (Personal)</label>
                                            <input id="contact_number_personal" name="contact_number[personal]" placeholder="+234 818 449 6562" value="{{ $staff_member->contact_info('personal') ?? '' }}" type="text" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="contact_number">Contact Number (Emergency)</label>
                                            <input id="contact_number" name="contact_number[emergency]" placeholder="+234 818 449 1111" value="{{ $staff_member->contact_info('emergency') ?? '' }}" type="text" class="form-control">
                                        </div>


                                        <div class="form-group">
                                            <label>Marital Status</label>
                                            <div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input name="marital_status" id="marital_status_0" type="radio" class="custom-control-input" value="Married" required="required" @if($staff_member->marital_status == 'Married') checked @endif>
                                                    <label for="marital_status_0" class="custom-control-label">Married</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input name="marital_status" id="marital_status_1" type="radio" class="custom-control-input" value="Single" required="required" @if($staff_member->marital_status == 'Single') checked @endif>
                                                    <label for="marital_status_1" class="custom-control-label">Single</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input name="marital_status" id="marital_status_2" type="radio" class="custom-control-input" value="Widowed" required="required" @if($staff_member->marital_status == 'Widowed') checked @endif>
                                                    <label for="marital_status_2" class="custom-control-label">Widowed</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input name="marital_status" id="marital_status_3" type="radio" class="custom-control-input" value="Divorced" required="required" @if($staff_member->marital_status == 'Divorced') checked @endif>
                                                    <label for="marital_status_3" class="custom-control-label">Divorced</label>
                                                </div>
                                            </div>
                                        </div>
                                            </div>
                                        </div>

{{--                                        <div id="spouse_information">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="surname">Spouse Surname</label>--}}
{{--                                                <input id="surname" name="spouse_surname" type="text" class="form-control" required="required">--}}
{{--                                            </div>--}}

{{--                                            <div class="form-group">--}}
{{--                                                <label for="surname">Spouse first name</label>--}}
{{--                                                <input id="surname" name="spouse_first_name" type="text" class="form-control" required="required">--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="surname">Spouse other name</label>--}}
{{--                                                <input id="surname" name="spouse_other_name" type="text" class="form-control" required="required">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}


                                    <div class="d-grid">
                                        <button class="btn bg-navy btn-lg btn-block" id="submitButton" type="submit">
                                            Submit
                                        </button>
                                    </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card arik-card">
                        <div class="card-header">
                            <i class="ion ion-clipboard mr-1"></i>
                            Family Details
                            <div class="card-tools">
                                <button class="btn bg-maroon btn-xs" data-toggle="modal" data-target="#family_detailsModal"><i class="fa fa-plus"></i> Add Family Member</button>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Relationship</th>
                                    <th>Gender</th>
                                    <th>D.O.B.</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="family-members">
                                @php($family_members_count = 0)
                                @forelse($staff_member->family_details as $family_detail)
                                    <tr id="remove-family-member-form-{{ $family_detail->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $family_detail->name ?? '' }}</td>
                                        <td>{{ $family_detail->relationship ?? '' }}</td>
                                        <td>{{ $family_detail->gender }}</td>
                                        <td>{{ $family_detail->dob ?? '' }}</td>
                                        <td>
                                            <a href="{{ route('frontend.hmo.staff_member.familyMember', ['ara_number' => $staff_member->staff_ara_id, 'family_member' => $family_detail->id]) }}" type="button" class="btn btn-xs btn-info">View/Edit</a>
                                            <button type="button" class="btn btn-xs btn-danger" onclick="removeFamilyMember({{ $family_detail->id }})">Remove</button>
                                        </td>
                                    </tr>
                                    @php($family_members_count++)

                                @empty
                                    <tr>
                                        <td colspan="5">No Family Data Entered Yet</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div><!--/. container-fluid -->


    </section>

    <!-- Modal -->
    <div class="modal fade" id="family_detailsModal" tabindex="-1" role="dialog" aria-labelledby="family_detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="family_detailsModalLabel">Add A Family Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form onsubmit="return submitFamilyMemberForm()" id="add-family-member-form" method="POST">
                        @csrf
                        <input type="hidden" name="staff_member_id" value="{{ $staff_member->id }}">
                        <input type="hidden" name="user_id" value="{{ $staff_member->user->id }}">
                        <div class="form-group">
                            <label for="surname">Surname</label>
                            <input id="surname" name="surname" type="text" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input id="first_name" name="first_name" type="text" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                            <label for="other_name">Other name</label>
                            <input id="other_name" name="other_name" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input name="gender" id="gender_0" type="radio" class="custom-control-input" value="male" required="required">
                                    <label for="gender_0" class="custom-control-label">Male</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input name="gender" id="gender_1" type="radio" class="custom-control-input" value="female" required="required">
                                    <label for="gender_1" class="custom-control-label">Female</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input id="dob" name="dob" type="date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="relationship">Relationship</label>
                            <div>
                                <select id="relationship" name="relationship" class="custom-select" required="required">
                                    <option value="spouse">spouse</option>
                                    <option value="child">child</option>
                                    <option value="ward">ward</option>
                                    <option value="parent">parent</option>
                                    <option value="brother">brother</option>
                                    <option value="sister">sister</option>
                                    <option value="other">other</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button class="btn bg-navy btn-lg btn-block" type="submit">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('after-scripts')
    <script>
        // $(document).ready(function() {
            var numFamilyMembers = {{ $family_members_count }};

            function submitFamilyMemberForm() {
                event.preventDefault();
                var formData = new FormData(document.getElementById('add-family-member-form'));
                fetch('{{ route('frontend.hmo.staff_member.addFamilyMember', $staff_member->staff_ara_id) }}', {
                    method: 'POST',
                    body: formData
                })
                    .then(
                        response => response.json())
                    .then(function (data) {
                            console.log(data);
                            if (data.success == true) {
                                numFamilyMembers++;
                                let familyMembers = $('#family-members');
                                // console.log(data.familyMember);
                                familyMembers.append(
                                    `
                            <tr id="remove-family-member-form-${data.familyMember.id}">
                                <td>${numFamilyMembers}</td>
                                <td>${data.familyMember.surname} ${data.familyMember.first_name} ${data.familyMember.other_name} </td>
                                <td>${data.familyMember.relationship}</td>
                                <td>${data.familyMember.gender}</td>
                                <td>${data.familyMember.dob}</td>
                                <td>
                                    <a href="{{ config('app.url') }}/hmo/staff_member/{{ $staff_member->staff_ara_id }}/family-member/${data.familyMember.id}" class="btn btn-xs btn-info">View/Edit</a>
                                    <button type="button" class="btn btn-xs btn-danger" onclick="removeFamilyMember(${data.familyMember.id})">Remove</button>
                                </td>
                                </form>
                            </tr>
                               `
                                );
                                $('#family_detailsModal').modal('hide');
                                showInstantToast(data.message, 'success');
                            } else {
                                showInstantToast(data.message, 'warning');
                            }
                        }
                    )
                    .catch(function (err) {
                        console.log(err)
                    });
            }

            function removeFamilyMember(family_member_id) {

                fetch('{{ route('frontend.hmo.staff_member.removeFamilyMember', $staff_member->staff_ara_id) }}?family_member_id=' + family_member_id, {
                    method: 'GET',
                })
                    .then((response) => response.json())
                    .then(function (data) {
                        numFamilyMembers--;
                        $('#remove-family-member-form-' + family_member_id).remove();
                        showInstantToast(data.message);
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });

            }
        // });
    </script>
@endpush
