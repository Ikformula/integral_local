<!-- resources/views/frontend/stb_login_logs/edit.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Edit StbLoginLog')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit StbLoginLog</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.stb_login_logs.update', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to update this?')">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="staff_ara_id">Staff ARA ID</label>
                            <input type="text" name="staff_ara_id" id="staff_ara_id" class="form-control" value="{{ $item->staff_ara_id }}" required>
                        </div>
        
                        <div class="form-group">
                            <label for="ip_address">IP Address</label>
                            <input type="text" name="ip_address" id="ip_address" class="form-control" value="{{ $item->ip_address }}" required>
                        </div>
        
                        <div class="form-group">
                            <label for="logged_in_at">Logged In At</label>
                            <input type="datetime-local" name="logged_in_at" id="logged_in_at" class="form-control" value="{{ $item->logged_in_at }}" required>
                        </div>
        
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('frontend.stb_login_logs.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        document.querySelectorAll('textarea').forEach(function(textarea) {
            ClassicEditor.create(textarea).catch(error => { console.error(error); });
        });
    </script>
@endpush
