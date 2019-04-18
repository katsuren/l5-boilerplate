<?php

namespace App\Http\Requests\Admin;

use App\Entities\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsersFormRequest extends FormRequest
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
        $id = $this->route('user');
        $rules = [
            'user.name' => ['max:100', 'required'],
            'user.email' => ['max:180', 'required', 'email', Rule::unique('users', 'email')->ignore($id)],
        ];
        return $rules;
    }
}
