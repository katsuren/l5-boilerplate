<?php

namespace App\Http\Requests\Admin;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminsFormRequest extends FormRequest
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
        $rules = [
            'admin.name' => ['max:100', 'required'],
        ];
        if ($this->isMethod('post')) {
            $rules['admin.email'] = ['max:180', 'required', 'email', Rule::unique('admins', 'email')];
        } else {
            $id = $this->route('admin');
            $rules['admin.email'] = ['max:180', 'required', 'email', Rule::unique('admins', 'email')->ignore($id)];
        }
        return $rules;
    }
}
