<?php

namespace Core\Database;

use Closure;
use Core\DB;

class Schema
{
    static function query($blueprint)
    {
        $q = "";
        $i = 1;
        foreach ($blueprint as $item) {
            if ($item->len != "") {
                $q .= "    `$item->name` $item->type($item->len)";
            } else {
                $q .= "    `$item->name` $item->type";
            }
            if ($item->name == 'id') $q .= " NOT NULL AUTO_INCREMENT";
            if ($i != count($blueprint)) $q .= ",\n";
            if ($item->name == 'id') $q .= "    PRIMARY KEY (id),\n";
            $i++;
        }
        return $q;
    }

    static function create($table,  $blueprint)
    {
        $q = "CREATE TABLE `$table` (\n";
        $q .= self::query($blueprint);
        $q .= "\n);";
        $conn = new DB();
        $stmt = $conn->query($q);
        $stmt->execute();
        echo "$table table has been created!";
    }
}