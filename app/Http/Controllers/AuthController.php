<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function register(RegisterRequest $request)
    {
        if($request->authority == 'owner' || $request->authority == 'admin' ) {
            if(auth()->user()->authority != 'admin') {
                return response()->json([
                    'message' => '権限がありません'
                ], 403);
            }
        };
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'authority' => $request->authority,
            'restaurant_id' => $request->restaurant_id,
        ]);
        event(new Registered($user));

        return response()->json(['message' => 'Successfully user create']);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function update(Request $request)
    {
        $update = [
            'restaurant_id' => $request->restaurant_id,
        ];
        $user = User::where('id', auth()->user()->id)->update($update);
        if ($user) {
            return response()->json([
                'message' => 'Updated successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        }
    }

    public function owner()
    {
        $items = User::with('restaurant')->where('authority', 'owner')->get();
        return response()->json([
            'data' => $items
        ], 200);
    }
    public function admin()
    {
        $items = User::with('restaurant')->where('authority', 'admin')->get();
        return response()->json([
            'data' => $items
        ], 200);
    }

}
