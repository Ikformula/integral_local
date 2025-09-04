<!-- resources/views/frontend/legal_team_folders/create.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Add New LegalTeamFolder')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New LegalTeamFolder</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.legal_team_folders.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
        
                        <div class="form-group">
                            <label for="parent_id">Parent Folder</label>
                            <select name="parent_id" id="parent_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach(\App\Models\LegalTeamFolder::all() as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            </select>
                        </div>
        
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('frontend.legal_team_folders.index') }}" class="btn btn-secondary">Cancel</a>
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
