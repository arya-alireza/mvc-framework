<?php

namespace Core\Database;

use Closure;
use Core\DB;

class Schema
{
    static function queryColumns($columns)
    {
        $q = "";
        foreach ($columns as $item) {
            if (is_null($item->len)) {
                $q .= "    `$item->name` $item->type";
            } else {
                $q .= "    `$item->name` $item->type($item->len)";
            }
            if (! is_null($item->opt)) $q .= " $item->opt";
            $q .= ",\n";
        }
        return $q;
    }

    static function queryKeys($keys)
    {
        $q = "";
        foreach ($keys as $item) {
            $q .= "    PRIMARY KEY (`$item`),\n";
        }
        return $q;
    }

    static function queryIndexes($table, $indexes)
    {
        $q = "";
        foreach ($indexes as $item) {
            $name = $item->name . "_index";
            if ($item->type == "index") {
                $q .= "    CREATE INDEX `$name` ON `$table`(`$item->name`)";
            } elseif ($item->type == "unique") {
                $q .= "    CREATE UNIQUE INDEX `$name` ON `$table`(`$item->name`)";
            }
        }
        return $q;
    }

    static function checkTable($table)
    {
        $q = "SHOW TABLES LIKE '$table'";
        $conn = new DB();
        $stmt = $conn->query($q);
        $stmt->execute();
        $r = $stmt->fetchAll();
        return count($r) == 1 ? true : false;
    }

    static function create($table, Closure $data)
    {
        $blueprint = new Blueprint($data);
        if (self::checkTable($table)) {
            echo "$table table has been exist! \n";
        } else {
            $q = "CREATE TABLE `$table` (\n";
            $q .= self::queryColumns($blueprint->getColumns());
            $q .= self::queryKeys($blueprint->getKeys());
            $q .= ");\n";
            $q = str_replace(",\n);", "\n);", $q);
            $conn = new DB();
            $stmt = $conn->query($q);
            $stmt->execute();
            if (count($blueprint->getIndexes()) > 0) self::createIndexes($table, $blueprint->getIndexes());
            echo "$table table has been created! \n";
        }
    }

    static function createIndexes($table, $indexes)
    {
        $q = self::queryIndexes($table, $indexes);
        $conn = new DB();
        $stmt = $conn->query($q);
        $stmt->execute();
    }

    static function drop($table)
    {
        if (self::checkTable($table)) {
            $q = "DROP TABLE `$table`";
            $conn = new DB();
            $stmt = $conn->query($q);
            $stmt->execute();
            echo "$table table has been dropped! \n";
        } else {
            echo "$table table not exist! \n";
        }
    }
}