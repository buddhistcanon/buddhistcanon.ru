<?php

namespace Database\Factories;

use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Content::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lang' => 'ru',
            'translator_name' => $this->faker->firstName,
            'description' => $this->faker->paragraph,
        ];
    }

    public function main()
    {
        return $this->state([
            'is_main' => 1,
        ]);
    }
}
