<!-- resources/views/frontend/stb_login_logs/create.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Add New StbLoginLog')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New StbLoginLog</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.stb_login_logs.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="staff_ara_id">Staff ARA ID</label>
                            <input type="text" name="staff_ara_id" id="staff_ara_id" class="form-control" required>
                        </div>
        
                        <div class="form-group">
                            <label for="ip_address">IP Address</label>
                            <input type="text" name="ip_address" id="ip_address" class="form-control" required>
                        </div>
        
                        <div class="form-group">
                            <label for="logged_in_at">Logged In At</label>
                            <input type="datetime-local" name="logged_in_at" id="logged_in_at" class="form-control" required>
                        </div>
        
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('frontend.stb_login_logs.index') }}" class="btn btn-secondary">Cancel</a>
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
