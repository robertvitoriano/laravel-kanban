<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($validated)) {
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
            'password' => 'required|confirmed|min:6',
            'avatar' => 'sometimes|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $folder = 'avatars/' . $validated['name'];
            $avatarPath = $request->file('avatar')->store($folder, 's3');
            Storage::disk('s3')->setVisibility($avatarPath, 'public');
            $validated['avatar'] = Storage::disk('s3')->url($avatarPath);
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

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|max:255|unique:users,name,' . auth()->id(),
            'avatar' => 'sometimes|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $folder = 'avatars/' . $validated['name'];
            $avatarPath = $request->file('avatar')->store($folder, 's3');
            Storage::disk('s3')->setVisibility($avatarPath, 'public');
            $validated['avatar'] = Storage::disk('s3')->url($avatarPath);
        }

        Auth::user()->update($validated);

        return response()->json([
            'message' =>  'Successfully updated!',
            'data' => $validated,
        ], 201);
    }

    public function handleGoogleLogin(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
        ]);

        try {
            $googleUserInfoUrl = 'https://www.googleapis.com/oauth2/v3/userinfo';
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $validated['token'],
            ])->get($googleUserInfoUrl);

            if ($response->failed()) {
                return response()->json(['error' => 'Invalid Google token'], 401);
            }

            $googleUser = $response->json();

            $user = User::firstOrCreate(
                ['email' => $googleUser['email']],
                [
                    'name' => $googleUser['name'],
                    'google_id' => $googleUser['sub'],
                    'avatar' => $googleUser['picture'],
                    'password' => bcrypt(Str::random(16)),
                ]
            );

            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'name' => $user->name,
                    'level' => $user->level,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed'], 500);
        }
    }
}
