<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentsControllerTest extends TestCase
{

    use RefreshDatabase;

    protected $userToken;

    public function setUp(): void
    {
        parent::setUp();

        // Initialize $userToken here
        $this->userToken = env('TOKEN');
    }

    /**
     * Tests Comment creation.
     */

    public function testCreateCommentSuccessfully()
    {

        $user = User::factory()->create(['token' => env('TOKEN')]);
        $post = Post::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => $this->userToken,
        ])->postJson("api/comment/{$post->id}", [
            'content' => 'This is a test comment',
            'user_id' => $user->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Comment created successfully'
            ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment',
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);
    }
}
