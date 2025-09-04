@php
    $password = generateNumericOTP(12).now()->dayName;
@endphp
<input type="hidden" name="password" value="{{ $password }}">
<input type="hidden" name="password_confirmation" value="{{ $password }}">

<input type="hidden" name="active" id="active" value="1">
<input type="hidden" name="confirmed" id="confirmed" value="1">
<input class="switch-input" type="hidden" name="roles[]" id="role-2" value="user">
