<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|max:255',
            'username' => ['required', 'max:255', Rule::unique('users')->ignore($this->user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id)],
            // 'password' => 'required|min:6',
            'role' => 'required',
        ];
    }

    public function message()
    {
        return [
            'name.required' => 'Nama User harus diisi',
            'name.max' => 'Nama User maksimal 255 karakter',
            'username.required' => 'Username harus diisi',
            'username.max' => 'Username maksimal 255 karakter',
            'username.unique' => 'Username sudah terdaftar',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.max' => 'Email maksimal 255 karakter',
            'email.unique' => 'Email sudah terdaftar',
            'role.required' => 'Role harus diisi',
        ];
    }
}
