<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Municipality;

class MunicipalityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Municipality::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->city,
            'code' => $this->faker->unique()->randomNumber(5), // 5桁のランダムな数字
            'source' => 'Test Data',
        ];
    }
}
