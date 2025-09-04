<!-- resources/views/frontend/ecs_refunds/show.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Refund  Details')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Refund  Details</h3>
                </div>
                <div class="card-body">
                    <p><strong>Client:</strong> {{ $item->client_idRelation ? $item->client_idRelation->name : '' }}</p>
<p><strong>Surname:</strong> {{ $item->surname }}</p>
<p><strong>First Name:</strong> {{ $item->first_name }}</p>
<p><strong>Ticket Number:</strong> {{ $item->ticket_number }}</p>
<p><strong>Booking Reference:</strong> {{ $item->booking_reference }}</p>
<p><strong>Route:</strong> {{ $item->route }}</p>
<p><strong>Travel Date:</strong> {{ $item->travel_date }}</p>
<p><strong>Class:</strong> {{ $item->ticket_class }}</p>
<p><strong>Amount Refundable:</strong> {{ checkIntNumber($item->amount_refundable) }}</p>
<p><strong>Remarks:</strong> {!! $item->remarks !!}</p>
<p><strong>Agent:</strong> {{ $item->agent_user_idRelation ? $item->agent_user_idRelation->full_name : '' }}</p>

                    <a href="{{ route('frontend.ecs_refunds.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
