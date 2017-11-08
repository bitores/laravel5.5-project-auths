Todo list

https://segmentfault.com/a/1190000005001064

手机 找回 密码

Laravel 扩展推荐: DbExporter 逆向 Migration 和 db:seed

类似的扩展包还有:
https://github.com/Xethron/migrations-generator
https://github.com/orangehill/iseed

通知被存放到数据表之后，需要在被通知实体中有一个便捷的方式来访问它们。Laravel默认提供的App\User模型引入的Illuminate\Notifications\Notifiabletrait包含了返回实体对应通知的Eloquent关联关系方法notifications，要获取这些通知，可以像访问其它Eloquent关联关系一样访问该关联方法，默认情况下，通知按照created_at时间戳排序：

$user = App\User::find(1);

foreach ($user->notifications as $notification) {
    echo $notification->type;
}
如果你只想获取未读消息，可使用关联关系unreadNotifications，同样，这些通知也按照created_at时间戳排序：

$user = App\User::find(1);

foreach ($user->unreadNotifications as $notification) {
    echo $notification->type;
}
注：要想从JavaScript客户端访问通知，需要在应用中定义一个通知控制器为指定被通知实体（比如当前用户）返回通知，然后从JavaScript客户端发送一个HTTP请求到控制器对应URI。

标记通知为已读

一般情况下，我们会将用户浏览过的通知标记为已读，Illuminate\Notifications\Notifiabletrait提供了一个markAsRead方法，用于更新对应通知数据库纪录上的read_at字段：

$user = App\User::find(1);

foreach ($user->unreadNotifications as $notification) {
    $notification->markAsRead();
}
如果觉得循环便利每个通知太麻烦，可以直接在通知集合上调用markAsRead方法：

$user->unreadNotifications->markAsRead();
还可以使用批量更新方式标记通知为已读，无需先从数据库获取通知：

$user = App\User::find(1);

$user->unreadNotifications()->update(['read_at' => Carbon::now()]);
当然，你也可以通过delete方法从数据库中移除这些通知：

$user->notifications()->delete();


class User extends Model{
    use Notifable;
}
这样我们的模型实体就可以处理消息通知了：

$user = User::find(1);

$user->getNotifications($limit = null, $paginate = null, $order = 'desc');
$user->getNotificationsNotRead($limit = null, $paginate = null, $order = 'desc');
$user->getLastNotification();
$user->countNotificationsNotRead($category = null);
$user->readAllNotifications();