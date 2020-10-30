<?php

namespace App\Http\Requests\Auth;

use App\Rules\OldPassword;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
            'oldPassword' => ['required', new OldPassword],
            'newPassword' => ['required', 'confirmed']
        ];
    }
}
