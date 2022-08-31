<?php

namespace Database\Factories;

use App\Models\Book;
use Arr;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence;
        return [
            'author_id' => 0,
            'title' => $title,
            'slug' => Str::slug($title),
            'short_description' => $this->faker->paragraph,
            'is_short' => Arr::random([0,1]),
        ];
    }

    public function published()
    {
    	return $this->state([
            'published_at' => Carbon::now()->subDays(5),
        ]);
    }

    public function copyrighted()
    {
    	return $this->state([
            'is_copyrighted' => 1,
        ]);
    }
}
