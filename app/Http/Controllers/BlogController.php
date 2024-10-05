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
        $id = $request->input('user_id');

        try {
            $blogs = Blog::query()->where('user_id', $id)->with(['posts'])->get();

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


    public function create(Request $request)
    {
        $id = $request->input('user_id');

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


    public function show(Request $request)
    {
        $id = $request->input('user_id');

        try {

            $blog = Blog::query()->where('id', $request->id)->where('user_id', $id)->first();

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


    public function update(Request $request)
    {
        $id = $request->input('user_id');

        try {

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
            ]);

            Blog::query()->where('id', $request->id)->where('user_id', $id)->update([
                'title' => $request->title,
                'description' => $request->description
            ]);

            Log::channel('custom')->info('Blog updated', ['user_id' => $id, 'action' => 'update_blog', 'ip address' => $request->ip()]);

            return response()->json([
                'status' => 'success',
                'message' => 'Blog updated successfully.'
            ]);
        } catch (Exception $e) {
            Log::channel('custom_error')->error('On Blog update', ['user_id' => $id, 'action' => 'update_blog', 'ip address' => $request->ip(), 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.'
            ], 500);
        }
    }


    public function delete(Request $request)
    {
        $id = $request->input('user_id');

        try {

            Blog::query()->where('id', $request->id)->where('user_id', $id)->delete();

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
