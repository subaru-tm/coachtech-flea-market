<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'required|max:255',
            'img_file' => 'image|mimes:jpeg,png',
            'category' => 'required|array|min:1',
            'condition' => 'required',
            'price' => 'required|min:0',
             // 基本設計書では'numeric'(数値型)も仕様として指定がありましたが、
             // 入力欄に'¥'を入れる都合、'numeric'を外しています。
             // 'numeric'を指定していると、数字を追加入力しても全て弾かれるため。
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '商品の説明は255文字以内で入力してください',
            'img_file.required' => '商品画像をアップロードしてください',
            'img_file.mimes' => '「.jpeg」もしくは「.png」形式でアップロードしてください',
            'category.required' => 'カテゴリーを１つ以上選択してください',
            'condition.required' => '商品の状態を選択してください',
            'price.required' => '販売価格を入力してください',
            'price.min' => '販売価格は0円以上で入力してください',
        ];
    }
}
