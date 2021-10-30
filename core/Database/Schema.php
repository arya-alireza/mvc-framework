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

    static function create($table, $data)
    {
        $q = "CREATE TABLE `$table` (\n";
        $q .= self::queryColumns($data->columns);
        $q .= self::queryKeys($data->keys);
        $q .= ");\n";
        $q = str_replace(",\n);", "\n);", $q);
        $conn = new DB();
        $stmt = $conn->query($q);
        $stmt->execute();
        if (count($data->indexes) > 0) self::createIndexes($table, $data->indexes);
        echo "$table table has been created!";
    }

    static function createIndexes($table, $indexes)
    {
        $q = self::queryIndexes($table, $indexes);
        $conn = new DB();
        $stmt = $conn->query($q);
        $stmt->execute();
    }
}