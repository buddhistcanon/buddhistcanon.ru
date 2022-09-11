<?php

namespace App\Data;

use App\Models\Term;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use Str;

class TermData extends Data
{
    public function __construct(
        public ?int $id,
        public string $title,
        public string $short_text,
        public string $text,
        public string $slug = '',
        public ?string $parts_text = '',
        public ?array $variants = [], // массив синонимов
    ) {
        if (! $this->slug) {
            $this->slug = Str::slug($this->title);
        }
    }

    public static function rules(): array
    {
        return [
            'id' => ['integer', 'nullable'],
            'title' => ['required', 'string'],
            'slug' => ['required', 'string'],
            'short_text' => ['required', 'string'],
            'text' => ['required', 'string'],
            'parts_text' => ['string', 'nullable'],
            //'variants' => ['array'],
        ];
    }

    public static function fromModel(Term $term): self
    {
        $variants = $term->variants->map(function ($item) {
            return $item->title;
        })->toArray();

        return new self(
            id: $term->id,
            title: $term->title,
            short_text: $term->short_text,
            text: $term->text,
            slug: $term->slug,
            parts_text: $term->parts_text,
            variants: $variants,
        );
    }

    public function fromArray(array $array)
    {
        return new self(
            id: $array['id'],
            title: $array['title'],
            short_text: $array['short_text'],
            text: $array['text'],
            slug: $array['slug'],
            parts_text: $array['parts_text'],
            variants: $array['variants'],
        );
    }

    public static function fromRequest(Request $request): self
    {
        self::validate($request->all()); // необходимо ! // TODO проверить в новых версиях, нужно ли, обещали убрать
        $variants = $request->input('list_variants') ? $variants = explode("\n", $request->input('list_variants')) : [];

        return new self(
            id: $request->input('id') ?? null,
            title: $request->input('title'),
            short_text: $request->input('short_text') ?? '',
            text: $request->input('text') ?? '',
            parts_text: $request->input('parts_text') ?? '',
            variants: $variants,
        );
    }
}
