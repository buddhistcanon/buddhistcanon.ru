<?php

namespace App\Http\Requests;

use App\Models\SearchToken;
use Illuminate\Foundation\Http\FormRequest;
use Validator;

class SearchApiRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'q' => 'required|min:3',
            'token' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

//    public static function withValidator(Validator $validator): void
//    {
//        $validator->after(function ($validator) {
//            $tokenActive = SearchToken::query()
//                ->where("token", $this->token)
//                ->where("is_active", 1)
//                ->first();
//            if(!$tokenActive) $validator->errors()->add('token', "Token is not valid");
//        });
//    }
}
