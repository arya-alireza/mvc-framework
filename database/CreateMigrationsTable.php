<?php

namespace Database;

use Core\Database\Migration;
use Core\Database\Blueprint;
use Core\Database\Schema;

class CreateMigrationsTable extends Migration
{
    protected function up()
    {
        Schema::create('migrations', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    protected function down()
    {
        Schema::drop('migrations');
    }
}