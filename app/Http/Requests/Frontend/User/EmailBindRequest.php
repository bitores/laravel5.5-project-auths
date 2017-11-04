<?php

namespace App\Http\Requests\Frontend\User;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use App\Rules\ValidatePhoneRule;

/**
 * Class RegisterRequest.
 */
class EmailBindRequest extends Request
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
        ];
    }
}
