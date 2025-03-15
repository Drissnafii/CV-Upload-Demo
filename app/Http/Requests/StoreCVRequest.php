<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCVRequest extends FormRequest
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
            'title' => ['required','string','max:255'],
            'cv_file'=> [
            'required',
            'file',
            'mimes:pdf, doc, docx', // Allow Just PDF and Word docs
            'max:5120'], // max 5MBd
        ];
    }

    public function messages() {
        return [
            'cv_file.max'=> 'The CV file may not be greater than 5MB.',
            'cv_file.mime'=> 'The CV file must be PDF / Word document (doc/docx).',
        ];
    }
}
