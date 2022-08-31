<?php

namespace Database\Factories;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->words(4, true),
            'text' => $this->faker->paragraphs(4, true),
        ];
    }

    public function published()
    {
        return $this->state([
            'published_at' => Carbon::now()->subDays(5),
        ]);
    }
}
