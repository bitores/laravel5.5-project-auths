<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\PCategoryB;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class PCategoryBRepository extends BaseRepository
{
    const MODEL = PCategoryB::class;

    public function create(array $data)
    {
        $model = self::MODEL;


        $instance = new $model;

        $instance->name = $data['name'];
        $instance->category_a_id = $data['category_a_id'];
        $instance->save();

        return $instance;
    }
}
