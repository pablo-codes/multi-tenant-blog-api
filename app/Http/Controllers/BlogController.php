<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->user_id;

        try {
            $blogs = Blog::with(['posts'])->get();

            Log::channel('custom')->info('Blogs fetched', ['user_id' => $id, 'action' => 'fetch_blogs', 'ip address' => $request->ip()]);

            return response()->json([
                'status' => 'success',
                'data' => $blogs
            ]);
        } catch (Exception $e) {
            Log::channel('custom_error')->error('On Blogs fetching', ['user_id' => $id, 'action' => 'fetch_blogs', 'error' => $e->getMessage()]);

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
                'title' => 'required|string|max:255',
                'description' => 'required|string'
            ]);

            Blog::create([
                'user_id' => $id,
                'title' => $request->title,
                'description' => $request->description
            ]);

            Log::channel('custom')->info('Blog created', ['user_id' => $id, 'action' => 'create_blogs', 'ip address' => $request->ip()]);
            return response()->json([
                'status' => 'success',
                'message' => 'Blog created successfully'
            ]);
        } catch (Exception $e) {
            Log::channel('custom_error')->error('On Blog creation', ['user_id' => $id, 'action' => 'create_blogs', 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.'
            ], 500);
        }
    }


    public function show(Request $request, Blog $blog)
    {
        $id = $request->user_id;

        try {



            Log::channel('custom')->info('Blog single fetch', ['user_id' => $id, 'action' => 'single_fetch_blog', 'ip address' => $request->ip()]);

            return response()->json([
                'status' => 'success',
                'data' => $blog
            ]);
        } catch (Exception $e) {
            Log::channel('custom_error')->error('On Blog single fetch', ['user_id' => $id, 'action' => 'single_fetch_blog', 'ip address' => $request->ip(), 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.'
            ], 500);
        }
    }


    public function update(Request $request, Blog $blog)
    {
        $id = $request->user_id;

        try {

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
            ]);

            $blog->update([
                'title' => $request->title,
                'description' => $request->description
            ]);
            $blog->refresh();
            Log::channel('custom')->info('Blog updated', ['user_id' => $id, 'action' => 'update_blog', 'ip address' => $request->ip()]);

            return response()->json([
                'status' => 'success',
                'message' => 'Blog updated successfully.',
                'data' => $blog->get()
            ]);
        } catch (Exception $e) {
            Log::channel('custom_error')->error('On Blog update', ['user_id' => $id, 'action' => 'update_blog', 'ip address' => $request->ip(), 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.'
            ], 500);
        }
    }


    public function destroy(Request $request, Blog $blog)
    {
        $id = $request->user_id;

        try {

            $blog->delete();

            Log::channel('custom')->info('Blog deletion', ['user_id' => $id, 'action' => 'delete_blog', 'ip address' => $request->ip()]);

            return response()->json([
                'status' => 'success',
                'message' => 'Blog deleted successfully.'
            ], 200);
        } catch (Exception $e) {
            Log::channel('custom_error')->error('On Blog delete', ['user_id' => $id, 'action' => 'delete_blog', 'ip address' => $request->ip(), 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.'
            ], 500);
        }
    }
}
