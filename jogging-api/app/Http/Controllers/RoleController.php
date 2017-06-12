<?php

namespace App\Http\Controllers;

use App\Repositories\RoleRepository;

class RoleController extends Controller
{
    public function getAll(RoleRepository $roleRepository)
    {
        return response()->ok($roleRepository->get());
    }
}
