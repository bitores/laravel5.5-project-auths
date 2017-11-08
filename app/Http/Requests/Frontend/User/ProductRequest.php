<?php

namespace App\Http\Requests\Frontend\User;

use App\Http\Requests\Request;

/**
 * Class UpdateProfileRequest.
 */
class ProductRequest extends Request
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
        // 'current_pro' => 'numeric|exists:products,id',
        // 'style_id' => 'numeric',
        // 'a_id' => 'numeric',
        // 'b_id' => 'numeric',
        // 'brand_id' => 'numeric',
        // 'cad_id' => 'numeric',
        // 'file_id' => 'numeric',
        // 'status_id' => 'numeric',
        // 'model_id' => 'numeric',
        // 'fee' => 'numeric'
        ];
    }
}
