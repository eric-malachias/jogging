<?php

use Illuminate\Database\Seeder;
use App\Repositories\RoleRepository;
use App\Models\User;
use App\Models\Jog;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $user = factory(User::class)->create([
                'name' => 'User',
                'email' => 'user@example.com',
                'password' => '123456',
                'role_id' => app(RoleRepository::class)->findByNameOrFail('regular')->id,
            ]);

            factory(Jog::class, 50)->create([
                'owner_id' => $user->id,
            ]);

            $manager = factory(User::class)->create([
                'name' => 'Manager',
                'email' => 'manager@example.com',
                'password' => '123456',
                'role_id' => app(RoleRepository::class)->findByNameOrFail('manager')->id
            ]);

            $admin = factory(User::class)->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => '123456',
                'role_id' => app(RoleRepository::class)->findByNameOrFail('admin')->id
            ]);
        });
    }
}
