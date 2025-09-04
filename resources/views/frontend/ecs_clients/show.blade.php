{{-- filepath: c:\laragon\www\arik_web_portals\resources\views\frontend\ecs_clients\show.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Client Details')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Client Details</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">Name</dt>
                            <dd class="col-sm-8">{{ $ecs_client->name }}</dd>

                            <dt class="col-sm-4">Current Balance</dt>
                            <dd class="col-sm-8">{{ checkIntNumber($ecs_client->current_balance) }}</dd>

                            <dt class="col-sm-4">Service Charge Amount</dt>
                            <dd class="col-sm-8">{{ checkIntNumber($ecs_client->service_charge_amount) }}</dd>

                            <dt class="col-sm-4">Deal Code</dt>
                            <dd class="col-sm-8">{{ $ecs_client->deal_code }}</dd>

                            <dt class="col-sm-4">Account Type</dt>
                            <dd class="col-sm-8">{{ $ecs_client->account_type }}</dd>

                            <dt class="col-sm-4">Applicable Taxes</dt>
                            <dd class="col-sm-8">
                                @if(!$ecs_client->taxes())
                                    None
                                @else
                                    @foreach($ecs_client->taxes() as $tax)
                                    {{ $tax }},
                                    @endforeach
                                @endif
                            </dd>
                        </dl>

                        @if(!$logged_in_user->isEcsClient) <a href="{{ route('frontend.ecs_clients.index') }}"
                                                              class="btn btn-secondary">Back</a> @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Client Account summaries</h4>

                        <div class="card-tools">
                            @if(!$logged_in_user->isEcsClient)
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#addSummaryModalId">
                                    + Add A Summary
                                </button>
                            @endif
                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                    class="fas fa-expand"></i>
                            </button>
                        </div>

                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            @include('frontend.ecs_client_account_summaries._partials._table-list', ['items' => $ecs_client->summaries])
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Client Users</h4>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addUserModalId">
                                + Add A Client User
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i
                                    class="fas fa-expand"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Added</th>
                                    <th>Updated</th>
                                    @if(!$logged_in_user->isEcsClient)
                                        <th>Actions</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($ecs_client->clientUsers as $client_user)
                                    @php
                                        $user = $client_user->user;
                                    @endphp
                                    <tr>
                                        <td scope="row">{{ $loop->iteration }}</td>
                                        <td>{{ $user->full_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $client_user->position }}</td>
                                        <td>{{ $client_user->created_at->diffForHumans() }}</td>
                                        <td>{{ $client_user->updated_at->diffForHumans() }}</td>
                                        @if(!$logged_in_user->isEcsClient)
                                            <td>
                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                                        data-target="#editModal-{{ $client_user->id }}">
                                                    Edit
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="editModal-{{ $client_user->id }}"
                                                     tabindex="-1" role="dialog"
                                                     aria-labelledby="modelTitleId" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="editModalTitleId"></h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @php $user = $client_user->user; @endphp
                                                                {{ html()->modelForm($user, 'PATCH', route('admin.auth.user.update', $user->id))->class('form-horizontal')->open() }}
                                                                <input type="hidden" name="client_user_id"
                                                                       value="{{ $client_user->id }}">
                                                                <input class="switch-input" type="hidden" name="roles[]"
                                                                       id="role-2" value="user">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-5">
                                                                                <h4 class="card-title mb-0">
                                                                                    <small
                                                                                        class="text-muted">@lang('labels.backend.access.users.edit')</small>
                                                                                </h4>
                                                                            </div><!--col-->
                                                                        </div><!--row-->

                                                                        <hr>

                                                                        <div class="row mt-4 mb-4">
                                                                            <div class="col">
                                                                                <div class="form-group row">
                                                                                    {{ html()->label(__('validation.attributes.backend.access.users.first_name'))->class('col-md-2 form-control-label')->for('first_name') }}

                                                                                    <div class="col-md-10">
                                                                                        {{ html()->text('first_name')
                                                                                            ->class('form-control')
                                                                                            ->placeholder(__('validation.attributes.backend.access.users.first_name'))
                                                                                            ->attribute('maxlength', 191)
                                                                                            ->required() }}
                                                                                    </div><!--col-->
                                                                                </div><!--form-group-->

                                                                                <div class="form-group row">
                                                                                    {{ html()->label(__('validation.attributes.backend.access.users.last_name'))->class('col-md-2 form-control-label')->for('last_name') }}

                                                                                    <div class="col-md-10">
                                                                                        {{ html()->text('last_name')
                                                                                            ->class('form-control')
                                                                                            ->placeholder(__('validation.attributes.backend.access.users.last_name'))
                                                                                            ->attribute('maxlength', 191)
                                                                                            ->required() }}
                                                                                    </div><!--col-->
                                                                                </div><!--form-group-->

                                                                                <div class="form-group row">
                                                                                    {{ html()->label(__('validation.attributes.backend.access.users.email'))->class('col-md-2 form-control-label')->for('email') }}

                                                                                    <div class="col-md-10">
                                                                                        {{ html()->email('email')
                                                                                            ->class('form-control')
                                                                                            ->placeholder(__('validation.attributes.backend.access.users.email'))
                                                                                            ->attribute('maxlength', 191)
                                                                                            ->required() }}
                                                                                    </div><!--col-->
                                                                                </div><!--form-group-->

                                                                                <div class="form-group row">
                                                                                    {{ html()->label(__('Position'))->class('col-md-2 form-control-label')->for('position') }}

                                                                                    <div class="col-md-10">
                                                                                        {{ html()->text('position')
                                                                                            ->class('form-control')
                                                                                            ->placeholder('Secretary')
                                                                                            ->attribute('maxlength', 191)
                                                                                            ->required()
                                                                                             ->value($client_user->position) }}
                                                                                    </div><!--col-->
                                                                                </div><!--form-group-->

                                                                            </div><!--col-->
                                                                        </div><!--row-->

                                                                        <div class="card-footer">
                                                                            <div class="row">
                                                                                <div class="col">
                                                                                    <button type="button"
                                                                                            class="btn btn-secondary btn-sm"
                                                                                            data-dismiss="modal">Close
                                                                                    </button>
                                                                                </div><!--col-->

                                                                                <div class="col text-right">
                                                                                    {{ form_submit(__('buttons.general.crud.update')) }}
                                                                                </div><!--row-->
                                                                            </div><!--row-->
                                                                        </div><!--card-footer-->
                                                                    </div><!--card-->
                                                                    {{ html()->closeModelForm() }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <form action="{{ route('admin.auth.user.destroy', $user) }}"
                                                      method="POST" style="display: inline;"
                                                      onsubmit="return confirm('@lang('strings.backend.general.are_you_sure')')">
                                                    <input type="hidden" name="client_user_id"
                                                           value="{{ $client_user->id }}">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="addUserModalId" tabindex="-1" role="dialog" aria-labelledby="addUserModalTitleId"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addUserModalTitleId"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        {{ html()->form('POST', route('admin.auth.user.store'))->class('form-horizontal')->open() }}
                        <input type="hidden" name="client_id" value="{{ $ecs_client->id }}">


                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <h4 class="card-title mb-0">@lang('labels.backend.access.users.create')
                                            for {{ $ecs_client->name }}
                                        </h4>
                                    </div><!--col-->
                                </div><!--row-->

                                <hr>

                                <div class="row mt-4 mb-4">
                                    <div class="col">
                                        <div class="form-group row">
                                            {{ html()->label(__('validation.attributes.backend.access.users.first_name'))->class('col-md-2 form-control-label')->for('first_name') }}

                                            <div class="col-md-10">
                                                {{ html()->text('first_name')
                                                    ->class('form-control')
                                                    ->placeholder(__('validation.attributes.backend.access.users.first_name'))
                                                    ->attribute('maxlength', 191)
                                                    ->required()
                                                    ->autofocus() }}
                                            </div><!--col-->
                                        </div><!--form-group-->

                                        <div class="form-group row">
                                            {{ html()->label(__('validation.attributes.backend.access.users.last_name'))->class('col-md-2 form-control-label')->for('last_name') }}

                                            <div class="col-md-10">
                                                {{ html()->text('last_name')
                                                    ->class('form-control')
                                                    ->placeholder(__('validation.attributes.backend.access.users.last_name'))
                                                    ->attribute('maxlength', 191)
                                                    ->required() }}
                                            </div><!--col-->
                                        </div><!--form-group-->

                                        <div class="form-group row">
                                            {{ html()->label(__('validation.attributes.backend.access.users.email'))->class('col-md-2 form-control-label')->for('email') }}

                                            <div class="col-md-10">
                                                {{ html()->email('email')
                                                    ->class('form-control')
                                                    ->placeholder(__('validation.attributes.backend.access.users.email'))
                                                    ->attribute('maxlength', 191)
                                                    ->required() }}
                                            </div><!--col-->
                                        </div><!--form-group-->

                                        @include('includes.partials._hidden-user-reg-password-field')
                                        <div class="form-group row">
                                            {{ html()->label(__('Position'))->class('col-md-2 form-control-label')->for('position') }}

                                            <div class="col-md-10">
                                                {{ html()->text('position')
                                                    ->class('form-control')
                                                    ->placeholder('Secretary')
                                                    ->attribute('maxlength', 191)
                                                    ->required() }}
                                            </div><!--col-->
                                        </div><!--form-group-->


                                        <input class="switch-input" type="hidden" name="roles[]" id="role-2"
                                               value="user">
                                    </div><!--col-->
                                </div><!--row-->
                            </div><!--card-body-->

                            <div class="card-footer clearfix">
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                            Close
                                        </button>
                                    </div><!--col-->

                                    <div class="col text-right">
                                        {{ form_submit(__('buttons.general.crud.create')) }}
                                    </div><!--col-->
                                </div><!--row-->
                            </div><!--card-footer-->
                        </div><!--card-->
                        {{ html()->form()->close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSummaryModalId" tabindex="-1" role="dialog" aria-labelledby="addSummaryModalTitleId"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addSummaryModalTitleId"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('frontend.ecs_client_account_summaries._partials._create-form', ['client' => $ecs_client])
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#exampleModal').on('show.bs.modal', event => {
            var button = $(event.relatedTarget);
            var modal = $(this);
            // Use above variables to manipulate the DOM

        });
    </script>
@endsection
