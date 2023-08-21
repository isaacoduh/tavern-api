<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerAddress>
 */
class CustomerAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CustomerAddress::class;
    public function definition()
    {
        $addressTypes = ['billing','shipping'];
        return [
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'address' => fake()->address(),
            'city' => fake()->city(),
            'postcode' => fake()->postcode(),
            'type' => fake()->randomElement($addressTypes),
            'selected' => false
        ];
    }
}
