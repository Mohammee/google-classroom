<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassroomRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

//        dd($this->route('classroom'));
        return  [
            'name' => [
//                (isset($this->classroom) ? 'nullable': 'required'),
            'required',
                'string', 'max:255', function($attribute, $value, $fail){
            if($value == 'admin'){
                return $fail('This :attribute is forbidden.');
            }
                }
            ],
            'section' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'image' => [
                'nullable',
                'image',
                'max:1024',
                Rule::dimensions([
                    'min_width' => 600,
                    'min_height' => 300
                ])
            ]
        ];
    }

    public function messages(): array
    {
       return [
           'required' => ':attribute Important',
           'name.required' => 'The name is required.',
           'image.max' => 'Image size greater than 1M.'
       ];
    }
}
