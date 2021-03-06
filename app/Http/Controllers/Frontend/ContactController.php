<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Frontend\Contact\SendContact;
use App\Http\Requests\Frontend\Contact\SendContactRequest;
use App\Repositories\Frontend\ContactUSRepository;

/**
 * Class ContactController.
 */
class ContactController extends Controller
{

    protected $contactUs;

    public function __construct(ContactUSRepository $contactUs)
    {
        $this->contactUs = $contactUs;
    }

    

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.contact');
    }

    /**
     * @param SendContactRequest $request
     *
     * @return mixed
     */
    public function send(SendContactRequest $request)
    {
        Mail::send(new SendContact($request));

        $this->contactUs->create([
            'data' => $request->only(
                'name',
                'email',
                'phone',
                'message'
            )
        ]);
        return redirect()->back()->withFlashSuccess(trans('alerts.frontend.contact.sent'));
    }
}
