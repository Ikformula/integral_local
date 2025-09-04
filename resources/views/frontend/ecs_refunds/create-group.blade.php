<!-- resources/views/frontend/ecs_refunds/create.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Add Group Refunds ')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Group Refunds </h3>
                </div>
                <div class="card-body">
                    @include('frontend.ecs_refunds._create-group-refunds-form')
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
