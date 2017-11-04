<?php

namespace App\Http\Requests\Frontend\User;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use App\Rules\ValidatePhoneRule;

use SmsManager;

/**
 * Class RegisterRequest.
 */
class MobileBindRequest extends Request
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
            'mobile'     => 'required|zh_mobile|unique:users',
            'verifyCode' => 'required|verify_code',
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
            'mobile.unique' => '手机号码已经被占用',
            'mobile.confirm_mobile_not_change' => '用户提交的手机号没变化',
            'mobile.confirm_rule' => '手机号码前后不一致',
            'mobile.zh_mobile' => '手机号格式不对',
            'verifyCode.verify_code' => '无效验证码',//验证码错误、过期或超出尝试次数
        ];
    }
}
