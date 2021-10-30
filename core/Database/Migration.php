<?php

namespace Core\Database;

use Core\DB;

class Migration
{
    static function insertMigrate($table)
    {
        $conn = new DB();
        $qCheck = "SELECT * FROM `migrations` WHERE `name` = 'create_$table'";
        $stmtCheck = $conn->query($qCheck);
        $stmtCheck->execute();
        $r = $stmtCheck->fetchAll();
        if (count($r) == 0) {
            $q = "INSERT INTO `migrations`(`name`) VALUE('create_$table');";
            $stmt = $conn->query($q);
            $stmt->execute();
        }
    }

    static function deleteMigrate($table)
    {
        $conn = new DB();
        $q = "DELETE FROM `migrations` WHERE `name` = 'create_$table';";
        $stmt = $conn->query($q);
        $stmt->execute();
    }
}