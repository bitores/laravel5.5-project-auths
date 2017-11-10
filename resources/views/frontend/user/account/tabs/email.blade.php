{{ Form::open(['route' => 'frontend.user.bind.email', 'class' => 'form-horizontal']) }}

<div class="form-group">
    {{ Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-md-4 control-label']) }}
    <div class="col-md-6">
        {{ Form::email('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
    </div><!--col-md-6-->
</div><!--form-group-->

<div class="form-group">
    <div class="col-md-6 col-md-offset-4">
        {{ Form::submit('发送邮箱绑定确认邮件', ['class' => 'btn btn-primary']) }}
    </div><!--col-md-6-->
</div><!--form-group-->

{{ Form::close() }}