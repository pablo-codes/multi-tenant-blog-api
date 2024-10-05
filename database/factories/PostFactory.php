<?php

namespace Database\Factories;


use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{

    public function definition()
    {
        return [
            'content' => $this->faker->paragraph,
            'blog_id' => Blog::factory(),
            'image_url' => $this->faker->imageUrl
        ];
    }
}
