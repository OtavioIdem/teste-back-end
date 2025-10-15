<?php
namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes','required','string','max:255'],
            'price'       => ['sometimes','required','numeric','min:0'],
            'description' => ['sometimes','required','string'],
            'category'    => ['sometimes','required'],
            'image'       => ['nullable','url'],
        ];
    }
}
