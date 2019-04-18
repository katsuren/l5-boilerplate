<?php

namespace App\Http\Requests\User;

use App\Entities\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountFormRequest extends FormRequest
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
        $user = Auth::user();
        $rules = [
            'user.name' => ['max:100', 'required'],
            'user.email' => ['max:180', 'required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'user.password' => ['nullable', 'between:8,255', 'confirmed'],
        ];
        return $rules;
    }
}
