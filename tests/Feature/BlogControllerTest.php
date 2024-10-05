<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase; // This will refresh the DB after each test

    protected $userToken;

    public function setUp(): void
    {
        parent::setUp();

        // Initialize $userToken here
        $this->userToken = 'Bearer ' . env('TOKEN');
    }

    /**
     * Test fetching all blogs for a user.
     */
    public function testFetchBlogs()
    {
        $user = User::factory()->create(['token' => env('TOKEN')]);
        Blog::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => $this->userToken,
        ])->getJson('api/blogs');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'user_id', 'title', 'description', 'created_at', 'updated_at']
                ]
            ]);
    }

    /**
     * Test creating a blog.
     */
    public function testCreateBlog()
    {
        $user = User::factory()->create(['token' => env('TOKEN')]);

        $response = $this->withHeaders([
            'Authorization' => $this->userToken,
        ])->postJson('api/blogs', [
            'title' => 'Test Blog',
            'description' => 'This is a test blog',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Blog created successfully'
            ]);

        $this->assertDatabaseHas('blogs', [
            'title' => 'Test Blog',
            'description' => 'This is a test blog',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test fetching a single blog.
     */
    public function testShowSingleBlog()
    {
        $user = User::factory()->create(['token' => env('TOKEN')]);
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => $this->userToken,
        ])->getJson("api/blogs/{$blog->id}");

        $response->assertStatus(200)->assertJsonFragment([
            'status' => 'success',
        ]);

        $response->assertJsonFragment([
            'id' => $blog->id,
            'user_id' => $blog->user_id,
            'title' => $blog->title,
            'description' => $blog->description,
        ]);
    }

    /**
     * Test updating a blog.
     */
    public function testUpdateBlog()
    {
        $user = User::factory()->create(['token' => env('TOKEN')]);
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => $this->userToken,
        ])->putJson("api/blogs/{$blog->id}", [
            'title' => 'Updated Blog Title',
            'description' => 'Updated description',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Blog updated successfully.'
            ]);

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'title' => 'Updated Blog Title',
            'description' => 'Updated description',
        ]);
    }

    /**
     * Test deleting a blog.
     */
    public function testDeleteBlog()
    {
        $user = User::factory()->create(['token' => env('TOKEN')]);
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => $this->userToken,
        ])->deleteJson("api/blogs/{$blog->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Blog deleted successfully.'
            ]);

        $this->assertDatabaseMissing('blogs', [
            'id' => $blog->id,
        ]);
    }
}
