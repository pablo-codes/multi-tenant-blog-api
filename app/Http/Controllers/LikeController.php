<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LikeController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('user_id');

        try {


            Like::query()->updateOrCreate([
                'user_id' => $id,
                'post_id' => $request->id,
            ], [
                'user_id' => $id,
                'post_id' => $request->id,
            ]);

            Log::channel('custom')->info('Like created', ['user_id' => $id, 'post_id' => $request->id, 'action' => 'create_likes', 'ip address' => $request->ip()]);
            return response()->json([
                'status' => 'success',
                'message' => 'Like created successfully'
            ]);
        } catch (Exception $e) {
            Log::channel('custom_error')->error('On Like creation', ['user_id' => $id, 'post_id' => $request->id, 'action' => 'create_likes', 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred.'
            ], 500);
        }
    }
}
