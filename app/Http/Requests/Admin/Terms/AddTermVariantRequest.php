<?php

namespace App\Http\Requests\Admin\Terms;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class AddTermVariantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'term_id' => ['required', 'integer'],
            'term_proposal_ids' => ['required', 'array']
        ];
    }

    public function authorize(): bool
    {
        if(optional(Auth::user())->is_superadmin) return true;
        return false;
    }

    public function messages()
    {
        return [
            'term_id.required' => 'Выберите термин, к которому присоединять варианты.',
            'term_proposal_ids.required' => 'Выберите варианты синонимов, которые присоединять к термину.'
        ];
    }

    public function attributes()
    {
    	return [
            'term_proposal_ids' => "Массив вариантов терминов"
        ];
    }
}
