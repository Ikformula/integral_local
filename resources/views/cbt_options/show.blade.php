@extends('frontend.layouts.app')

@section('content')

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($title) ? $title : 'CBT Option' }}</h4>
        <div>
            <form method="POST" action="{!! route('cbt_options.cbt_option.destroy', $cbtOption->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('cbt_options.cbt_option.edit', $cbtOption->id ) }}" class="btn btn-primary" title="Edit CBT Option">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="Delete CBT Option" onclick="return confirm(&quot;Click Ok to delete CBT Option.?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('cbt_options.cbt_option.index') }}" class="btn btn-primary" title="Show All CBT Option">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('cbt_options.cbt_option.create') }}" class="btn btn-secondary" title="Create New CBT Option">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">CBT Question</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($cbtOption->CbtQuestion)->id }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Body</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtOption->body }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Is Correct</dt>
            <dd class="col-lg-10 col-xl-9">{{ ($cbtOption->is_correct) ? 'Yes' : 'No' }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Created At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtOption->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">Updated At</dt>
            <dd class="col-lg-10 col-xl-9">{{ $cbtOption->updated_at }}</dd>

        </dl>

    </div>
</div>

                </div>
            </div>
        </div>
    </section>
@endsection
