<?php

namespace App\Repositories\Frontend\MLM;

use App\Models\MLM\Style;
use App\Repositories\BaseRepository;
/**
 * Class UserSessionRepository.
 */
class StyleRepository extends BaseRepository
{
    const MODEL = Style::class;

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function create(array $data)
    {
        $brand = self::MODEL;


        $brand = new $brand;

        $brand->name = $data['name'];
        $brand->save();

        return $brand;
    }
}
