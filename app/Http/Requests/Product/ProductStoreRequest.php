<?php
namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => ['required','string','max:255'],
            'price'       => ['required','numeric','min:0'],
            'description' => ['required','string'],
            'category'    => ['required'], // string ou array (service normaliza)
            'image'       => ['nullable','url'],
        ];
    }
}
