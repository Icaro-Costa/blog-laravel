<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define o estado padrão do modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'image' => fake()->imageUrl(),
            'birthDate' => fake()->date(),
            'password' => Hash::make('password'), 
            
            // Campos de endereço
            'address_address' => fake()->streetAddress(),
            'address_city' => fake()->city(),
            'address_state' => fake()->state(),
            'address_postalCode' => fake()->postcode(),
        ];
    }
}