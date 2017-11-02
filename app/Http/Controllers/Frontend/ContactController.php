<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Frontend\Contact\SendContact;
use App\Http\Requests\Frontend\Contact\SendContactRequest;
use App\Models\System\ContactUS;

/**
 * Class ContactController.
 */
class ContactController extends Controller
{
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

        $contactUs = new ContactUS;

        $contactUs->user_name = $request->get('name');
        $contactUs->email  = $request->get('email');
        $contactUs->phone = $request->get('phone');
        $contactUs->message = $request->get('message');


        $contactUs->save();
        return redirect()->back()->withFlashSuccess(trans('alerts.frontend.contact.sent'));
    }
}
