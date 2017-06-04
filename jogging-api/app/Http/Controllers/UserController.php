<?php

namespace App\Http\Controllers;

use Hash;
use JWTAuth;
use App\Http\Requests\Request;
use App\Repositories\UserRepository;
use App\Http\Requests\UserSignUpRequest;
use App\Models\User;

class UserController extends Controller
{
    protected function respondWithToken($user)
    {
        return response()->ok([
            'id' => $user->id,
            'token' => JWTAuth::fromUser($user),
            'role' => $user->role->name,
        ]);
    }

    public function login(Request $request, UserRepository $userRepository)
    {
        $email = $request->email;
        $password = $request->password;

        $user = $userRepository->findByEmailOrFail($email);

        if (!Hash::check($password, $user->password)) {
            return response()->badRequest();
        }

        return $this->respondWithToken($user);
    }
    public function postOne(UserSignUpRequest $request, UserRepository $userRepository)
    {
        $user = $userRepository->create($request->only([
            'name',
            'email',
            'password',
        ]));

        return $this->respondWithToken($user);
    }
    public function getJogs(User $user)
    {
        $jogs = $user
            ->Jogs()
            ->paginate(10);

        return response()->ok($jogs);
    }
}
