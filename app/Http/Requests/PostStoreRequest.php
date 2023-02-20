<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:50',
            'slug'=> ['required','max:70', Rule::unique('posts', 'slug')->ignore($this->route('post'))],
            'description' => 'required|min:5|max:1000',
            'category_id' => 'required|exists:categories,id'
        ];
    }
}
