<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\MLM\UClientRepository;
use GatewayWorker\Lib\Gateway as WsSender;
use Illuminate\Http\Request;

/**
 * Class LanguageController.
 */
class ChatController extends Controller
{
    public function __construct(UClientRepository $client)
    {
        $this->client = $client;
    }

    /**
     * login - user
     * @author Chengcheng
     * @date 2016-10-21 09:00:00
     */
    public function userLoginAction(Request $request)
    {

        //返回结果
        $result         = [];
        $result['code'] = 0;
        $result['data'] = [];
        $result['msg']  = 'success';

        $client_id = $request->get('client_id');

        $this->client->update(access()->id(), $client_id);
        return $result;
    }
}
