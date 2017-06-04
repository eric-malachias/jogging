<?php

use Illuminate\Database\Seeder;
use App\Repositories\RoleRepository;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $password = Hash::make('123456');
        DB::beginTransaction();

        try {
            $user = factory(User::class)->create([
                'name' => 'User',
                'email' => 'user@example.com',
                'password' => $password,
                'role_id' => app(RoleRepository::class)->findByNameOrFail('regular')->id,
            ]);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
