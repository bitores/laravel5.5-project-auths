<?php

namespace App\Repositories\Frontend\Access\User;

use App\Models\Access\User\Style;
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
