<?php

namespace App\Http\Controllers;

use Hash;
use JWTAuth;
use App\Http\Requests\Request;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    public function login(Request $request, UserRepository $userRepository)
    {
        $email = $request->email;
        $password = $request->password;

        $user = $userRepository->findByEmailOrFail($email);

        if (!Hash::check($password, $user->password)) {
            return response()->badRequest();
        }

        return response()->ok([
            'id' => $user->id,
            'token' => JWTAuth::fromUser($user),
            'role' => $user->role->name,
        ]);
    }
}
