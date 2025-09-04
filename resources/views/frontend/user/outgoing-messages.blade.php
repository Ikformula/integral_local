@extends('frontend.layouts.app')

@push('after-styles')
    {{--    @include('includes.partials._datatables-css')--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
@endpush

@section('title', 'Outgoing Emails')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
<div class="table-responsive">
    <table class="table table-bordered table-sm table-striped table-striped-columns w-100">
       <thead>
       <tr>
           <th>S/N</th>
           <th>ID</th>
           <th>User</th>
           <th>Email Sent</th>
           <th>Created</th>
           <th>Subject</th>
           <th>Body</th>
           <th>To</th>
           <th>Cc</th>
           <th>BCc</th>
       </tr>
       </thead>

        <tbody>
        @foreach($message_recipients as $recipient)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $recipient->id }}</td>
                <td>{{ $recipient->user ? $recipient->user->full_name : '' }}</td>
                <td class="{{ isset($recipient->email_sent_at) ? '' : 'bg-warning' }}">{{ isset($recipient->email_sent_at) ? $recipient->email_sent_at->toDayDateTimeString() : 'Not Sent Yet' }}</td>
                <td>{{ $recipient->created_at->toDayDateTimeString() }}</td>

                @php
                    $msg = $recipient->message;
                    $message = json_decode($msg->payload);

                @endphp
                <td>{{ $message->subject }}</td>
                <td>
                    <strong>{{ $message->greeting ?? '' }}</strong><br>
                    @foreach($message->line as $line)
                        <p>{{ $line }}</p>
                    @endforeach

                    @if(isset($message->formatted_line) && count($message->formatted_line))
                        @foreach($message->formatted_line as $line)
                            <p>{!! $line !!}</p>
                        @endforeach
                    @endif
                    @if(isset($message->action_url ))
                        <a href="{{ $message->action_url }}" class="btn btn-primary" target="_blank">{{ $message->action_text }}</a>
                    @endif
                </td>
                <td>
                    @if(isset($message->to))
                        @if(is_array($message->to) && count($message->to))
                            @foreach($message->to as $to)
                                {{ $to }}<br>
                            @endforeach
                        @else
                            {{ $message->to }} - {{ isset($message->to_name) ? $message->to : '' }}
                        @endif
                    @endif
                </td>
                <td>
                    @if(isset($message->cc))
                        @if(is_array($message->cc) && count($message->cc))
                            @foreach($message->cc as $cc)
                                {{ $cc }}<br>
                            @endforeach
                        @else
                            {{ $message->cc }} - {{ isset($message->cc_name) ? $message->cc : '' }}
                        @endif
                    @endif
                </td>
                <td>
                    @if(isset($message->bcc))
                        @if(is_array($message->bcc) && count($message->bcc))
                            @foreach($message->bcc as $bcc)
                                {{ $bcc }}<br>
                            @endforeach
                        @else
                            {{ $message->bcc }} - {{ isset($message->bcc_name) ? $message->bcc : '' }}
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
                    <div class="form-group">
                        {{ $message_recipients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    <script src="{{ asset('js/html-table-xlsx.js') }}"></script>

    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/dataTables.searchBuilder.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/searchBuilder.dataTables.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            var table = new DataTable('.table', {
                "paging": false,
                // pageLength: 50,
                // order:[[0, 'desc']],
                layout: {
                    top: {
                        searchBuilder: {
                            // columns: [6],
                            @if(isset($_GET->days_left))
                            preDefined: {
                                {{--criteria: [--}}
                                {{--    {--}}
                                {{--        data: 'Days Left to End',--}}
                                {{--        condition: '=',--}}
                                {{--        value: [{{ $_GET->days_left }}]--}}
                                {{--    }--}}
                                {{--]--}}
                            }
                            @endif
                        }
                    },
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                }
            });
        });
    </script>
@endpush
