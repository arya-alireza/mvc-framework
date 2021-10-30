<?php

namespace Core\Database;

use Core\DB;

class Migration
{
    static function insertMigrate($table)
    {
        $conn = new DB();
        $stmt = $conn->query("INSERT INTO `migrations`(`name`) VALUE('create_$table')");
        $stmt->execute();
    }
}