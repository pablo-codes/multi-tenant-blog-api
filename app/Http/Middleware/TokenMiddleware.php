<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

use Illuminate\Http\Request;

class TokenMiddleware
{

    public function handle(Request $request, Closure $next)
    {

        $token = $request->header('Authorization');
        $token = explode('Bearer ', $token, 2);
        $token = $token[1];
        $expectedToken = env('TOKEN');

        if ($token !== $expectedToken) {
            return response()->json(['error' => 'Unauthorized',], 401);
        }
        $id = User::query()->where('token', $token)->get(['id']);
        $request->merge(['user_id' => $id['0']['id']]);

        return $next($request,);
    }
}
