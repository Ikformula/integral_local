<!-- resources/views/frontend/service_now_group_viewers/edit.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Edit Service Now Group Viewer ')
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
                    <h3 class="card-title">Edit Service Now Group Viewer </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.service_now_group_viewers.update', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to update this?')">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="user_id">User</label>
                            <select name="user_id" id="user_id" class="form-control select2" required>
                                <option value="">-- Select --</option>
                                @foreach(\App\Models\Auth\User::all() as $option)
                                    <option value="{{ $option->id }}" {{ $item->user_id == $option->id ? 'selected' : '' }}>{{ $option->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="staff_ara_id">Staff ARA ID</label>
                            <select name="staff_ara_id" id="staff_ara_id" class="form-control select2" required>
                                <option value="">-- Select --</option>
                                @foreach(\App\Models\StaffMember::all() as $option)
                                    <option value="{{ $option->staff_ara_id }}" {{ $item->staff_ara_id == $option->staff_ara_id ? 'selected' : '' }}>{{ $option->name_and_ara }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="service_now_group_id">Service Now Group</label>
                            <select name="service_now_group_id" id="service_now_group_id" class="form-control select2" required>
                                <option value="">-- Select --</option>
                                @foreach(\App\Models\ServiceNowGroup::all() as $option)
                                    <option value="{{ $option->id }}" {{ $item->service_now_group_id == $option->id ? 'selected' : '' }}>{{ $option->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Can View All Tickets in the ServiceNow Group?</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="can_view_all_tickets" id="can_view_all_tickets_1" value="1" {{ $item->can_view_all_tickets == '1' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="can_view_all_tickets_1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="can_view_all_tickets" id="can_view_all_tickets_2" value="0" {{ $item->can_view_all_tickets == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_view_all_tickets_2">No</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('frontend.service_now_group_viewers.index') }}" class="btn btn-secondary">Cancel</a>
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
