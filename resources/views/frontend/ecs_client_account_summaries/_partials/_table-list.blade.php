@push('after-styles')
    <style>
       .bg-refund {
           background-color: #ACE1AF;
       }
    </style>
@endpush
    @php
    $is_ecs_client = $logged_in_user->isEcsClient()->count();
@endphp
<table class="table table-bordered text-nowrap w-100">
    <thead>
    <tr>
        <th>#</th>
        @if(!$is_ecs_client)
            <th>Client</th>
            <th>Agent</th>
        @endif
        <th>Credit Amount</th>
        <th>Debit Amount</th>
        <th>Balance</th>
        <th>Ticket Number</th>
        <th>For Date</th>
        <th>Details</th>
        @if(1 < 0)
        <th>Status</th>
        <th>Status Change Date</th>
        @if($is_ecs_client)
        <th>Actions</th>
            @endif
            @endif
    </tr>
    </thead>
    <tbody>
        @php
        $bg_colours = [
            'REFUND' => '#ACE1AF',
            ''
        ];
        @endphp
    @foreach($items as $key => $item)
        <tr class="bg-{{ !isset($item->source) ? 'light' : '' }}" style="background-color: {{ array_key_exists($item->source, $bg_colours) ? $bg_colours[$item->source] : '' }}">
            <td>{{ $key + 1 }}</td>
            @if(!$is_ecs_client)
                <td>{{ $item->client_idRelation->name }}</td>
                <td>{{ $item->agent->full_name }}</td>
            @endif
            <td class="{{ $item->credit_amount != 0 ? 'text-success' : '' }}">{{ number_format($item->credit_amount) }}</td>
            <td class="{{ $item->debit_amount != 0 ? 'text-danger' : '' }}">{{ number_format($item->debit_amount) }}</td>
            <td>{{ number_format($item->balance) }}</td>
            <td>
            @php
                $ticket = $item->ticket();
            @endphp

                @if($ticket)
                    @if(!$is_ecs_client)
                <a href="{{ !is_null($ticket) ? route('frontend.ecs_flight_transactions.edit', $ticket->id) : 'javascript:void(0)' }}"  target="_blank">@if(!is_null($ticket)){{ $item->ticket_number }} <i class="fa-solid fa-up-right-from-square"></i>@endif</a>
                    @else
                        {{ $item->ticket_number }}
                    @endif
                @endif
            </td>
            <td>{{ $item->for_date->toDateString() }}</td>
            <td>{{ $item->details }}</td>

            @if(1 < 0)

            @php
                $status_arr = [
'colour' => 'secondary',
'text' => 'UNAPPROVED'
];
if($item->client_approved_at)
$status_arr = ['colour' => 'success', 'text' => 'APPROVED'];
if(is_null($item->client_approved_at) && $item->client_disputed_at)
$status_arr = ['colour' => 'warning', 'text' => 'DISPUTED'];
            @endphp
            <td><span class="badge badge-pill badge-{{ $status_arr['colour'] }}">{{ $status_arr['text'] }}</span>
                @if($status_arr['text'] == 'DISPUTED' && !$is_ecs_client)
                <button type="button" class="btn text-danger" data-toggle="modal"
                        data-target="#dispute-modal-{{ $item->id }}-Id">
                    <i class="fa fa-message"></i>
                </button>
                    @endif
            </td>
            <td>{{ $item->status_change_date }}</td>

            @if($is_ecs_client)
            <td>
                @if(is_null($item->client_approved_at))
                    <button type="button" class="btn btn-sm btn-{{ $status_arr['text'] == 'DISPUTED' ? 'outline-danger' : 'danger' }}" data-toggle="modal"
                            data-target="#dispute-modal-{{ $item->id }}-Id">Dispute{{ $status_arr['text'] == 'DISPUTED' ? ' Messages' : '' }}
                    </button>

                    <form action="{{ route('frontend.ecs_client_portal.approveTrx', $item->id) }}" method="POST"
                          class="d-inline" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    </form>
                @endif
            </td>
            @endif
            @endif
        </tr>




        <!-- Modal -->
            <div class="modal fade" id="dispute-modal-{{ $item->id }}-Id" tabindex="-1" role="dialog"
                 aria-labelledby="dispute-modal-{{ $item->id }}-TitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="dispute-modal-{{ $item->id }}-TitleId">Dispute Transaction</h4>
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-0">
                            <form action="{{ route('frontend.ecs_client_portal.disputeTrx', $item->id) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                            <div class="card">
                                <div class="card-body">
                                    <ul>
                                        <li>
                                            <strong>Credit: </strong> {{ number_format($item->credit_amount) }}
                                        </li>
                                        <li>
                                            <strong>Debit: </strong>{{ number_format($item->debit_amount) }}
                                        </li>
                                        <li><strong>Ticket Number: </strong>{{ $item->ticket_number }}
                                        </li>
                                        <li><strong>Details: </strong>{{ $item->details }}</li>
                                        <li><strong>Balance: </strong>{{ number_format($item->balance) }}</li>
                                    </ul>
                                </div>
                                <div class="card-footer card-comments">
                                    @if(is_array(json_decode($item->messages)))
                                        @foreach(json_decode($item->messages) as $message)
                                            <div class="card-comment">
                                                <!-- User image -->
                                                <img class="img-circle img-sm"
                                                     src="https://eu.ui-avatars.com/api/?name={{  $message->sender_name }}"
                                                     alt="{{  $message->sender_name }}'s Image">

                                                <div class="comment-text">
                <span class="username">
                  {{  $message->sender_name }}
                  <span
                      class="text-muted float-right">{{  \Carbon\Carbon::parse($message->sent_at)->diffForHumans() }}</span>
                </span><!-- /.username -->
                                                    {{ $message->message }}
                                                </div>
                                                <!-- /.comment-text -->
                                            </div>
                                        @endforeach
                                    @else
                                        <input type="hidden" name="fresh_dispute" value="1">
                                    @endif

                                </div>
                                <!-- /.card-footer -->
                                <div class="card-footer">
                                    <div class="img-push">
{{--                                        <input type="text" name="message" class="form-control form-control-sm"--}}
{{--                                               placeholder="Press enter to post comment">--}}
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="message" class="form-control form-control-sm" placeholder="Press enter to post comment">
                                            <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-paper-plane"></i></button>
                  </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-footer -->
                            </div>
                            <!-- /.card -->

        </form>
                        </div>
                    </div>
                </div>
            </div>
    @endforeach
    </tbody>
</table>

