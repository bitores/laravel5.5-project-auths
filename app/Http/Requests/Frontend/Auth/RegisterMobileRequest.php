<?php

namespace App\Http\Requests\Frontend\Auth;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use App\Rules\ValidatePhoneRule;

use SmsManager;

/**
 * Class RegisterRequest.
 */
class RegisterMobileRequest extends Request
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
            'mobile'                => ['required', 'string', new ValidatePhoneRule, 'max:11', Rule::unique('users')],
            // 'mobile'                => 'required|max:11|confirm_mobile_not_change|confirm_rule:mobile_required',verify_code
            // 'verifyCode'            => 'required',

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
        SmsManager::forgetState();
        return [
            'g-recaptcha-response.required_if' => trans('validation.required', ['attribute' => 'captcha']),
        ];
    }
}
