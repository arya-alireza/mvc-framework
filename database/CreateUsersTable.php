<?php

namespace Database;

use Core\Database\Migration;
use Core\Database\Blueprint;
use Core\Database\Schema;

class CreateUsersTable extends Migration
{
    public static function up()
    {
        $table = new Blueprint();
        $tableName = 'users';
        $table->id();
        $table->string('fname');
        $table->string('lname');
        $table->string('email');
        $table->unique('email');
        $table->timestamps();
        Schema::create(
            $tableName,
            $table->getData()
        );
        parent::insertMigrate($tableName);
    }
}