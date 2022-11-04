<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->input("token");
        if (!$token) {
            return \response()->json(
                [
                    'message' => "Unauthorized",
                    'error' => "Token not provided",
                ],
                400
            );
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET', 'iniceritanyastringrandom'), ['HS256']);
        } catch (\ExpiredException $th) {
            return response()->json([
                'message' => "Unauthorized",
                'error' => "token expired",
            ], 400);
        } catch (Exception $e) {
            //
            return response()->json([
                "error" => "An error while decoding token.",
            ], 400);

            $user = User::find($credentials->id);

            $request->auth = $user;
        }
        return $next($request);
    }
}
