<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SizeStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'size_label' => 'required|string|max:10',
            'quantity_available' => 'required|integer|min:0',
        ];
    }
}
