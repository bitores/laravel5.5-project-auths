<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\SetRoleRequest;
use App\Repositories\Frontend\Access\User\UserRepository;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index()
    {
        return view('frontend.user.dashboard');
    }

    public function setRole()
    {
    	return view('frontend.mlm.setrole');
    }

    public function bindRole(SetRoleRequest $request)
    {
    	if(access()->hasRole('User'))
    	{
    		$user = access()->user();
    		$user->detachRoles($user->roles);
    		$user->attachRole([
    			'id' =>	$request->get('roleid')
    		]);
    		return redirect()->route('frontend.user.dashboard')->withFlashSuccess('角色绑定成功');
    	} else {
    		return redirect()->route('frontend.user.dashboard')->withFlashSuccess('角色绑定失败');
    	}

    	
    }
}
