<?php

namespace Database;

use Core\Database\Migration;
use Core\Database\Blueprint;
use Core\Database\Schema;

class CreateMigrationsTable extends Migration
{
    public static function up()
    {
        $table = new Blueprint();
        $tableName = 'migrations';
        $table->id();
        $table->string('name');
        $table->timestamps();
        Schema::create(
            $tableName,
            $table->getData()
        );
        parent::insertMigrate($tableName);
    }
}