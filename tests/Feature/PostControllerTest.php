<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $userToken;

    public function setUp(): void
    {
        parent::setUp();

        // Initialize $userToken here
        $this->userToken = 'Bearer ' . env('TOKEN');
    }
    /**
     * Test fetching all posts for a blog.
     */
    public function testFetchPosts()
    {
        $user = User::factory()->create(['token' => env('TOKEN')]);
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        Post::factory()->count(5)->create(['blog_id' => $blog->id]);

        $response = $this->withHeaders([
            'Authorization' => $this->userToken,
        ])->getJson("api/posts/{$blog->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'blog_id', 'content', 'image_url', 'created_at', 'updated_at']
                ]
            ]);
    }

    /**
     * Test creating a post for a blog.
     */
    public function testCreatePost()
    {
        $user = User::factory()->create(['token' => env('TOKEN')]);
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => $this->userToken,
        ])->postJson("api/post/{$blog->id}", [
            'content' => 'Test post content',
            'image' => 'test-image.jpg',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Post created successfully'
            ]);

        $this->assertDatabaseHas('posts', [
            'blog_id' => $blog->id,
            'content' => 'Test post content',
            'image_url' => 'test-image.jpg',
        ]);
    }

    /**
     * Test fetching a single post.
     */
    public function testShowSinglePost()
    {
        $user = User::factory()->create(['token' => env('TOKEN')]);
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);

        $response = $this->withHeaders([
            'Authorization' => $this->userToken,
        ])->getJson("api/post/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => 'success',
            ])
            ->assertJsonFragment([
                'id' => $post->id,
                'blog_id' => $post->blog_id,
                'content' => $post->content,
                'image_url' => $post->image_url,
            ]);
    }

    /**
     * Test updating a post.
     */
    public function testUpdatePost()
    {
        $user = User::factory()->create(['token' => env('TOKEN')]);
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);

        $response = $this->withHeaders([
            'Authorization' => $this->userToken,
        ])->putJson("api/post/{$post->id}", [
            'content' => 'Updated content',
            'image' => 'updated-image.jpg',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Post updated successfully.'
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'content' => 'Updated content',
            'image_url' => 'updated-image.jpg',
        ]);
    }

    /**
     * Test deleting a post.
     */
    public function testDeletePost()
    {
        $user = User::factory()->create(['token' => env('TOKEN')]);
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create(['blog_id' => $blog->id]);

        $response = $this->withHeaders([
            'Authorization' => $this->userToken,
        ])->deleteJson("api/post/{$post->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Post deleted successfully.'
            ]);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}
