<?php

namespace App\Http\Requests\Frontend\Auth;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use SmsManager;
/**
 * Class RegisterRequest.
 */
class ResetPasswordRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'first_name'           => 'required|string|max:191',
            // 'last_name'            => 'required|string|max:191',
            // 'mobile'                => ['required', 'string', new ValidatePhoneRule, 'max:10', Rule::unique('users')],
            // |confirm_rule:mobile,mobile_required|confirm_mobile_not_change
            'mobile'     => 'required|zh_mobile',
            'verifyCode' => 'required|verify_code',

            'password'             => 'required|string|min:6|confirmed',
            'g-recaptcha-response' => 'required_if:captcha_status,true|captcha',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        //验证失败后建议清空存储的发送状态，防止用户重复试错
        // SmsManager::forgetState();
        return [
            'mobile.unique' => '手机号码已经被注册',
            'mobile.confirm_mobile_not_change' => '用户提交的手机号没变化',
            'mobile.confirm_rule' => '手机号码前后不一致',
            'mobile.zh_mobile' => '手机号格式不对',
            'verifyCode.verify_code' => '无效验证码',//验证码错误、过期或超出尝试次数
            'g-recaptcha-response.required_if' => trans('validation.required', ['attribute' => 'captcha']),
        ];
    }
}
