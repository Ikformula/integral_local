@php
$locations = [
"ABB",
"ABJ",
"ABV",
"ACC",
"AKR",
"BCU",
"BJL",
"BNI",
"CBQ",
"COO",
"DKR",
"DLA",
"DXB",
"ENU",
"FNA",
"GMO",
"IBA",
"ILR",
"JFK",
"JNB",
"JOS",
"KAD",
"KAN",
"LAD",
"LBV",
"LHR",
"LOS",
"MIU",
"PHC",
"PHG",
"QOW",
"QRW",
"QUO",
"ROB",
"SKO",
"YOL"
];

@endphp

@if(isset($selected))
    @foreach($locations as $location)
        <option value="{{ $location }}" {{ $selected == $location ? 'selected' : '' }}>{{ $location }}</option>
    @endforeach
    @else
@foreach($locations as $location)
    <option value="{{ $location }}">{{ $location }}</option>
@endforeach
    @endif
