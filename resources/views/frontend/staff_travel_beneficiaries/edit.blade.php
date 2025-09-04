<!-- resources/views/frontend/staff_travel_beneficiaries/edit.blade.php -->
{{-- filepath: c:\laragon\www\arik_web_portals\resources\views\frontend\staff_travel_beneficiaries\edit.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Edit Staff Travel Beneficiary')

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Staff Travel Beneficiary</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.staff_travel_beneficiaries.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="staff_ara_id">Staff ARA ID</label>
                            <select name="staff_ara_id" id="staff_ara_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach($staffMembers as $staffMember)
                                    <option value="{{ $staffMember->staff_ara_id }}" {{ $item->staff_ara_id == $staffMember->staff_ara_id ? 'selected' : '' }}>
                                        {{ $staffMember->name }}, {{ $staffMember->staff_ara_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="firstname">Firstname</label>
                            <input type="text" name="firstname" id="firstname" class="form-control" value="{{ $item->firstname }}" required>
                        </div>

                        <div class="form-group">
                            <label for="surname">Surname</label>
                            <input type="text" name="surname" id="surname" class="form-control" value="{{ $item->surname }}" required>
                        </div>

                        <div class="form-group">
                            <label for="other_name">Other Name</label>
                            <input type="text" name="other_name" id="other_name" class="form-control" value="{{ $item->other_name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" name="dob" id="dob" class="form-control" value="{{ substr($item->dob, 0, 10) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Gender</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="Male" value="Male" {{ $item->gender == 'Male' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="Male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="Female" value="Female" {{ $item->gender == 'Female' ? 'checked' : '' }}>
                                <label class="form-check-label" for="Female">Female</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="relationship">Relationship</label>
                            <select name="relationship" id="relationship" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="Wife" {{ $item->relationship == 'Wife' ? 'selected' : '' }}>Wife</option>
                                <option value="Husband" {{ $item->relationship == 'Husband' ? 'selected' : '' }}>Husband</option>
                                <option value="Child" {{ $item->relationship == 'Child' ? 'selected' : '' }}>Child</option>
                                <option value="Father" {{ $item->relationship == 'Father' ? 'selected' : '' }}>Father</option>
                                <option value="Mother" {{ $item->relationship == 'Mother' ? 'selected' : '' }}>Mother</option>
                                <option value="Brother" {{ $item->relationship == 'Brother' ? 'selected' : '' }}>Brother</option>
                                <option value="Sister" {{ $item->relationship == 'Sister' ? 'selected' : '' }}>Sister</option>
                                <option value="Extended Family" {{ $item->relationship == 'Extended Family' ? 'selected' : '' }}>Extended Family</option>
                                <option value="Friend" {{ $item->relationship == 'Friend' ? 'selected' : '' }}>Friend</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <input type="file" name="photo" id="photo" class="form-control">
                            @if($item->photo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $item->photo) }}" alt="Current Photo" class="img-thumbnail" style="max-width: 150px;">
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        @if(isset($_GET['personal']) && $_GET['personal'] == 1)
                            <input type="hidden" name="personal" value="1">
                        <a href="{{ route('frontend.staff_travel_beneficiaries.index.mine') }}" class="btn btn-secondary">Cancel</a>
                        @else
                        <a href="{{ route('frontend.staff_travel_beneficiaries.index') }}" class="btn btn-secondary">Cancel</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('select').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush
