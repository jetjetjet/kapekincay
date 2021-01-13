<?php

namespace Database\Factories;

use App\Models\Board;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Board::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker =  $this->faker;
        return [
            'number' => $faker->unique()->numberBetween(1,30),
            'floor' => $faker->randomElement($array = array ('1', '2', '3')),
            'space' => $faker->randomElement($array = array ('2', '4', '6')),
            'active' => '1',
            'created_at' => now(),
            'created_by' => 1,
        ];
    }
}