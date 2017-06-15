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
            'name' => $user->name,
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
    public function signUp(UserEditRequest $request, UserRepository $userRepository)
    {
        $user = $userRepository->create($request->intersect([
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
    public function getWeeklyJogReport(User $user)
    {
        $report = $user
            ->getWeeklyJogReport()
            ->paginate(10);

        return response()->ok($report);
    }
    public function deleteOne(User $user)
    {
        $user->delete();

        return response()->ok($user);
    }
    public function getAll(UserRepository $userRepository, Request $request)
    {
        $user = $request->user();

        $users = $userRepository
            ->with('role')
            ->when($user->isManager(), function ($query) {
                return $query->isRegular();
            })
            ->when($user->isAdmin(), function ($query) {
                return $query->isRegularOrManager();
            })
            ->orderByName()
            ->paginate(10);

        return response()->ok($users);
    }
    public function putOne(UserEditRequest $request, User $user)
    {
        $user->fill($request->intersect([
            'name',
            'email',
            'password',
            'role_id',
        ]));
        $user->save();

        return response()->ok($user);
    }
    public function getOne(User $user)
    {
        return response()->ok($user);
    }
    public function postOne(UserEditRequest $request, UserRepository $userRepository)
    {
        $user = $userRepository->create($request->intersect([
            'name',
            'email',
            'password',
            'role_id',
        ]));

        return response()->created($user);
    }
    public function search(Request $request, UserRepository $userRepository)
    {
        $user = $userRepository
            ->isRegular()
            ->filterByKeyword($request->keyword)
            ->orderByNameLength()
            ->firstOrFail();

        return response()->ok($user);
    }
}
