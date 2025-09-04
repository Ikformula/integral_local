<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Attendance Marking | {{ app_name() }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', app_name())</title>
    <meta name="description" content="@yield('meta_description', 'Arik Air Web Portals')">
    <meta name="author" content="@yield('meta_author', 'Asuquo Bartholomew Ikechukwu')">

    <!-- Favicons -->
    <link href="https://www.arikair.com/assets/images/favicon.ico" rel="icon">
    <!-- Core theme CSS (includes Bootstrap)-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css"
          integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="{{ asset('css/attendance-bootstrap.min.css') }}">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        /*input::-webkit-outer-spin-button,*/
        /*input::-webkit-inner-spin-button {*/
        /*    display: none;*/
        /*}*/

        .bg-out-attendance {
            background-color: #C5C7DC;
        }

        .container {
            max-width: 95%;
            zoom: 90%;
        }
    </style>
</head>
<body>
<!-- Responsive navbar-->
<nav class="navbar navbar-dark bg-primary fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#"><img src="{{ asset(config('view.logo.gray')) }}" alt="Arik Air Logo"
                                              class="brand-image " style="opacity: .8"></a>
        @if(isset($stats))
            <div class="row justify-content-end">
                <div class="col">
                    <div class="btn-group btn-group btn-block" role="group" aria-label="Large button group">
                        <button type="button" class="btn btn-outline-light">In
                            <span class="card-title" style="font-size:1.8em;" id="in_counter">{{ $stats['ins'] }}</span>
                        </button>
                        <button type="button" class="btn btn-outline-light">Out
                            <span class="card-title" style="font-size:1.8em;"
                                  id="out_counter">{{ $stats['outs'] }}</span>
                        </button>
                        <button type="button" class="btn btn-outline-light ">In Premises
                            <span class="card-title" style="font-size:1.8em;"
                                  id="on_prem_counter">{{ $stats['on_prem'] }}</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</nav>
<!-- Page content-->
<div style="margin-top: 6rem;"></div>
<div class="container mt-5">
    <h4>ARIKPASS</h4>
    <div class="row">
        <div class="col-md-7">
            <div class="card mb-3 animate__animated animate__backInUp">
                <div class="card-header">Staff Movement Timestamp - <strong><span id="MyClockDisplay"
                                                                                  onload="showTime()"></span></strong>
                </div>
                <div class="card-body">


                    <div class="row">
                        <div class="col">
                            <h4 class="card-title">Arik Air Staff Details</h4>
                            <div class="row">
                                <div class="col mb-4">
                                    <ul class="list-group" id="attendance_list_group">
                                    </ul>
                                </div>
                            </div>

                            <form id="check_staff_info">
                                @csrf
                                <input type="hidden" name="operator_user_id" value="{{ auth()->id() }}">
                                <div class="my-2">
                                    <label for="staff_ara_id">ARA Number</label><br>
                                    {{--                                    <small class="text-info">You can now check for contract staff with letters in their ARA IDs e.g 8021c</small>--}}
                                    <div class="input-group">
                                        <button class="btn btn-outline-danger" type="reset" id="clear-button">x</button>
                                        <input type="text" name="staff_ara_id" id="staff_ara_id" minlength="4"
                                               class="form-control" autofocus placeholder="1066" autocomplete="off">
                                        <button class="btn btn-outline-secondary" type="submit"
                                                id="check-ara-id-button">Check
                                        </button>
                                    </div>
                                    <small class="text-muted">Enter ARA number only</small>
                                </div>
                            </form>

                            <form id="attendance_form">
                                @csrf
                                <input type="hidden" id="ara_id" name="staff_ara_id">

                                <div class="form-group" @if(!$outstation) id="temperature"
                                     @endif style="display: none;">
                                    <label>Temperature</label>
                                    <input type="number" name="temperature" id="temperature_field" class="form-control">
                                </div>

                                <input type="hidden" name="direction" id="direction_input">
                                <div class="form-group">
                                    <button type="button" id="attendance_button_in"
                                            class="btn btn-info mark-attendance-buttons disabled">
                                        Mark In
                                    </button>
                                    <button type="button" id="attendance_button_out"
                                            class="btn btn-danger mark-attendance-buttons float-right disabled">
                                        Mark Out
                                    </button>
                                </div>
                                <small class="text-info">Click according to the direction the staff is taking.</small>
                            </form>
                        </div>

                        <div class="col mb-3" style="display: none" id="id_card_row">
                            <div id="staff_details"></div>
                            <img src="..." class="img-fluid rounded mx-auto d-block" alt="..." id="id_card">
                        </div>
                    </div>


                </div>
            </div>
        </div>


    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script
    src="https://code.responsivevoice.org/responsivevoice.js?key=iDy5OGEK"></script>
@include('includes.partials.messages-toastr')

<script>
    function showTime() {
        var date = new Date();
        var h = date.getHours(); // 0 - 23
        var m = date.getMinutes(); // 0 - 59
        var s = date.getSeconds(); // 0 - 59
        var session = "AM";

        // if (h == 0) {
        //     h = 12;
        // }

        // if (h > 12) {
        //     h = h - 12;
        //     session = "PM";
        // }

        h = (h < 10) ? "0" + h : h;
        m = (m < 10) ? "0" + m : m;
        s = (s < 10) ? "0" + s : s;

        var time = h + ":" + m + ":" + s;
        // var time = h + ":" + m + ":" + s + " " + session;
        document.getElementById("MyClockDisplay").innerText = time;
        document.getElementById("MyClockDisplay").textContent = time;

        setTimeout(showTime, 1000);

    }

    showTime();
</script>


<script>

    let in_count = {{ $stats['ins'] ?? 0}};
    let out_count = {{ $stats['outs'] ?? 0 }};
    let on_prem_count = {{ $stats['on_prem'] ?? 0 }};

    function setButtonsState(newState) {
        var setState = false;
        var stateClass = '';
        if (newState == 'disabled') {
            setState = 'disabled';
            stateClass = 'disabled';
        }

        $('#attendance_button_in').attr("class", "btn btn-info " + stateClass);
        $('#attendance_button_in').attr("disabled", setState);
        $('#attendance_button_out').attr("class", "btn btn-danger float-right " + stateClass);
        $('#attendance_button_out').attr("disabled", setState);
    }

    function checkStaffInfo(event) {
        event.preventDefault();
        $('#id_card_row').hide();
        $('#temperature').hide();
        $('#staff_details').empty();
        $('#attendance_list_group').empty();
        $('#check-ara-id-button').html('Checking');

        setButtonsState('disabled');

        var formData = new FormData(document.getElementById('check_staff_info'));
        fetch('{{ route('check_staff_info') }}', {
            method: 'POST',
            body: formData
        })
            .then(
                response => response.json())
            .then(function (data) {

                $('#check-ara-id-button').html('Check');
                // console.log(data);
                if (typeof data.staff_member !== 'undefined') {
                    let surname = data.staff_member.surname == null ? '' : data.staff_member.surname;
                    if (data.staff_member.restrict_access_from != null) {
                        $('#staff_details').append(`
                        <div class="pb-0 alert alert-dismissible alert-danger">
                        <strong>Alert</strong>
<p>${surname} ${data.staff_member.other_names} <br>
Resigned at: ${data.staff_member.resigned_on} <br>
Restrict Access From ${data.staff_member.restrict_access_from}
</p>
                    </div>
                        `);
                    } else {
                        if(!(data.staff_member.id_card_file_name)){
                            responsiveVoice.speak(`Hello ${data.staff_member.other_names}, you are yet to upload your ID Card on Integral.`);
                        }else {
                            responsiveVoice.speak(`Hello ${data.staff_member.other_names}`);
                        }
                        $('#staff_details').append(`
                        <div class="pb-0 alert alert-dismissible alert-success pr-1">
                        <strong>Staff Information</strong>
<p><strong>Name: </strong> ${surname} ${data.staff_member.other_names} <br>
<strong>Department: </strong> ${data.staff_member.department_name}<br>
<strong>Designation: </strong> ${data.staff_member.job_title}<br>
<strong>Paypoint: </strong> ${data.staff_member.paypoint}<br>
</p>
                    </div>
                        `);

                        $('#ara_id').val(data.staff_member.staff_ara_id);



                        // show ID card
                        $('#id_card').attr('src', '{{ asset('/img/id_cards')}}/' + data.staff_member.id_card_file_name);
                        $('#id_card_row').show();
                        $('#temperature').show();

                        setButtonsState('');
                    }


                } else if (typeof data.msg !== 'undefined') {
                    $('#staff_details').append(`
                        <div class="pb-0 alert alert-dismissible alert-warning">
                        <strong>Notice</strong>
<p>${data.msg}</p>
                    </div>
                        `);
                } else if (typeof data.staff_member !== 'undefined' && data.staff_member.restrict_access_from != 'undefined') {
                    responsiveVoice.speak(`Hello ${data.staff_member.other_names}`);
                    $('#staff_details').append(`
                        <div class="pb-0 alert alert-dismissible alert-danger">
                        <strong>Alert</strong>
<p>${surname} ${data.staff_member.other_names} <br>
Resigned at: ${data.staff_member.resigned_on} <br>
Restrict Access From ${data.staff_member.restrict_access_from}
</p>
                    </div>
                        `);
                } else {
                    $('#staff_details').append(`
                        <div class="pb-0 alert alert-dismissible alert-warning">
                        <strong>No Message</strong>
                    </div>
                        `);
                }

                if (typeof data.todays_attendances !== 'undefined') {
                    // display list
                    $('#attendance_list_group').append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Attendance @if(!$outstation)/Temperature @endif for Today</strong>
                                        </li>`);

                    data.todays_attendances.forEach(function (value, i) {
                        $('#attendance_list_group').append(`
                                                     <li class="list-group-item d-flex justify-content-between align-items-center  animate__animated ${i % 2 == 0 ? 'animate__backInUp' : 'animate__backInDown'}  ${value.direction == 'in' ? '' : 'bg-out-attendance'}">
                                            <span class="text-uppercase text-lg"> ${value.direction}</span>

<div class="btn-group">
                        <button type="button" class="btn btn-sm  btn-primary">${value.hour}:${value.minutes}</button>
                        @if(!$outstation)
                        <button type="button" class="btn btn-sm  text-white bg-${value.temperature > 36.9 ? 'danger' : 'success'}">${value.temperature} °c</button>
                        @endif
                        </div>
                        </li>
                                                    `);
                    });
                }
            })
            {{-- .catch(err => console.error(err)); --}}
            .catch(function (err) {
                {{-- checkButton.text = 'Check'; --}}
                console.error(err);
            });

    }

    function updateStats() {
        $('#in_counter').html(in_count);
        $('#out_counter').html(out_count);
        $('#on_prem_counter').html(in_count - out_count);
    }

    function submitForm(direction) {
        {{--  event.preventDefault(); --}}
        setButtonsState('disabled');
        var formData = new FormData(document.getElementById('attendance_form'));
        // console.log(direction);
        formData.append("direction", direction);
        fetch('{{ route('mark_attendance') }}', {
            method: 'POST',
            body: formData
        })
            .then(
                response => response.json())
            .then(function (data) {
                    console.log(data);
                    if (data.attendance_entered == true) {
                        // show notification for such
                        $('#staff_details').append(`
                        <div class="pb-0 alert alert-dismissible alert-success">
                        <strong>Attendance Marked</strong>
                    </div>
                        `);

                        $('#temperature_field').val('');
                        $('#temperature').hide();

                        $('#in_counter').html(data.stats.ins);
                        $('#out_counter').html(data.stats.outs);
                        $('#on_prem_counter').html(data.stats.on_prem);
                    } else if (data.msg !== 'undefined') {
                        $('#staff_details').append(`
                        <div class="pb-0 alert alert-dismissible alert-warning">
                        <strong>Attendance Not Marked</strong>
<p>${data.msg}</p>
                    </div>
                        `);
                    } else if (data.staff_member.restrict_access_from != null) {
                        $('#staff_details').append(`
                        <div class="pb-0 alert alert-dismissible alert-danger">
                        <strong>Attendance Not Marked</strong>
<p>${surname} ${data.staff_member.other_names} <br>
Resigned at: ${data.staff_member.resigned_on} <br>
Restrict Access From ${data.staff_member.restrict_access_from}
</p>
                    </div>
                        `);
                    } else {
                        $('#staff_details').append(`
                        <div class="pb-0 alert alert-dismissible alert-warning">
                        <strong>Attendance Not Marked</strong>
<p>${data.msg}</p>
                    </div>
                        `);
                    }

                    if (typeof data.nows_attendance !== 'undefined') {
                        // display list
                        if (data.nows_attendance.direction == 'in') {
                            in_count++;
                        } else {
                            out_count++;
                        }

                        responsiveVoice.speak(`Attendance marked, direction: ${data.nows_attendance.direction}`);
                        updateStats();

                        // data.todays_attendances.forEach(function (value, i) {
                        $('#attendance_list_group').append(`
                                                     <li class="list-group-item d-flex justify-content-between align-items-center  animate__animated  ${data.nows_attendance.direction == 'in' ? 'animate__backInDown' : 'animate__backInUp bg-out-attendance'}">
                                            <span class="text-uppercase text-lg"> ${data.nows_attendance.direction}</span>

<div class="btn-group">
                        <button type="button" class="btn btn-sm  btn-primary">${data.nows_attendance.hour}:${data.nows_attendance.minutes}</button>
                        @if(!$outstation)
                        <button type="button" class="btn btn-sm  text-white bg-${data.nows_attendance.temperature > 36.9 ? 'danger' : 'success'}">${data.nows_attendance.temperature} °c</button>
                        @endif
                        </div>
                                        </li>
                                                    `);
                        // });

                        callEmailWorker();
                    }


                }
            )
            .catch(err => console.error(err));
    }

    function callEmailWorker(){
        fetch('{{ route('frontend.emailWorker') }}')
            .then(response => {
            })
            .catch(error => {
            });
        return true;
    }

    document.getElementById("attendance_button_in").addEventListener("click", function () {
        submitForm('in')
    });
    document.getElementById("attendance_button_out").addEventListener("click", function () {
        submitForm('out')
    });
    document.getElementById("attendance_form").addEventListener("submit", function () {
        submitForm('')
    });
    document.getElementById("check_staff_info").addEventListener("submit", checkStaffInfo);
</script>

<script>

    function highlightARAInput() {
        document.getElementById('staff_ara_id').select();
    }

    document.getElementById('staff_ara_id').addEventListener('click', highlightARAInput);


</script>
</body>
</html>
