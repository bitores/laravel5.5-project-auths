<?php

namespace App\Http\Requests\Frontend\Auth;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Class RegisterRequest.
 */
class SetRoleRequest extends Request
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
            'roleid'                => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        // return [
        //     'roleid' => trans('validation.required', ['attribute' => 'captcha']),
        // ];
        return [];
    }
}
