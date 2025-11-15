<?php

namespace Database\Factories;

use App\Models\Forum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Forum>
 */
class ForumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Forum::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Crop Farming', 'Livestock', 'Organic Farming', 'Market Prices', 'Equipment'];

        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'category' => $this->faker->randomElement($categories),
            'video_path' => null,
            'video_original_name' => null,
            'views' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the forum has a video.
     */
    public function withVideo(): static
    {
        return $this->state(fn (array $attributes) => [
            'video_path' => 'forums/videos/' . $this->faker->uuid() . '.mp4',
            'video_original_name' => $this->faker->word() . '.mp4',
        ]);
    }
}
