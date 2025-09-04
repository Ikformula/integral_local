<!-- resources/views/frontend/ecs_client_account_summaries/show.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Client Account Summary Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Client Account Summary Details</h3>
                </div>
                <div class="card-body">
                    <p><strong>Client:</strong> {{ $item->client_idRelation ? $item->client_idRelation->name : '' }}</p>
<p><strong>Credit Amount:</strong> {{ checkIntNumber($item->credit_amount) }}</p>
<p><strong>Ticket Number:</strong> {{ $item->ticket_number }}</p>
<p><strong>Details:</strong> {{ $item->details }}</p>
<p><strong>Debit Amount:</strong> {{ checkIntNumber($item->debit_amount) }}</p>
<p><strong>Balance:</strong> {{ checkIntNumber($item->balance) }}</p>

                    <a href="{{ route('frontend.ecs_client_account_summaries.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
