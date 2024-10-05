<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('user_id');

        try {
            $request->validate([
                'content' => 'required|string'
            ]);

            Comments::create([
                'user_id' => $id,
                'post_id' => $request->id,
                'content' => $request->content
            ]);

            Log::channel('custom')->info('Post commented on', ['user_id' => $id, 'post_id' => $request->id, 'action' => 'create_comments', 'ip address' => $request->ip()]);
            return response()->json([
                'status' => 'success',
                'message' => 'Comment created successfully'
            ]);
        } catch (Exception $e) {
            Log::channel('custom_error')->error('On Post commenting', ['user_id' => $id, 'post_id' => $request->id, 'action' => 'create_comments', 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.'
            ], 500);
        }
    }
}
