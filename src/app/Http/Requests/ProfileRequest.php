<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'img_file' => 'mimes:jpeg,png',
        ];
    }

    public function messages()
    {
        return [
            'img_file.mimes' => '「.jpeg」もしくは「.png」形式でアップロードしてください',
        ];
    }
}
