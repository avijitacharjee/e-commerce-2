<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'gender' => $this->faker->randomElements(['male', 'female', 'male', 'female', 'other'])[0],
            'date_of_birth' => $this->faker->dateTimeBetween('1960-01-01', '2006-12-31')->format('Y/m/d'),
            'status' => $this->faker->randomElements(['active', 'inactive', 'active'])[0],
            'password' => bcrypt('password'),
            'picture_path' => $this->faker->imageUrl(),
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'email_verification_token' => '',
            'phone_number' => $this->faker->unique()->numberBetween(1300000000, 1999999999),
            'number_verified_at' => now(),
            'number_verification_pin' => '',
            
            'remember_token' => Str::random(10),
        ];
    }
}
