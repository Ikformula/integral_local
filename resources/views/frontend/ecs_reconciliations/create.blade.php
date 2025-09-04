<!-- resources/views/frontend/ecs_reconciliations/create.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Add New Reconciliation')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-7">
            @include('frontend.ecs_reconciliations._create-form')
        </div>
    </div>
</div>
@endsection
