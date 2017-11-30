<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\UClient;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class UClientRepository extends BaseRepository
{
    const MODEL = UClient::class;

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function create($userid, $clientid)
    {
        $model = self::MODEL;

        $instance = new $model;

        $instance->client_id = $clientid;
        $instance->user_id = $userid;
        $instance->save();

        return $instance;
    }

    // 获取 指定用户 所有文件
    public function findByUserId($userid)
    {
        return $this->query()->where('user_id', $userid)->first();
    }


    public function update($userid, $clientid)
    {
        $client = $this->findByUserId($userid);

        if($client) {

            $client->client_id = $clientid;
            $client->save();
        } else {
            
            $client = $this->create($userid, $clientid);
        }

        return $client;
    }

}