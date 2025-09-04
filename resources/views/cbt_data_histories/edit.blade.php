@extends('frontend.layouts.app')

@section('content')

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
    <div class="card text-bg-theme">

         <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ !empty($title) ? $title : 'CBT Data History' }}</h4>
            <div>
                <a href="{{ route('cbt_data_histories.cbt_data_history.index') }}" class="btn btn-primary" title="Show All CBT Data History">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('cbt_data_histories.cbt_data_history.create') }}" class="btn btn-secondary" title="Create New CBT Data History">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="list-unstyled mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" class="needs-validation" novalidate action="{{ route('cbt_data_histories.cbt_data_history.update', $cbtDataHistory->id) }}" id="edit_cbt_data_history_form" name="edit_cbt_data_history_form" accept-charset="UTF-8" >
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('cbt_data_histories.form', [
                                        'cbtDataHistory' => $cbtDataHistory,
                                      ])

                <div class="col-lg-10 col-xl-9 offset-lg-2 offset-xl-3">
                    <input class="btn btn-primary" type="submit" value="Update">
                </div>
            </form>

        </div>
    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
