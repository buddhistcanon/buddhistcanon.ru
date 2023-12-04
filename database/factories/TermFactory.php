<?php

namespace Database\Factories;

use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class TermFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Term::class;

    /**
     * Define the model's default state.
     */
    #[ArrayShape(['title' => 'string', 'slug' => 'string', 'short_text' => 'array|string', 'text' => 'array|string'])]
    public function definition(): array
    {
        $title = $this->faker->word;
        $arr = [
            'title' => $title,
            'slug' => Str::slug($title),
            'short_text' => $this->faker->paragraphs(1, true),
            'text' => $this->faker->paragraphs(6, true),
        ];

        return $arr;
    }
}
