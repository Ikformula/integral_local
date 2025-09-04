<!-- resources/views/frontend/legal_team_folder_accesses/create.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Add New LegalTeamFolderAccess')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New LegalTeamFolderAccess</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.legal_team_folder_accesses.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="user_id">User Id</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach(\App\Models\Auth\User::all() as $option)
                                    <option value="{{ $option->id }}">{{ $option->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <div class="form-group">
                            <label for="folder_id">Folder</label>
                            <select name="folder_id" id="folder_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach(\App\Models\LegalTeamFolder::all() as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('frontend.legal_team_folder_accesses.index') }}" class="btn btn-secondary">Cancel</a>
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
