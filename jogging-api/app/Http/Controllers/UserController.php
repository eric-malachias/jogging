<?php

namespace App\Http\Controllers;

use Hash;
use JWTAuth;
use App\Http\Requests\Request;
use App\Repositories\UserRepository;
use App\Http\Requests\UserEditRequest;
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
    public function postOne(UserEditRequest $request, UserRepository $userRepository)
    {
        $user = $userRepository->create($request->only([
            'name',
            'email',
            'password',
        ]));

        return $this->respondWithToken($user);
    }
    public function getJogs(User $user, Request $request)
    {
        $jogs = $user
            ->jogs()
            ->between($request->before, $request->after)
            ->latestFirst()
            ->paginate(10);

        return response()->ok($jogs);
    }
    public function deleteOne(User $user)
    {
        $user->delete();

        return response()->ok($user);
    }
    public function getAll(UserRepository $userRepository)
    {
        $users = $userRepository->paginate(10);

        return response()->ok($users);
    }
    public function putOne(UserEditRequest $request, User $user)
    {
        $user->fill($request->only([
            'name',
            'email',
            'password',
        ]));
        $user->save();

        return response()->ok($user);
    }
    public function getOne(User $user)
    {
        return response()->ok($user);
    }
}
