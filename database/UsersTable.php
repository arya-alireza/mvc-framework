<?php

namespace Database;

use Core\Database\Blueprint;
use Core\Database\Schema;

class UsersTable
{
    public function up()
    {
        $table = new Blueprint();
        $table->id();
        $table->string('fname');
        $table->string('lname');
        $table->timestamps();
        Schema::create('users', $table->getColumns());
    }
}