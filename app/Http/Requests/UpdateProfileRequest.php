<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'name' => ['required','string','max:255'],
            'email'=> ['required','email','max:255', Rule::unique('users','email')->ignore($this->user()->id)],
            'phone'=> ['nullable','string','max:30'],
            'profile_image_url' => ['nullable','url'],
        ];
    }
}