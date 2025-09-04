@extends('frontend.layouts.app')

@section('content')

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($title) ? $title : 'CBT Data History' }}</h4>
        <div>
            <form method="POST" action="{!! route('cbt_data_histories.cbt_data_history.destroy', $cbtDataHistory->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('cbt_data_histories.cbt_data_history.edit', $cbtDataHistory->id ) }}" class="btn btn-primary" title="Edit CBT Data History">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="Delete CBT Data History" onclick="return confirm(&quot;Click Ok to delete CBT Data History.?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('cbt_data_histories.cbt_data_history.index') }}" class="btn btn-primary" title="Show All CBT Data History">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('cbt_data_histories.cbt_data_history.create') }}" class="btn btn-secondary" title="Create New CBT Data History">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">Model Type</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtDataHistory->model_type }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Model</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($cbtDataHistory->model)->id }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Previous Value</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtDataHistory->previous_value }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Changed By User</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($cbtDataHistory->changedByUser)->id }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Created At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtDataHistory->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Updated At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtDataHistory->updated_at }}</dd>

        </dl>

    </div>
</div>

                </div>
            </div>
        </div>
    </section>
@endsection
