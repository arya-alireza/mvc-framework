<?php

namespace Core\Database;

use Core\DB;

class Migration
{
    static function insertMigrate($table)
    {
        $conn = new DB();
        $q = "DELETE FROM `migrations` WHERE `name` = 'create_$table';";
        $q .= "INSERT INTO `migrations`(`name`) VALUE('create_$table');";
        $stmt = $conn->query($q);
        $stmt->execute();
    }
}