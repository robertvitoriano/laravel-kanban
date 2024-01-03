<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!Auth::attempt($validated)){
            return response()->json([
                'message' => 'Login information invalid'
            ], 401);
        }

        $user = User::where('email', $validated['email'])->first();

        return response()->json([
            'access_token' => $user->createToken('api_token')->plainTextToken,
            'token_type' => 'Bearer',
            'user' =>  [
                'name' => $user->name,
                'level' => $user->level,
                'email' => $user->email,
                'avatar' => $user->avatar,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:users,name',
            'email' => 'required|max:255|unique:users,email',
            'password' => 'required|confirmed| min:6',
            'avatar' => 'sometimes|image|max:2048',
            ]);

        if ($request->hasFile('avatar')) {
            $folder = 'avatars/' . $validated['name'];
            $avatarPath = $request->file('avatar')->store($folder, 's3');
            Storage::disk('s3')->setVisibility($avatarPath, 'public');
            Storage::disk('s3')->url($avatarPath);
            $validated['avatar'] = env('AWS_BUCKET').'.s3.amazonaws.com/'.$avatarPath;
        }

        $user = User::create($validated);
        return response()->json([
            'access_token' => $user->createToken('api_token')->plainTextToken,
            'token_type' => 'Bearer',
            'user' =>  [
                'name' => $user->name,
                'level' => $user->level,
                'email' => $user->email,
                'avatar' => $user->avatar,
            ],
        ], 201);
    }

}
