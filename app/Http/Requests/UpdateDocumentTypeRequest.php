<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocumentTypeRequest extends FormRequest
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
            'name' => ['required', 'max:255', Rule::unique('document_types')->ignore($this->document_type_id)],
        ];
    }

    public function message()
    {
        return [
            'name.required' => 'Jenis Dokumen harus diisi',
            'name.max' => 'Jenis Dokumen maksimal 255 karakter',
            'name.unique' => 'Jenis Dokumen sudah terdaftar',
        ];
    }
}
