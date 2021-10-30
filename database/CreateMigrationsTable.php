<?php

namespace Database;

use Core\Database\Migration;
use Core\Database\Blueprint;
use Core\Database\Schema;

class CreateMigrationsTable extends Migration
{
    static $tableName = 'migrations';

    public static function up()
    {
        $table = new Blueprint();
        $table->id();
        $table->string('name');
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