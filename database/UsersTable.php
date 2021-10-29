<?php

namespace Database;

use Core\Database\Blueprint;
use Core\Database\Schema;

class UsersTable
{
    public function up()
    {
        $table = new Blueprint();
        $table->string('fname');
        $table->string('lname');
        Schema::create('users', $table->getColumns());
    }
}