<?php

namespace App\Data\Search;

use Illuminate\Validation\Validator;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

class SearchRequestData extends Data
{
    public function __construct(
        #[Min(3)]
        public string $q,
    ) {
    }

    // Валидация
//    public static function rules(): array
//    {
//        return [
//            'q' => ['required', 'min:3'],
//        ];
//    }
//    public static function messages(): array
//    {
//        return [
//            'q.required' => 'Слишком короткий поисковый запрос, допускается минимум три буквы.',
//        ];
//    }
//    public static function withValidator(Validator $validator): void
//    {
//        $validator->after(function ($validator) {
//            // TODO проверка на мат и другие запрещённые слова, чтобы не показывались в публичной истории поиска
//            //$validator->errors()->add('searchTerm', "Обнаружены недопустимые слова в поиске.");
//        });
//    }
}
