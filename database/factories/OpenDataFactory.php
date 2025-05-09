<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\OpenData;
use App\Models\Municipality; // 追加
use App\Models\Dataset;      // 追加

class OpenDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OpenData::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'municipality_id' => Municipality::factory(), // MunicipalityFactory を使用
            'dataset_id' => Dataset::factory(),          // DatasetFactory を使用
            'record_identifier' => $this->faker->uuid,
            'title' => $this->faker->sentence,
            'data_type' => $this->faker->randomElement(['map', 'time_series', 'table']),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'data' => json_encode(['key' => 'value']),
        ];
    }
}
