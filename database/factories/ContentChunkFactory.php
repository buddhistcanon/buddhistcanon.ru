<?php

namespace Database\Factories;

use App\Models\ContentChunk;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentChunkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ContentChunk::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content_id' => 0,
            'order' => 0,
            'text' => $this->faker->paragraph,
        ];
    }
}
