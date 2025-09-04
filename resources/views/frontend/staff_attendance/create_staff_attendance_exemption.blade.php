@extends('frontend.layouts.app')

@section('title', 'Staff Absence/Lateness Authorization' )

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <form action="{{ route('frontend.attendance.store.manager.authorization') }}" method="POST">
                                @csrf
                                <input type="hidden" name="manager_ara_id" value="{{ $own_staff_details->staff_ara_id }}">
                                <div class="form-group">
                                    <label for="staff_ara_id">Staff Member</label>
                                    <select class="form-control select2" name="staff_ara_id" id="staff_ara_id" required>
                                        @foreach($staff_members as $staff_member)
                                            <option value="{{ $staff_member->staff_ara_id }}">{{ $staff_member->name }}, {{ $staff_member->staff_ara_id }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" id="start_date" required value="{{ old('start_date', '') }}">
                                </div>

                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date" value="{{ old('end_date', '') }}">
                                    <small>Not needed if you're checking the "Is Indefinite box"</small>
                                </div>


                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="is_indefinite" id="is_indefinite">
                                    <label class="form-check-label" for="is_indefinite">
                                        Is indefinite
                                    </label>
                                </div>


                                <div class="form-group">
                                    <label for="reason">Reason</label>
                                    <input type="text" class="form-control" name="reason" id="reason" required  value="{{ old('reason', '') }}">
                                </div>


                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea class="form-control" name="notes" id="notes">{{ old('notes', '') }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary float-right">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('after-scripts')
    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush
