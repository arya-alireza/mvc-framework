<?php

namespace Database;

use Core\Database\Migration;
use Core\Database\Blueprint;
use Core\Database\Schema;

class CreateUsersTable extends Migration
{
    static $tableName = 'users';

    public static function up()
    {
        $table = new Blueprint();
        $table->id();
        $table->string('fname');
        $table->string('lname');
        $table->string('email');
        $table->unique('email');
        $table->timestamps();
        Schema::create(
            self::$tableName,
            $table->getData()
        );
        parent::insertMigrate(self::$tableName);
    }

    public static function down()
    {
        Schema::drop(self::$tableName);
        parent::deleteMigrate(self::$tableName);
    }
}