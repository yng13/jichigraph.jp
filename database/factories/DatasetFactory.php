<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dataset;

class DatasetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dataset::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'source' => 'Test Data',
            'publication_date' => $this->faker->date(),
            'update_frequency' => $this->faker->randomElement(['daily', 'weekly', 'monthly']),
            'data_source_type' => $this->faker->randomElement(['web', 'local', 'api']),
            'data_source_url' => $this->faker->url,
        ];
    }
}
