<!-- resources/views/frontend/legal_team_folder_accesses/edit.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Edit LegalTeamFolderAccess')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit LegalTeamFolderAccess</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.legal_team_folder_accesses.update', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to update this?')">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="user_id">User Id</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach(\App\Models\Auth\User::all() as $option)
                                    <option value="{{ $option->id }}" {{ $item->user_id == $option->id ? 'selected' : '' }}>{{ $option->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <div class="form-group">
                            <label for="folder_id">Folder</label>
                            <select name="folder_id" id="folder_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach(\App\Models\LegalTeamFolder::all() as $option)
                                    <option value="{{ $option->id }}" {{ $item->folder_id == $option->id ? 'selected' : '' }}>{{ $option->name }}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('frontend.legal_team_folder_accesses.index') }}" class="btn btn-secondary">Cancel</a>
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
