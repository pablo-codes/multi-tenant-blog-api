<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeControllerTest extends TestCase
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
     * Test creating or updating a like successfully.
     */
    public function testCreateOrUpdateLikeSuccessfully()
    {

        $user = User::factory()->create(['token' => env('TOKEN')]);
        $post = Post::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => $this->userToken,
        ])->postJson("api/like/{$post->id}", [
            'user_id' => $user->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Like created successfully'
            ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);
    }
}
