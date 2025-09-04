<!-- resources/views/frontend/staff_travel_beneficiaries/show.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Staff Travel Beneficiary Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Staff Travel Beneficiary Details</h3>
                </div>
                <div class="card-body">
                    <p><strong>Staff ARA ID:</strong> {{ $item->staff_ara_id }}</p>
<p><strong>Firstname:</strong> {{ $item->firstname }}</p>
<p><strong>Surname:</strong> {{ $item->surname }}</p>
<p><strong>Other Name:</strong> {{ $item->other_name }}</p>
<p><strong>Date of Birth:</strong> {{ $item->dob->toDateString() }}</p>
<p><strong>Gender:</strong> {{ $item->gender }}</p>
<p><strong>Relationship:</strong> {{ $item->relationship }}</p>
<p><strong>Photo:</strong> <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->surname }} {{ $item->other_name }}'s Photo" class="img-thumbnail" style="max-width: 350px;"></p>
<p><strong>Posted By:</strong> {{ $item->posted_byRelation ? $item->posted_byRelation->full_name : '' }}</p>
<p><strong>Status:</strong> {{ $item->status }}</p>
<p><strong>Actioned By:</strong> {{ $item->actioned_byRelation ? $item->actioned_byRelation->full_name : '' }}</p>
<p><strong>Actioned Time:</strong> {{ $item->actioned_time }}</p>
<p><strong>Actioned Comment:</strong> {{ $item->actioned_comment }}</p>

                    @if(isset($mode) && $mode == 'personal')
                        <a href="{{ route('frontend.staff_travel_beneficiaries.index.mine') }}" class="btn btn-secondary">Back</a>
                        <a href="{{ route('frontend.staff_travel_beneficiaries.edit', $item->id) }}?personal=1" class="btn btn-primary">Edit</a>
                        @else
                    <a href="{{ route('frontend.staff_travel_beneficiaries.index') }}" class="btn btn-secondary">Back</a>
                    <a href="{{ route('frontend.staff_travel_beneficiaries.edit', $item->id) }}" class="btn btn-primary">Edit</a>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
