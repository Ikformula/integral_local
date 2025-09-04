@extends('frontend.layouts.app')

@push('after-styles')
    <script src="https://unpkg.com/htmx.org@2.0.1" integrity="sha384-QWGpdj554B4ETpJJC9z+ZHJcA/i59TyjxEPXiiUgN2WmTyV5OEZWCD6gQhgkdpB/" crossorigin="anonymous"></script>
@endpush

@section('content')

<section class="content" id="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

    @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {!! session('success_message') !!}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card text-bg-theme">

        <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">CBT Subjects</h4>
            <div>
{{--                <a href="{{ route('cbt_subjects.cbt_subject.create') }}" class="btn btn-secondary" title="Create New CBT Subject">--}}
{{--                    <span class="fa-solid fa-plus" aria-hidden="true"></span>--}}
{{--                </a>--}}
                <button hx-get="{{ route('cbt_subjects.cbt_subject.create') }}"
                        hx-trigger="click"
                        hx-target="#section"
                        hx-swap="outerHTML"
                        class="btn btn-secondary" title="Create New CBT Subject">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </button>
            </div>
        </div>

        @if(count($cbtSubjects) == 0)
            <div class="card-body text-center">
                <h4>No CBT Subjects Available.</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Name</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($cbtSubjects as $cbtSubject)
                        <tr>
                            <td class="align-middle">{{ $cbtSubject->name }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('cbt_subjects.cbt_subject.destroy', $cbtSubject->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('cbt_subjects.cbt_subject.show', $cbtSubject->id ) }}" class="btn btn-info" title="Show CBT Subject">
                                            <span class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('cbt_subjects.cbt_subject.edit', $cbtSubject->id ) }}" class="btn btn-primary" title="Edit CBT Subject">
                                            <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete CBT Subject" onclick="return confirm(&quot;Click Ok to delete CBT Subject.&quot;)">
                                            <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                                        </button>
                                    </div>

                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

            {!! $cbtSubjects->links('pagination') !!}
        </div>

        @endif

    </div>


                </div>
            </div>


        </div>
    </section>
@endsection
