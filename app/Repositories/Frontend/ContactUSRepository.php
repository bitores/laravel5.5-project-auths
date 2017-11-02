<?php

namespace App\Repositories\Frontend;

use App\Models\System\ContactUS;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;


/**
 * Class UserRepository.
 */
class ContactUSRepository extends BaseRepository
{
	const MODEL = ContactUS::class;

	public function create($input)
    {
    	$contactUs = self::MODEL;
        $contactUs = new $contactUs;
        $data = $input['data'];

    	$contactUs->user_name = $data['name'];
        $contactUs->email  = $data['email'];
        $contactUs->phone = $data['phone'];
        $contactUs->message = $data['message'];

        $contactUs->save();


        return $contactUs;
    }
}