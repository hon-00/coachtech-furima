<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Item;

class CommentFactory extends Factory
{
    public function definition()
    {
        return [
            'content' => $this->faker->sentence(10),
        ];
    }
}