<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Blog;
use App\Models\Comments;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'token' => env('token')
        ]);

        $blogs = Blog::factory()->count(5)->create([
            'user_id' => $user->id,
        ]);

        $blogs->each(function ($blog) use ($user) {
            $blog->posts()->createMany(
                Post::factory()
                    ->count(3)
                    ->make(['blog_id' => $blog->id, 'likes_count' => 5])
                    ->toArray()

            );
            $post = $blog->posts;
            $post->each(function ($post) use ($blog) {
                $post->comments()->createMany(
                    Comments::factory()->count(2)->make(['post_id' => $post->id])->toArray()
                );
                $post->likes()->createMany(
                    Like::factory()->count(3)->make(['post_id' => $post->id])->toArray()
                );
            });
        });

        User::factory()->count(2)->create();
    }
}
