<?php

namespace Database\Factories;

use App\Models\TermVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class TermVariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TermVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->word;

        return [
            'term_id' => 0,
            'title' => $title,
        ];
    }
}
