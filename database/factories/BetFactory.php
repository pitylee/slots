<?php

namespace Database\Factories;

use App\Models\Bet;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Currency;
use App\Currencies\USD;
use App\Currencies\EUR;

class BetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bet::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomDigitNotNull,
            'currency' => $this->faker->randomElement(array_map(function ($currency){
                return $currency->code;
            }, config('app.currencies'))),
            'client' => $this->faker->name,
        ];
    }
}
