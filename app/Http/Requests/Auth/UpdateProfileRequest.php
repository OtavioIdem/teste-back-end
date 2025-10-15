<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'name'  => ['sometimes','required','string','max:255'],
            'email' => ['sometimes','required','email','unique:users,email,'.$this->user()->id],
            'phone' => ['sometimes','nullable','string','max:30'],
            'image_url' => ['sometimes','nullable','url'],
        ];
    }
}
