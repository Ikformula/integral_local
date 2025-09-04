@extends('frontend.layouts.app')

@section('title', 'CRM' )

@push('after-styles')
    <style>
        *, *:before, *:after {
            box-sizing: border-box;
        }

        .tags-container {
            display: flex;
            flex-flow: row wrap;
            margin-bottom: 15px;
            width: 100%;
            min-height: 34px;
            padding: 2px 5px;
            font-size: 14px;
            line-height: 1.6;
            background-color: transparent;
            border: 1px solid #ccc;
            border-radius: 1px;
            overflow: hidden;
            word-wrap: break-word;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        input.tag-input {
            flex: 3;
            border: 0;
            outline: 0;
        }

        .tag {
            position: relative;
            margin: 2px 6px 2px 0;
            padding: 1px 20px 1px 8px;
            font-size: inherit;
            font-weight: 400;
            text-align: center;
            color: #fff;
            background-color: #34000D;
            border-radius: 3px;
            transition: background-color 0.3s ease;
            cursor: default;
        }

        .tag:first-child {
            margin-left: 0;
        }

        .tag--marked {
            background-color: #6fadd7;
        }

        .tag--exists {
            background-color: #EDB5A1;
            -webkit-animation: shake 1s linear;
            animation: shake 1s linear;
        }

        .tag__name {
            margin-right: 3px;
        }

        .tag__remove {
            position: absolute;
            right: 0;
            bottom: 0;
            width: 20px;
            height: 100%;
            padding: 0 5px;
            font-size: 16px;
            font-weight: 400;
            transition: opacity 0.3s ease;
            opacity: 0.5;
            cursor: pointer;
            border: 0;
            background-color: transparent;
            color: #fff;
            line-height: 1;
        }

        .tag__remove:hover {
            opacity: 1;
        }

        .tag__remove:focus {
            outline: 5px auto #fff;
        }

        @-webkit-keyframes shake {
            0%, 100% {
                transform: translate3d(0, 0, 0);
            }
            10%, 30%, 50%, 70%, 90% {
                transform: translate3d(-5px, 0, 0);
            }
            20%, 40%, 60%, 80% {
                transform: translate3d(5px, 0, 0);
            }
        }

        @keyframes shake {
            0%, 100% {
                transform: translate3d(0, 0, 0);
            }
            10%, 30%, 50%, 70%, 90% {
                transform: translate3d(-5px, 0, 0);
            }
            20%, 40%, 60%, 80% {
                transform: translate3d(5px, 0, 0);
            }
        }
    </style>
@endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Add a Call Log</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('frontend.call_center.store.log') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">

                                        <div class="mb-3">
                                            <label class="form-label" for="passengerName">Passenger Name</label>
                                            <input class="form-control" name="passenger_name" id="passengerName"
                                                   type="text" placeholder="Passenger Name"
                                                   data-sb-validations="required"/>
                                            <div class="invalid-feedback" data-sb-feedback="passengerName:required">
                                                Passenger Name is required.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="passengersMobileNumber">Passenger's
                                                Mobile Number</label>
                                            <input class="form-control" name="passenger_mobile_number"
                                                   id="passengersMobileNumber" type="text"
                                                   placeholder="Passenger's Mobile Number" data-sb-validations=""/>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="passengersEmailAddress">Passenger's
                                                Email Address</label>
                                            <input class="form-control" name="passenger_email_address"
                                                   id="passengersEmailAddress" type="text"
                                                   placeholder="Passenger's Email Address" data-sb-validations=""/>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="passengersLocation">Passenger's
                                                Location</label>
                                            <textarea class="form-control" name="passenger_location"
                                                      id="passengersLocation" type="text"
                                                      placeholder="Passenger's Location"
                                                      data-sb-validations=""></textarea>
                                        </div>

                                    </div>
                                    <div class="col-md-4">

                                        <div class="mb-3">
                                            <label class="form-label" for="flightRoute">Flight Route</label>
                                            <input class="form-control" name="flight_route" id="flightRoute" type="text"
                                                   placeholder="Flight Route" data-sb-validations="required"/>
                                            <div class="invalid-feedback" data-sb-feedback="flightRoute:required">Flight
                                                Route is required.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="flightTime">Flight Time</label>
                                            <input class="form-control" name="flight_time" id="flightTime"
                                                   type="datetime-local" placeholder="Flight Time"
                                                   data-sb-validations=""/>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="pnr">PNR</label>
                                            <input class="form-control" name="pnr" id="pnr" type="text"
                                                   placeholder="PNR" data-sb-validations="required"/>
                                            <div class="invalid-feedback" data-sb-feedback="pnr:required">PNR is
                                                required.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="classOfBooking">Class of Booking</label>
                                            <input class="form-control" name="class_of_booking" id="classOfBooking"
                                                   type="text" placeholder="Class of Booking" data-sb-validations=""/>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="ticketFare">Ticket Fare</label>
                                            <input class="form-control" name="ticket_fare" id="ticketFare" type="text"
                                                   placeholder="Ticket Fare" data-sb-validations=""/>
                                        </div>


                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="callPurpose">Call Purpose</label>
                                            <select class="form-select form-control" name="call_purpose"
                                                    id="callPurpose" aria-label="Call Purpose">
                                                <option value="Call drop">Call drop</option>
                                                <option value="Refund">Refund</option>
                                                <option value="Enquiry">Enquiry</option>
                                                <option value="OTE">OTE</option>
                                                <option value="Flight disruption">Flight disruption</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="typeOfCall">Type of Call</label>
                                            <select class="form-select form-control" name="type_of_call" id="typeOfCall"
                                                    aria-label="Type of Call">
                                                 <option value="issuance">issuance</option>
                                                <option value="Reservation">Reservation</option>
                                                <option value="Modification">Modification</option>
                                                <option value="Check-in baggage">Check-in baggage</option>
                                                <option value="Flight schedule">Flight schedule</option>
                                                <option value="Special handling">Special handling</option>
                                                <option value="frequent flyer">frequent flyer</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="footnote">Footnote</label>
                                            <textarea class="form-control" name="footnote" id="footnote" type="text"
                                                      placeholder="Footnote" data-sb-validations="" rows="2"></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="arikPhoneNumber">Arik Phone Number</label>
                                            <input class="form-control" name="receiving_phone_number"
                                                   id="arikPhoneNumber" type="text" placeholder="Arik Phone Number"
                                                   value="{{ session()->has('receiving_phone_number') ? session('receiving_phone_number') : '' }}"
                                                   data-sb-validations="required" required/>
                                            <div class="invalid-feedback" data-sb-feedback="arikPhoneNumber:required">
                                                Arik Phone Number is required.
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="exist-values">Supervisor(s) on duty</label>
                                            <input name="supervisors" id="exist-values" class="tagged form-control"
                                                   data-removeBtn="true" value="{{ session('supervisors') }}" style="height: 5rem;">
                                        </div>

                                    </div>
                                </div>


                                <div class="d-grid">
                                    <button class="btn bg-navy btn-lg btn-block" id="submitButton" type="submit">
                                        Submit
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div><!--/. container-fluid -->


    </section>
@endsection

@push('after-scripts')
    <script>
        // https://github.com/k-ivan/Tags
        (function () {

            'use strict';

            // Helpers
            function $$(selectors, context) {
                return (typeof selectors === 'string') ? (context || document).querySelectorAll(selectors) : [selectors];
            }

            function $(selector, context) {
                return (typeof selector === 'string') ? (context || document).querySelector(selector) : selector;
            }

            function create(tag, attr) {
                var element = document.createElement(tag);
                if (attr) {
                    for (var name in attr) {
                        if (element[name] !== undefined) {
                            element[name] = attr[name];
                        }
                    }
                }
                return element;
            }

            function whichTransitionEnd() {
                var root = document.documentElement;
                var transitions = {
                    'transition': 'transitionend',
                    'WebkitTransition': 'webkitTransitionEnd',
                    'MozTransition': 'mozTransitionEnd',
                    'OTransition': 'oTransitionEnd otransitionend'
                };

                for (var t in transitions) {
                    if (root.style[t] !== undefined) {
                        return transitions[t];
                    }
                }
                return false;
            }

            function oneListener(el, type, fn, capture) {
                capture = capture || false;
                el.addEventListener(type, function handler(e) {
                    fn.call(this, e);
                    el.removeEventListener(e.type, handler, capture)
                }, capture);
            }

            function hasClass(cls, el) {
                return new RegExp('(^|\\s+)' + cls + '(\\s+|$)').test(el.className);
            }

            function addClass(cls, el) {
                if (!hasClass(cls, el))
                    return el.className += (el.className === '') ? cls : ' ' + cls;
            }

            function removeClass(cls, el) {
                el.className = el.className.replace(new RegExp('(^|\\s+)' + cls + '(\\s+|$)'), '');
            }

            function toggleClass(cls, el) {
                (!hasClass(cls, el)) ? addClass(cls, el) : removeClass(cls, el);
            }

            function Tags(tag) {

                var el = $(tag);

                if (el.instance) return;
                el.instance = this;

                var type = el.type;
                var transitionEnd = whichTransitionEnd();

                var tagsArray = [];
                var KEYS = {
                    ENTER: 13,
                    COMMA: 188,
                    BACK: 8
                };
                var isPressed = false;

                var timer;
                var wrap;
                var field;

                function init() {

                    // create and add wrapper
                    wrap = create('div', {
                        'className': 'tags-container',
                    });
                    field = create('input', {
                        'type': 'text',
                        'className': 'tag-input',
                        'placeholder': el.placeholder || ''
                    });

                    wrap.appendChild(field);

                    if (el.value.trim() !== '') {
                        hasTags();
                    }

                    el.type = 'hidden';
                    el.parentNode.insertBefore(wrap, el.nextSibling);

                    wrap.addEventListener('click', btnRemove, false);
                    wrap.addEventListener('keydown', keyHandler, false);
                    wrap.addEventListener('keyup', backHandler, false);
                }

                function hasTags() {
                    var arr = el.value.trim().split(',');
                    arr.forEach(function (item) {
                        item = item.trim();
                        if (~tagsArray.indexOf(item)) {
                            return;
                        }
                        var tag = createTag(item);
                        tagsArray.push(item);
                        wrap.insertBefore(tag, field);
                    });
                }

                function createTag(name) {
                    var tag = create('div', {
                        'className': 'tag',
                        'innerHTML': '<span class="tag__name">' + name + '</span>' +
                            '<button class="tag__remove">&times;</button>'
                    });
//       var tagName = create('span', {
//         'className': 'tag__name',
//         'textContent': name
//       });
//       var delBtn = create('button', {
//         'className': 'tag__remove',
//         'innerHTML': '&times;'
//       });

//       tag.appendChild(tagName);
//       tag.appendChild(delBtn);
                    return tag;
                }

                function btnRemove(e) {
                    e.preventDefault();
                    if (e.target.className === 'tag__remove') {
                        var tag = e.target.parentNode;
                        var name = $('.tag__name', tag);
                        wrap.removeChild(tag);
                        tagsArray.splice(tagsArray.indexOf(name.textContent), 1);
                        el.value = tagsArray.join(',')
                    }
                    field.focus();
                }

                function keyHandler(e) {

                    if (e.target.tagName === 'INPUT' && e.target.className === 'tag-input') {

                        var target = e.target;
                        var code = e.which || e.keyCode;

                        if (field.previousSibling && code !== KEYS.BACK) {
                            removeClass('tag--marked', field.previousSibling);
                        }

                        var name = target.value.trim();

                        // if(code === KEYS.ENTER || code === KEYS.COMMA) {
                        if (code === KEYS.ENTER) {

                            target.blur();

                            addTag(name);

                            if (timer) clearTimeout(timer);
                            timer = setTimeout(function () {
                                target.focus();
                            }, 10);
                        } else if (code === KEYS.BACK) {
                            if (e.target.value === '' && !isPressed) {
                                isPressed = true;
                                removeTag();
                            }
                        }
                    }
                }

                function backHandler(e) {
                    isPressed = false;
                }

                function addTag(name) {

                    // delete comma if comma exists
                    name = name.toString().replace(/,/g, '').trim();

                    if (name === '') return field.value = '';

                    if (~tagsArray.indexOf(name)) {

                        var exist = $$('.tag', wrap);

                        Array.prototype.forEach.call(exist, function (tag) {
                            if (tag.firstChild.textContent === name) {

                                addClass('tag--exists', tag);

                                if (transitionEnd) {
                                    oneListener(tag, transitionEnd, function () {
                                        removeClass('tag--exists', tag);
                                    });
                                } else {
                                    removeClass('tag--exists', tag);
                                }


                            }

                        });

                        return field.value = '';
                    }

                    var tag = createTag(name);
                    wrap.insertBefore(tag, field);
                    tagsArray.push(name);
                    field.value = '';
                    el.value += (el.value === '') ? name : ',' + name;
                }

                function removeTag() {
                    if (tagsArray.length === 0) return;

                    var tags = $$('.tag', wrap);
                    var tag = tags[tags.length - 1];

                    if (!hasClass('tag--marked', tag)) {
                        addClass('tag--marked', tag);
                        return;
                    }

                    tagsArray.pop();

                    wrap.removeChild(tag);

                    el.value = tagsArray.join(',');
                }

                init();

                /* Public API */

                this.getTags = function () {
                    return tagsArray;
                }

                this.clearTags = function () {
                    if (!el.instance) return;
                    tagsArray.length = 0;
                    el.value = '';
                    wrap.innerHTML = '';
                    wrap.appendChild(field);
                }

                this.addTags = function (name) {
                    if (!el.instance) return;
                    if (Array.isArray(name)) {
                        for (var i = 0, len = name.length; i < len; i++) {
                            addTag(name[i])
                        }
                    } else {
                        addTag(name);
                    }
                    return tagsArray;
                }

                this.destroy = function () {
                    if (!el.instance) return;

                    wrap.removeEventListener('click', btnRemove, false);
                    wrap.removeEventListener('keydown', keyHandler, false);
                    wrap.removeEventListener('keyup', keyHandler, false);

                    wrap.parentNode.removeChild(wrap);

                    tagsArray = null;
                    timer = null;
                    wrap = null;
                    field = null;
                    transitionEnd = null;

                    delete el.instance;
                    el.type = type;
                }
            }

            window.Tags = Tags;

        })();

        // Use
        var tags = new Tags('.tagged');

        document.getElementById('get').addEventListener('click', function (e) {
            e.preventDefault();
            alert(tags.getTags());
        });
        document.getElementById('clear').addEventListener('click', function (e) {
            e.preventDefault();
            tags.clearTags();
        });
        document.getElementById('add').addEventListener('click', function (e) {
            e.preventDefault();
            tags.addTags('New');
        });
        document.getElementById('addArr').addEventListener('click', function (e) {
            e.preventDefault();
            tags.addTags(['Steam Machines', 'Nintendo Wii U', 'Shield Portable']);
        });
        document.getElementById('destroy').addEventListener('click', function (e) {
            e.preventDefault();
            if (this.textContent === 'destroy') {
                tags.destroy();
                this.textContent = 'reinit';
            } else {
                this.textContent = 'destroy';
                tags = new Tags('.tagged');
            }

        });

    </script>

    <script>
        // count seconds call lasted
        document.addEventListener('onchange')
    </script>
@endpush
