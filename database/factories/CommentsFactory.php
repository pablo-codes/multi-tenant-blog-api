<?php

namespace Database\Factories;

use App\Models\Comments;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentsFactory extends Factory
{
    public function definition()
    {
        return [
            'content' => $this->faker->sentence,
            'post_id' => Post::factory(),  // Creates a post if not provided
            'user_id' => User::factory(),  // Creates a user if not provided
        ];
    }
}
