<!-- resources/views/frontend/legal_team_external_lawyers/show.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'LegalTeamExternalLawyer Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">External Lawyer Details</h3>
                </div>
                <div class="card-body">
                    <p><strong>First Name:</strong> {{ $item->user->first_name }}</p>
<p><strong>Last Name:</strong> {{ $item->user->last_name }}</p>
<p><strong>Email:</strong> {{ $item->user->email }}</p>
<p><strong>Firm:</strong> {{ $item->firm }}</p>
<p><strong>Notes:</strong> {!! $item->notes !!}</p>

                    <a href="{{ route('frontend.legal_team_external_lawyers.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
