<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->user_id;

        try {

            $post = Post::where('blog_id', $request->blog_id)->get();

            Log::channel('custom')->info('Posts fetched', ['user_id' => $id, 'action' => 'fetch_posts', 'ip address' => $request->ip()]);

            return response()->json([
                'status' => 'success',
                'data' => $post
            ]);
        } catch (Exception $e) {
            Log::channel('custom_error')->error('On Posts fetching', ['user_id' => $id, 'action' => 'fetch_posts', 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.'
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $id = $request->user_id;

        try {
            $request->validate([
                'content' => 'required|string',
                'image' => 'required|string|max:255'
            ]);

            $post = Post::create([
                'blog_id' => $request->blog_id,
                'content' => $request->content,
                'image_url' => $request->image
            ]);


            Log::channel('custom')->info('Post created', ['user_id' => $id, 'action' => 'create_posts', 'ip address' => $request->ip()]);

            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully'
            ]);
        } catch (Exception $e) {
            Log::channel('custom_error')->error('On Post creation', ['user_id' => $id, 'action' => 'create_posts', 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.'
            ], 500);
        }
    }


    public function show(Request $request, Post $post)
    {
        $id = $request->user_id;

        try {

            $data = $post->with(['comments', 'likes'])->get();

            Log::channel('custom')->info('Post single fetch', ['user_id' => $id, 'action' => 'single_fetch_post', 'ip address' => $request->ip()]);

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (Exception $e) {
            Log::channel('custom_error')->error('On Post single fetch', ['user_id' => $id, 'action' => 'single_fetch_post', 'ip address' => $request->ip(), 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.'
            ], 500);
        }
    }


    public function update(Request $request, Post $post)
    {
        $id = $request->user_id;

        try {

            $request->validate([
                'content' => 'required|string',
                'image' => 'required|string|max:255'
            ]);



            $post->update([
                'content' => $request->content,
                'image_url' => $request->image
            ]);
            $post->refresh();

            Log::channel('custom')->info('Post updated', ['user_id' => $id, 'action' => 'update_post', 'ip address' => $request->ip()]);

            return response()->json([
                'status' => 'success',
                'message' => 'Post updated successfully.',
                'data' => $post->get()
            ]);
        } catch (Exception $e) {
            Log::channel('custom_error')->error('On Post update', ['user_id' => $id, 'action' => 'update_post', 'ip address' => $request->ip(), 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.'
            ], 500);
        }
    }


    public function destroy(Request $request, Post $post)
    {
        $id = $request->user_id;

        try {

            $post->delete();

            Log::channel('custom')->info('Post deletion', ['user_id' => $id, 'action' => 'delete_post', 'ip address' => $request->ip()]);

            return response()->json([
                'status' => 'success',
                'message' => 'Post deleted successfully.'
            ], 200);
        } catch (Exception $e) {
            Log::channel('custom_error')->error('On Post delete', ['user_id' => $id, 'action' => 'delete_post', 'ip address' => $request->ip(), 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.'
            ], 500);
        }
    }
}
