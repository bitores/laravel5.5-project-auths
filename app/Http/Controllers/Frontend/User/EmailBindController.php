<?php

namespace App\Http\Controllers\Frontend\User;

use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Notifications\Frontend\Auth\UserNeedsBinding;
use App\Http\Requests\Frontend\User\EmailBindRequest;

/**
 * Class ConfirmAccountController.
 */
class EmailBindController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * ConfirmAccountController constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function bindEmail($token)
    {
        $user = $this->user->findByConfirmationToken($token);
        if($user) {
            $binduser = $this->user->findByEmail($user->bindemail);
            if($binduser) {
                return redirect()->route('frontend.user.account')->withFlashSuccess('邮箱绑定失败：邮箱被占用');
            }

            $user->email = $user->bindemail;
            $user->save();
            return redirect()->route('frontend.user.account')->withFlashSuccess('邮箱绑定成功');
            
        } else {
            return redirect()->route('frontend.user.account')->withFlashSuccess('邮箱绑定失败');
        }
    }

    /**
     * @param $user
     *
     * @return mixed
     */
    public function sendBindingEmail(EmailBindRequest $request)
    {

        $user = access()->user();
        $user->bindemail = $request->get('email');
        $user->save();
        $user->email = $user->bindemail;

        $user->notify(new UserNeedsBinding($user->confirmation_code));

        return redirect()->route('frontend.user.account')->withFlashSuccess('邮箱绑定确认邮件已成功发送');
    }
}
