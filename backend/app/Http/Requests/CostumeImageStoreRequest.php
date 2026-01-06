<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CostumeImageStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'image_url' => 'required_without:file|nullable|url',
            'file' => 'required_without:image_url|nullable|image|max:5120',
            'position' => 'nullable|integer',
        ];
    }
}
