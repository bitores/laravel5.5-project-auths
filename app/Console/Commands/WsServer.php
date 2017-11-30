<?php 
namespace App\Console\Commands;

use \Workerman\Worker;
use \GatewayWorker\BusinessWorker;
use \GatewayWorker\Gateway;
use \GatewayWorker\Register;
use \GatewayWorker\Lib\Gateway as WsSender;
use \Workerman\Autoloader;
use Illuminate\Console\Command;

// require_once __DIR__ . '/../../../vendor/autoload.php';

// #debug运行
// php artisan ws start
// #常驻后台运行
// php artisan ws start --d

class WsServer extends Command
{

	protected $webSocket;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ws {action} {--d}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'workerman server';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 检查OS
        if (strpos(strtolower(PHP_OS), 'win') === 0) {
            $this->error("Sorry, not support for windows.\n");
            exit;
        }

    	// 检查扩展
        if (!extension_loaded('pcntl')) {
            $this->error("Please install pcntl extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
            exit;
        }
        if (!extension_loaded('posix')) {
            $this->error("Please install posix extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
            exit;
        }


        //因为workerman需要带参数 所以得强制修改
        global $argv;
        $action = $this->argument('action');
        if (!in_array($action, ['start', 'stop', 'status'])) {
            $this->error('Error Arguments');
            exit;
        }
        $argv[0] = 'ws';
        $argv[1] = $action;
        $argv[2] = $this->option('d') ? '-d' : '';

        // BusinessWorker -- 必须是text协议
        $register = new Register('text://0.0.0.0:' . config('gateway.register.port'));

        // gateway 进程
        $gateway                  = new Gateway("Websocket://0.0.0.0:" . config('gateway.port'));
        // 设置名称，方便status时查看
        $gateway->name            = config('gateway.gateway.name');
        // 设置进程数，gateway进程数建议与cpu核数相同
        $gateway->count           = config('gateway.gateway.count');
        // 分布式部署时请设置成内网ip（非127.0.0.1）
        $gateway->lanIp           = config('gateway.gateway.lan_ip');
        // 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
        // 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口 
        $gateway->startPort       = config('gateway.gateway.startPort');
        // 服务注册地址
        $gateway->registerAddress = config('gateway.register.host') . ':' . config('gateway.register.port');
        // 心跳间隔
        $gateway->pingInterval    = 10;
        // 心跳数据
        $gateway->pingData        = '{"type":"ping"}';//'{"action":"sys/ping","data":"0"}';

        // 当客户端连接上来时，设置连接的onWebSocketConnect，即在websocket握手时的回调
        // $gateway->onConnect = function($connection)
        // {
        //     $connection->onWebSocketConnect = function($connection , $http_header)
        //     {
        //         // 可以在这里判断连接来源是否合法，不合法就关掉连接
        //         // $_SERVER['HTTP_ORIGIN']标识来自哪个站点的页面发起的websocket链接
        //         if($_SERVER['HTTP_ORIGIN'] != config('gateway.gateway.fromDoain'))
        //         {
        //             $connection->close();
        //         }
        //         // onWebSocketConnect 里面$_GET $_SERVER是可用的
        //         // var_dump($_GET, $_SERVER);
        //     };
        // }; 


        // BusinessWorker
        $worker                  = new BusinessWorker();
        $worker->name            = config('gateway.worker.name');
        $worker->count           = config('gateway.worker.count');
        $worker->registerAddress = config('gateway.register.host') . ':' . config('gateway.register.port');
        $worker->eventHandler    = 'app\Console\Commands\WsServer';


        Worker::runAll();
    }

    /**
     * 当客户端发来消息时触发
     * @param int   $client_id 连接id
     * @param mixed $message 具体消息
     */
    public static function onMessage($client_id, $message)
    {
        // Router::init($client_id, $message);
        // debug
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:".json_encode($_SESSION)." onMessage:".$message."\n";

        // $user = access()->user();
        
        // 客户端传递的是json数据
        $message_data = json_decode($message, true);
        if(!$message_data)
        {
            return ;
        }
        
        // 根据类型执行不同的业务
        switch($message_data['type'])
        {
            // 客户端回应服务端的心跳
            case 'pong':
                return;
            // 客户端登录 message格式: {type:login, name:xx, room_id:1} ，添加到客户端，广播给所有客户端xx进入聊天室
            case 'login':
                // 判断是否有房间号
                if(!isset($message_data['room_id']))
                {
                    throw new \Exception("\$message_data['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                }
                
                // 把房间号昵称放到session中
                $room_id = $message_data['room_id'];
                $client_name = htmlspecialchars($message_data['client_name']);
                $_SESSION['room_id'] = $room_id;
                $_SESSION['client_name'] = $client_name;
              
                // 获取房间内所有用户列表 
                $clients_list = WsSender::getClientSessionsByGroup($room_id);
                foreach($clients_list as $tmp_client_id=>$item)
                {
                    $clients_list[$tmp_client_id] = $item['client_name'];
                }
                $clients_list[$client_id] = $client_name;
                
                // 转播给当前房间的所有客户端，xx进入聊天室 message {type:login, client_id:xx, name:xx} 
                $new_message = array('type'=>$message_data['type'], 'client_id'=>$client_id, 'client_name'=>htmlspecialchars($client_name), 'time'=>date('Y-m-d H:i:s'));
                WsSender::sendToGroup($room_id, json_encode($new_message));
                WsSender::joinGroup($client_id, $room_id);
               
                // 给当前用户发送用户列表 
                $new_message['client_list'] = $clients_list;
                WsSender::sendToCurrentClient(json_encode($new_message));
                return;
                
            // 客户端发言 message: {type:say, to_client_id:xx, content:xx}
            case 'say':
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $client_name = $_SESSION['client_name'];
                
                // 私聊
                if($message_data['to_client_id'] != 'all')
                {
                    $new_message = array(
                        'type'=>'say',
                        'from_client_id'=>$client_id, 
                        'from_client_name' =>$client_name,
                        'to_client_id'=>$message_data['to_client_id'],
                        'content'=>"<b>对你说: </b>".nl2br(htmlspecialchars($message_data['content'])),
                        'time'=>date('Y-m-d H:i:s'),
                    );
                    WsSender::sendToClient($message_data['to_client_id'], json_encode($new_message));
                    $new_message['content'] = "<b>你对".htmlspecialchars($message_data['to_client_name'])."说: </b>".nl2br(htmlspecialchars($message_data['content']));
                    return WsSender::sendToCurrentClient(json_encode($new_message));
                }
                
                $new_message = array(
                    'type'=>'say', 
                    'from_client_id'=>$client_id,
                    'from_client_name' =>$client_name,
                    'to_client_id'=>'all',
                    'content'=>nl2br(htmlspecialchars($message_data['content'])),
                    'time'=>date('Y-m-d H:i:s'),
                );
                return WsSender::sendToGroup($room_id ,json_encode($new_message));
        }
    }


    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     */
    public static function onConnect()
    {
        $result           = [];
        $result['action'] = "sys/connect";
        $result['msg']    = '连接成功！';
        $result['code']   = 9900;
        WsSender::sendToCurrentClient(json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 进程启动后初始化数据库连接
     */
    public static function onWorkerStart()
    {

    }

    /**
     * 当用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($client_id)
    {
        // Router::close($client_id);
        // debug
       echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onClose:''\n";
       
       // 从房间的客户端列表中删除
       if(isset($_SESSION['room_id']))
       {
           $room_id = $_SESSION['room_id'];
           $new_message = array('type'=>'logout', 'from_client_id'=>$client_id, 'from_client_name'=>$_SESSION['client_name'], 'time'=>date('Y-m-d H:i:s'));
           WsSender::sendToGroup($room_id, json_encode($new_message));
       }
    }
}