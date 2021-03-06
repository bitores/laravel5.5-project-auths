<?php

namespace App\Http\Requests\Backend\Access\User;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

use App\Rules\ValidatePhoneRule;

/**
 * Class StoreUserRequest.
 */
class StoreUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->hasRole(1);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nickname'  => 'required|max:191',
            'user_name' => 'required|max:191',
            'mobile'    => [new ValidatePhoneRule,'max:11',Rule::unique('users')],
            'email'    => ['email', 'max:191', Rule::unique('users')],
            'password' => 'required|min:6|confirmed',
        ];
    }
}
