@extends('backend.layouts.app')

@section('title', __('labels.backend.access.users.management') . ' | ' . __('labels.backend.access.users.edit'))

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
    {{ html()->modelForm($user, 'PATCH', route('admin.auth.user.update', $user->id))->class('form-horizontal')->open() }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        @lang('labels.backend.access.users.management')
                        <small class="text-muted">@lang('labels.backend.access.users.edit')</small>
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

                    <div class="row">
                        {{--                        <div class="col-md-12">--}}
                        {{ html()->label('Abilities')->class('col-md-2 form-control-label') }}


                        @if($roles->count())
                            <div class="col-md-12">
                                @lang('labels.backend.access.users.table.roles')
                                @foreach($roles as $role)
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="checkbox d-flex align-items-center">
                                                {{ html()->label(
                                                        html()->checkbox('roles[]', in_array($role->name, $userRoles), $role->name)
                                                                ->class('switch-input')
                                                                ->id('role-'.$role->id)
                                                        . '<span class="switch-slider" data-checked="on" data-unchecked="off"></span>')
                                                    ->class('switch switch-label switch-pill switch-primary mr-2')
                                                    ->for('role-'.$role->id) }}
                                                {{ html()->label(ucwords($role->name))->for('role-'.$role->id) }}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if($role->id != 1)
                                                @if($role->permissions->count())
                                                    <div class="row">
                                                        @foreach($role->permissions as $permission)
                                                            <div class="col-auto col-md-4 mb-1">
                                                                <i class="fas fa-dot-circle"></i> {{ ucwords($permission->name) }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    @lang('labels.general.none')
                                                @endif
                                            @else
                                                @lang('labels.backend.access.users.all_permissions')
                                            @endif
                                        </div>
                                    </div><!--card-->
                                @endforeach
                            </div>
                        @endif

                        @if($permissions->count())
                            <div class="col-md-12">

                                    <div class="row">
                                        <div class="col-md-12">
                                            @lang('labels.backend.access.users.table.permissions')
                                        </div>
                                    </div>
                                    {{--                                                        TODO: refactor this view/HTML --}}
                                <div class="row">
                                    @foreach($permissions as $permission)
                                        <div class="col-auto col-md-4 mb-1">
                                            <div class="checkbox d-flex align-items-center">
                                                {{ html()->label(
                                                        html()->checkbox('permissions[]', in_array($permission->name, $userPermissions), $permission->name)
                                                                ->class('switch-input')
                                                                ->id('permission-'.$permission->id)
                                                            . '<span class="switch-slider" data-checked="on" data-unchecked="off"></span>')
                                                        ->class('switch switch-label switch-pill switch-primary mr-2')
                                                    ->for('permission-'.$permission->id) }}
                                                {{ html()->label(ucwords($permission->name))->for('permission-'.$permission->id) }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{--                        </div><!--col-->--}}
                    </div><!--form-group-->
                </div><!--col-->
            </div><!--row-->

            @if($user->staff_member)
                <input type="hidden" name="staff_ara_id" value="{{ $user->staff_member->staff_ara_id }}">
            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="card border-1 border-light">
                      <div class="card-body">
                        <h4 class="card-title">Add as Co Presenter to Business Areas</h4>
                              @foreach($business_score_area as $businessArea)
                              <div class="form-group">
                                  <label class="checkbox-inline">
                                      <input type="checkbox" name="co_presentings[]" class="form-check-inline" @if(in_array($businessArea->id, $accessible_biz_areas_ids)) checked @endif id="co_presenting_{{ $businessArea->id }}" value="{{ $businessArea->id }}">{{ $businessArea->name }}
                                  </label>
                              </div>
                              @endforeach
                      </div>
                    </div>
                </div>
            </div>
        </div><!--card-body-->
        @endif

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.auth.user.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--row-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
    {{ html()->closeModelForm() }}
@endsection
