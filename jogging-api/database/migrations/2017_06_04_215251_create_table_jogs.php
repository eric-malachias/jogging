<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableJogs extends Migration
{
    const TABLE = 'jogs';

    public function up()
    {
        Schema::create(static::TABLE, function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('started_at');
            $table->datetime('ended_at');
            $table->unsignedInteger('distance');
            $table->unsignedInteger('owner_id');
            $table->timestamps();

            $table
                ->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
    public function down()
    {
        Schema::drop(static::TABLE);
    }
}
