@extends('frontend.layouts.app')

@section('title', app_name() . ' | 联系我们')

@section('content')
    <div class="row">

        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading fix"><big>{{ trans('labels.frontend.contact.box_title') }}</big></div>

                <div class="panel-body">

                    {{ Form::open(['route' => 'frontend.contact.send', 'class' => 'form-horizontal']) }}

                    <div class="form-group">
                        {{ Form::label('name', trans('validation.attributes.frontend.name'), ['class' => 'col-md-12']) }}
                        <div class="col-md-12">
                            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.name')]) }}
                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <div class="form-group">
                        {{ Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-md-12']) }}
                        <div class="col-md-12">
                            {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <div class="form-group">
                        {{ Form::label('phone', trans('validation.attributes.frontend.phone'), ['class' => 'col-md-12']) }}
                        <div class="col-md-12">
                            {{ Form::text('phone', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.phone')]) }}
                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <div class="form-group">
                        {{ Form::label('message', trans('validation.attributes.frontend.message'), ['class' => 'col-md-12']) }}
                        <div class="col-md-12">
                            {{ Form::textarea('message', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.message')]) }}
                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    <div class="form-group">
                        <div class="col-md-12">
                            {{ Form::submit(trans('labels.frontend.contact.button'), ['class' => 'btn btn-primary pull-right', 'style'=>'background-color:#b6a98b;border:none;']) }}
                        </div><!--col-md-6-->
                    </div><!--form-group-->

                    {{ Form::close() }}
                </div><!-- panel body -->

            </div><!-- panel -->

        </div><!-- col-md-8 -->

    </div><!-- row -->
@endsection