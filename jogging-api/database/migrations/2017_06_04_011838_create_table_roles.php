<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRoles extends Migration
{
    const TABLE = 'roles';

    protected function addRole($name)
    {
        DB::table(static::TABLE)
            ->insert([
                'name' => $name,
            ]);
    }

    public function up()
    {
        Schema::create(static::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 10);
        });

        DB::transaction(function () {
            $this->addRole('admin');
            $this->addRole('manager');
            $this->addRole('regular');
        });
    }
    public function down()
    {
        Schema::drop(static::TABLE);
    }
}
