<?php

namespace App\Http\Controllers\Frontend\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Repositories\Frontend\Access\User\UserRepository;

/**
 * Class ResetPasswordController.
 */
class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * ChangePasswordController constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param string|null $token
     *
     * @return \Illuminate\Http\Response
     */
    public function showResetForm($token = null)
    {
        if (! $token) {
            return redirect()->route('frontend.auth.password.email');
        }

        $user = $this->user->findByPasswordResetToken($token);

        if ($user && app()->make('auth.password.broker')->tokenExists($user, $token)) {
            return view('frontend.auth.passwords.reset')
                ->withToken($token)
                ->withEmail($user->email);
        }

        return redirect()->route('frontend.auth.password.email')
            ->withFlashDanger(trans('exceptions.frontend.auth.password.reset_problem'));
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetResponse($response)
    {
        return redirect()->route(homeRoute())->withFlashSuccess(trans($response));
    }


    /**
     * 发送重置密码邮件
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(EmailResetPasswordSendRequest $request)
    {
        $broker = $this->getBroker();

        $response = RyanPassword::broker($broker)->sendResetLink($request->only('email'));

        switch ($response) {
            case RyanPassword::RESET_LINK_SENT:
                return ['status_code' => '200', 'message' => '密码重置邮件已发送'];

            case RyanPassword::INVALID_USER:
            default:
                throw new \UnauthorizedHttpException(401, '该邮箱未注册');
        }
    }


    /**
     * 通过邮件重置密码
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function resetBymail(EmailResetPasswordRequest $request)
    {
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

        $broker = $this->getBroker();

        $response = RyanPassword::broker($broker)->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case RyanPassword::PASSWORD_RESET:
                unset($credentials['token']);
                unset($credentials['password_confirmation']);
                return [
                    'status_code' => '200',
                    'message' => '密码重置成功'
                ];

            case RyanPassword::INVALID_TOKEN:
                //返回'Token 已经失效'

            default:
                //返回'密码重置失败'
        }
    }


    /**
     *  发送重置密码短信验证码
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetCodePhone(PhoneResetPasswordSendRequest $request)
    {
        $broker = $this->getBroker();

        $response = RyanPassword::broker($broker)->sendResetCode($request->only('telephone'));

        switch ($response) {
            case RyanPassword::RESET_LINK_SENT:
                return ['status_code' => '200', 'message' => '密码重置验证码已发送'];

            case RyanPassword::INVALID_USER:
            default:
                //返回'该手机号未注册'
        }
    }


    /**
     * 通过短信验证码重置密码
     * @param PhoneResetPasswordRequest $request
     * @return array
     */
    public function resetByPhone(Request $request)
    {
        $credentials = $request->only('telephone', 'password', 'password_confirmation', 'verify_code');

        $broker = $this->getBroker();

        $response = RyanPassword::broker($broker)->resetByPhone($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case RyanPassword::PASSWORD_RESET:
                unset($credentials['verify_code']);
                unset($credentials['password_confirmation']);
                return [
                    'status_code' => '200',
                    'message' => '密码重置成功',
                ];

            case RyanPassword::INVALID_TOKEN:
                //返回'手机验证码已失效'

            default:
                //返回'密码重置失败'
        }
    }
}
