<?php

use Illuminate\Database\Seeder;
use App\Repositories\RoleRepository;
use App\Models\User;
use App\Models\Jog;
use App\Models\Role;

class UserTableSeeder extends Seeder
{
    protected function getRoleId($name)
    {
        return app(RoleRepository::class)->findByNameOrFail($name)->id;
    }

    public function run()
    {
        DB::transaction(function () {
            $user = factory(User::class)->create([
                'name' => 'User',
                'email' => 'user@example.com',
                'password' => '123456',
                'role_id' => $this->getRoleId(Role::REGULAR),
            ]);

            factory(Jog::class, 50)->create([
                'owner_id' => $user->id,
            ]);

            factory(User::class)->create([
                'name' => 'Manager',
                'email' => 'manager@example.com',
                'password' => '123456',
                'role_id' => $this->getRoleId(Role::MANAGER),
            ]);

            factory(User::class)->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => '123456',
                'role_id' => $this->getRoleId(Role::ADMIN),
            ]);

            factory(User::class, 50)->create([
                'role_id' => $this->getRoleId(Role::REGULAR),
            ]);
        });
    }
}
