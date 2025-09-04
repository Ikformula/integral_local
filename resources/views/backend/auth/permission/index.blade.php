@extends('backend.layouts.app')

@section('title', app_name() . ' | Permissions')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    Permissions
                </h4>
            </div><!--col-->

{{--            <div class="col-sm-7 pull-right">--}}
{{--                @include('backend.auth.role.includes.header-buttons')--}}
{{--            </div><!--col-->--}}
        </div><!--row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Users</th>
                            <th>Created</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                            <td>{{ $permission->id }}</td>
                            <td>{{ $permission->name }}</td>
                                <td><a href="{{ route('admin.auth.permission.users', $permission) }}">Users</a> </td>
                            <td>{{ $permission->created_at->toDayDateTimeString() }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <form action="{{ route('admin.auth.permission.store') }}" method="POST">
                                @csrf
                                <td>Add new</td>
                                <td colspan="2"><input type="text" name="name" class="form-control" maxlength="170"></td>
                                <td><button type="submit" class="btn btn-primary">Submit</button></td>
                            </form>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
