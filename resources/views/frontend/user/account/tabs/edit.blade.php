{{ Form::model($logged_in_user, ['route' => 'frontend.user.profile.update', 'class' => 'form-horizontal', 'method' => 'PATCH']) }}

    <div class="form-group">
        {{ Form::label('nickname', trans('validation.attributes.frontend.nickname'),
        ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-6">
            {{ Form::text('nickname', null,
            ['class' => 'form-control', 'required' => 'required', 'autofocus' => 'autofocus', 'maxlength' => '191', 'placeholder' => trans('validation.attributes.frontend.nickname')]) }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('user_name', trans('validation.attributes.frontend.user_name'),
        ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-6">
            {{ Form::text('user_name', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '191', 'placeholder' => trans('validation.attributes.frontend.user_name')]) }}
        </div>
    </div>

    @if ($logged_in_user->canChangeEmail())
        <div class="form-group">
            {{ Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-md-4 control-label']) }}
            <div class="col-md-6">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> {{  trans('strings.frontend.user.change_email_notice') }}
                </div>

                {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '191', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
            </div>
        </div>
    @endif

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            {{ Form::submit(trans('labels.general.buttons.update'), ['class' => 'btn btn-primary', 'id' => 'update-profile']) }}
        </div>
    </div>

{{ Form::close() }}