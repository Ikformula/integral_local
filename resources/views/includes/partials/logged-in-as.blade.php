@impersonating
<div class="container-fluid">
    <div class="row">
        <div class="col">
    <div class="alert alert-warning logged-in-as mt-1">
        You are currently logged in as {{ auth()->user()->name }}. <a href="{{ route('impersonate.leave') }}">Return to your account</a>.
    </div><!--alert alert-warning logged-in-as-->
        </div>
    </div>
</div>
@endImpersonating
