<?php

namespace App\Http\Requests\Frontend\User;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class UpdateProfileRequest.
 */
class BrandRequest extends Request
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
            // 'brd_name'  => [
            //     'required',
            //     'max:50',
            //     Rule::unique('u_brands')->where(function(){
            //         $query->where('user_id', access()->id());
            //     })],,
            // exists:u_brands,name,user_id,'.access()->id(), 取指定用户同样的数据
            // 同一用户不能创建同一品牌
            'brd_name'  => 'required|max:191|unique:u_brands,name,NULL,id,user_id,'.access()->id()
    //         'email' => 'sometimes|required|email|max:191',
        ];
    }
}
