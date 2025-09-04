<!-- resources/views/frontend/ecs_reconciliations/edit.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Edit Reconciliation')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Reconciliation</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.ecs_reconciliations.update', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to update this?')">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="for_date">For Date</label>
                            <input type="date" name="for_date" id="for_date" class="form-control" value="{{ $item->for_date }}" required>
                        </div>

                        <div class="form-group">
                            <label for="ecs_sales_amount">ECS Sales Amount</label>
                            <input type="number" step="0.01" name="ecs_sales_amount" id="ecs_sales_amount" class="form-control" value="{{ $item->ecs_sales_amount }}" required>
                        </div>

                        <div class="form-group">
                            <label for="ibe_sales_amount">IBE Sales Amount</label>
                            <input type="number" step="0.01" name="ibe_sales_amount" id="ibe_sales_amount" class="form-control" value="{{ $item->ibe_sales_amount }}" required>
                        </div>

                        <div class="form-group">
                            <label for="amounts_difference">Amounts Difference</label>
                            <input type="number" step="0.01" name="amounts_difference" id="amounts_difference" class="form-control" value="{{ $item->amounts_difference }}" required>
                        </div>

                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea class="form-control" name="comment" id="comment" rows="5">{{ $item->comment }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="agent_user_id">Agent</label>
                            <select name="agent_user_id" id="agent_user_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach(\App\Models\Auth\User::all() as $option)
                                    <option value="{{ $option->id }}" {{ $item->agent_user_id == $option->id ? 'selected' : '' }}>{{ $option->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('frontend.ecs_reconciliations.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        document.querySelectorAll('textarea').forEach(function(textarea) {
            ClassicEditor.create(textarea).catch(error => { console.error(error); });
        });
    </script>
@endpush
