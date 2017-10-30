<div class="panel-body">
    @foreach( $user->notifications as $notification)
        {{--根据notification的type选则不同的试图文件--}}
        @include('notifications.'.snake_case(class_basename($notification->type)))
    @endforeach
</div>