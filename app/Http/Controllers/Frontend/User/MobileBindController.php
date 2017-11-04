<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\User\MobileBindRequest;
use App\Repositories\Frontend\Access\User\UserRepository;

/**
 * Class RegisterController.
 */
class MobileBindController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * RegisterController constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        // Where to redirect users after registering
        $this->redirectTo = route(homeRoute());

        $this->user = $user;
    }

   
    /**
     * @param RegisterMobileRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function bind(MobileBindRequest $request)
    {
        

        $this->user->bindMobile($request->only(['mobile']));

        // event(new UserRegistered($user));event(new UserRegistered(access()->user()));

        return redirect($this->redirectTo);
    }
}
