<!-- resources/views/frontend/legal_team_external_lawyers/create.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Add New External Lawyer')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New External Lawyer</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.auth.user.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="firm">Firm</label>
                            <input type="text" name="firm" id="firm" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" name="notes" id="notes" rows="5"></textarea>
                            <small class="form-text text-muted">Kindly explain in detail</small>
                        </div>
                        @include('includes.partials._hidden-user-reg-password-field')

                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('frontend.legal_team_external_lawyers.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
    <!-- Load CKEditor only if needed -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        document.querySelectorAll('textarea').forEach(function(textarea) {
            if(textarea.id) {
                ClassicEditor.create(textarea).catch(error => { console.error(error); });
            }
        });
    </script>
@endpush
