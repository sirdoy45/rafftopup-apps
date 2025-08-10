<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255', 
            'provider' => 'required|string|max:255',
            'categories_id' => 'required|exists:categories,id', 
            'kode_produk' => 'required|string|max:100|unique:products,kode_produk,' . $this->product,
            'vendor_price' => 'nullable|numeric',
            'price' => 'required|integer', 
            'status' => 'required|in:aktif,nonaktif',
            'description' => 'required',
            'input_type' => 'required|in:id_game,user_id,no_hp',
        ];
    }

}
