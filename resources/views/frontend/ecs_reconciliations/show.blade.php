<!-- resources/views/frontend/ecs_reconciliations/show.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Reconciliation Details')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reconciliation Details</h3>
                </div>
                <div class="card-body">
                    <p><strong>For Date:</strong> {{ $item->for_date }}</p>
<p><strong>ECS Sales Amount:</strong> {{ checkIntNumber($item->ecs_sales_amount) }}</p>
<p><strong>IBE Sales Amount:</strong> {{ checkIntNumber($item->ibe_sales_amount) }}</p>
<p><strong>Amounts Difference:</strong> {{ checkIntNumber($item->amounts_difference) }}</p>
<p><strong>Comment:</strong> {{ $item->comment }}</p>
<p><strong>Agent:</strong> {{ $item->agent_user_idRelation ? $item->agent_user_idRelation->full_name : '' }}</p>

                    <a href="{{ route('frontend.ecs_reconciliations.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
