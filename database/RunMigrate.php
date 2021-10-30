<?php

namespace Database;

use Database\CreateMigrationsTable;

class RunMigrate
{
    public static function run()
    {
        CreateMigrationsTable::up();
        $dir = realpath("database");
        $scans = array_diff(
            scandir($dir),
            array("...", "..", ".", "RunMigrate.php", "CreateMigrationsTable.php")
        );
        foreach ($scans as $item) {
            $obj = "Database\\" . str_replace(".php", "", $item);
            if (class_exists($obj)) $obj::up();
        }
    }
}