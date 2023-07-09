<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthServiceProvider;
use App\Models\User;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');
        $accessToken = str_replace('Bearer ', '', $token);
        // Query the database to check the access token
        $tokenExists = User::where('access_token', $accessToken)->exists();

        if (!$tokenExists) {
            return response()->json(['messsage' => 'Unauthorized','error' => true, 'status' => 'error'], 401);
        }

        return $next($request);
    }
}
