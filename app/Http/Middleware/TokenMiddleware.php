<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TokenMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        // Retrieve the token from the request headers
        $token = $request->header('Authorization');
        Log::channel('custom')->info('Token :' . $token);
        // Check if the token matches 'vg@123'
        if ($token !== env('TOKEN')) {
            // If the token is invalid, return a 403 Forbidden response
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // check's for user id
        $id = User::query()->where('token', $token)->get(['id']);
        $request->merge(['user_id' => $id['0']['id']]);

        // If the token is valid, proceed with the request
        return $next($request);
    }
}
