<!-- resources/views/frontend/service_now_group_agents/show.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Service Now Group Agent  Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Service Now Group Agent  Details</h3>
                </div>
                <div class="card-body">
                    <p><strong>User:</strong> {{ $item->user_idRelation ? $item->user_idRelation->full_name : '' }}</p>
<p><strong>Staff ARA ID:</strong> {{ $item->staff_ara_idRelation ? $item->staff_ara_idRelation->name_and_ara : '' }}</p>
<p><strong>Service Now Group:</strong> {{ $item->service_now_group_idRelation ? $item->service_now_group_idRelation->name : '' }}</p>

                    <a href="{{ route('frontend.service_now_group_agents.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
