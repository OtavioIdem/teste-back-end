<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateProductRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'name'        => ['required','string','max:255'],
            'price'       => ['required','numeric','min:0'],
            'description' => ['required','string'],
            'category'    => ['sometimes','string'],
            'categories'  => ['sometimes','array','max:3'],
            'categories.*'=> ['string','distinct'],
            'image'       => ['sometimes','nullable','url'],
            'image_url'   => ['sometimes','nullable','url'],
        ];
    }
    protected function prepareForValidation(): void {
        if ($this->has('image') && !$this->has('image_url')) {
            $this->merge(['image_url' => $this->input('image')]);
        }
        
        if ($this->has('category') && !$this->has('categories')) {
            $cat = $this->input('category');
            $this->merge(['categories' => is_array($cat) ? $cat : [$cat]]);
        }
    }
}