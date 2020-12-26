<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        $state = ['Dhaka', 'Chattogram', 'Sylhet', 'Khulna', 'Rangpur'];
        return [
            'user_id' => $faker->unique()->numberBetween(1, 100),
            'address_line_1' => $faker->address,
            'address_line_2' => $faker->address,
            'city' => $faker->city,
            'state' => $faker->state, //randomElements($state)[0],
            'country' => $faker->country,
            'zip_code' => $faker->numberBetween(1111, 9999),

        ];
    }
}
