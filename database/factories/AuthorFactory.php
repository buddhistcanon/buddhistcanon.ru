<?php

namespace Database\Factories;

use App\Models\Author;
use Arr;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->firstName;
        $fullname = Arr::random(['Bhikku', 'Ajahn']).' '.$name;

        return [
            'short_name' => $name,
            'full_name' => $fullname,
            'slug' => Str::slug($fullname),
            'description' => $this->faker->paragraphs(5, true),
        ];
    }

    public function monk()
    {
        return $this->state([
            'is_monk' => 1,
        ]);
    }
}
