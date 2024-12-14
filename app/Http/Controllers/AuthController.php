<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['create_token', 'create_user', 'login_user', 'all_user']]);
    }

    public function all_user(Request $request)
    {
        $user = User::all();
        return response()->json($user, 200);
    }

    public function create_user(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name" => "required",
            "password" => "required"
        ]);

        if ($validate->fails()) {
            response()->json([
                "status" => false,
                "message" => "Validation Error",
                "error" => $validate->errors()
            ], 401);
        }

        $user = User::create([
            "name" => $request->name,
            "password" => $request->password
        ]);

        return response()->json([
            "status" => true,
            "message" => "User created",
        ], 200);
    }

    public function login_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "password" => "required"
        ]);

        $auth = Auth::attempt([
            "name" => $request->name,
            "password" => $request->password,
        ]);

        if ($auth) {
            return response()->json([
                "status" => true,
                "message" => "successfully login"
            ], 200);
        }

        return response()->json([
            "status" => false,
            "message" => "login fail"
        ], 401);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create_token()
    {
        $credentials = request(['name', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout_token()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh_token()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 30
        ]);
    }
}
