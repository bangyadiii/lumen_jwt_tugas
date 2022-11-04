<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    protected function jwt(User $user)
    {
        $payload = [
            'iis' => "lument-jwt",
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60 * 60,
        ];
        return JWT::encode($payload, \env("JWT_SECRET", "iniceritanyastringrandom"), "HS256");
    }

    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return \response()->json([
                "message" => "Email not found in our record",
            ], 400);
        }
        if (!Hash::check($request->password, $user->password)) {
            return \response()->json([
                "message" => "Wrong password",
            ], 400);
        }
        return \response()->json([
            "token" => $this->jwt($user),
        ]);
    }

    public function me(Request $request)
    {
        return \response()->json([
            "user" => $request->auth
        ]);
    }

    //
}
