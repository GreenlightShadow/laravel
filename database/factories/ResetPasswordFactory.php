<?php

namespace Database\Factories;

use App\Models\ResetPassword;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResetPasswordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ResetPassword::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => '41',
            'token' => 'hrenkjfle3ilhnl43423gblb423',
            'created_at' => '2021-05-24 11:35:27'
        ];
    }
}
