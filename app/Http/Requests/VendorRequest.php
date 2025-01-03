<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item.vendore_code' => ['required', 'unique:v_vendor,vendore_code', 'min:2', 'max:20'],
            'item.vendore_name' => ['required', 'min:4', 'max:512'] 
        ];
    }

    public function messages() {
        return [
            'item.vendore_code.required' => 'Поле модель обязательно для заполнения',
            'item.vendore_code.unique' => 'Модель уже существует',
            'item.vendore_code.min' => 'Модель должна быть не менее 2х символов',
            'item.vendore_code.max' => 'Модель должна быть не более 20 символов',
            'item.vendore_name.required' => 'Поле название СУТ обязательно для заполнения',
            'item.vendore_name.min' => 'Название СУТ должно быть не менее 4х символов',
            'item.vendore_name.max' => 'Название СУТ должно быть не более 250 символов'
        ];
    }
}
