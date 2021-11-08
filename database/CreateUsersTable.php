<?php

namespace Database;

use Core\Database\Migration;
use Core\Database\Blueprint;
use Core\Database\Schema;

class CreateUsersTable extends Migration
{
    protected function up()
    {
        Schema::create('users', function(Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('email');
            $table->string('password');
            $table->unique('email');
            $table->timestamps();
        });
    }

    protected function down()
    {
        Schema::drop('users');
    }
}