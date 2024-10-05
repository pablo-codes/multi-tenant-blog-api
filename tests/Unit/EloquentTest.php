<?php

namespace Tests\Unit;

use App\Models\Blog;
use App\Models\Comments;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Blog-User relationship.
     */
    public function testBlogBelongsToUser()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $blog->user);
        $this->assertEquals($user->id, $blog->user->id);
    }

    /**
     * Test Blog-Post relationship.
     */
    public function testBlogHasManyPosts()
    {
        $blog = Blog::factory()->create();
        Post::factory()->count(3)->create(['blog_id' => $blog->id]);

        $this->assertInstanceOf(Post::class, $blog->posts->first());
        $this->assertCount(3, $blog->posts);
    }

    /**
     * Test Post-Blog relationship.
     */
    public function testPostBelongsToBlog()
    {
        $blog = Blog::factory()->create();
        $post = Post::factory()->create(['blog_id' => $blog->id]);

        $this->assertInstanceOf(Blog::class, $post->blog);
        $this->assertEquals($blog->id, $post->blog->id);
    }

    /**
     * Test Post-Comments relationship.
     */
    public function testPostHasManyComments()
    {
        $post = Post::factory()->create();
        Comments::factory()->count(2)->create(['post_id' => $post->id]);

        $this->assertInstanceOf(Comments::class, $post->comments->first());
        $this->assertCount(2, $post->comments);
    }

    /**
     * Test Post-Likes relationship.
     */
    public function testPostHasManyLikes()
    {
        $post = Post::factory()->create();
        Like::factory()->count(5)->create(['post_id' => $post->id]);

        $this->assertInstanceOf(Like::class, $post->likes->first());
        $this->assertCount(5, $post->likes);
    }

    /**
     * Test Comment-Post relationship.
     */
    public function testCommentBelongsToPost()
    {
        $post = Post::factory()->create();
        $comment = Comments::factory()->create(['post_id' => $post->id]);

        $this->assertInstanceOf(Post::class, $comment->post);
        $this->assertEquals($post->id, $comment->post->id);
    }

    /**
     * Test Like-Post relationship.
     */
    public function testLikeBelongsToPost()
    {
        $post = Post::factory()->create();
        $like = Like::factory()->create(['post_id' => $post->id]);

        $this->assertInstanceOf(Post::class, $like->post);
        $this->assertEquals($post->id, $like->post->id);
    }

    /**
     * Test User-Blogs relationship.
     */
    public function testUserHasManyBlogs()
    {
        $user = User::factory()->create();
        Blog::factory()->count(2)->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Blog::class, $user->blogs->first());
        $this->assertCount(2, $user->blogs);
    }

    /**
     * Test User-Posts relationship.
     */
    public function testUserHasManyPosts()
    {
        $blog = Blog::factory()->create();
        Post::factory()->count(3)->create(['blog_id' => $blog->id]);

        $this->assertInstanceOf(Post::class, $blog->posts->first());
        $this->assertCount(3, $blog->posts);
    }

    /**
     * Test User-Comments relationship.
     */
    public function testUserHasManyComments()
    {
        $user = User::factory()->create();
        Comments::factory()->count(4)->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Comments::class, $user->comments->first());
        $this->assertCount(4, $user->comments);
    }

    /**
     * Test User-Likes relationship.
     */
    public function testUserHasManyLikes()
    {
        $user = User::factory()->create();
        Like::factory()->count(6)->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Like::class, $user->likes->first());
        $this->assertCount(6, $user->likes);
    }
}
