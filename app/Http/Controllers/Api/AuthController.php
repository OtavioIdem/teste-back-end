<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        /** @var User|null $user */
        $user = User::where('email', $request->validated()['email'])->first();

        if (! $user || ! Hash::check($request->validated()['password'], $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], 422);
        }

        $token = $user->createToken('spa')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        /** @var User|null $user */
        $user = $request->user();

        if ($user) {
            /** @var PersonalAccessToken|null $token */
            $token = $user->currentAccessToken();

            if ($token) {
                $token->delete(); // <- agora tipado, sem warning
            }
        }

        return response()->noContent();
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        /** @var User $user */
        $user = $request->user();
        $user->update($request->validated());
        return response()->json($user);
    }
}
