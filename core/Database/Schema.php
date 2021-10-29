<?php

namespace Core\Database;

use Core\DB;

class Schema
{
    static function query($blueprint)
    {
        $q = "";
        $i = 1;
        foreach ($blueprint as $item) {
            $q .= "$item->name $item->type($item->len)";
            if ($i != count($blueprint)) $q .= ",";
            $i++;
        }
        return $q;
    }

    static function create($table, $blueprint)
    {
        $q = "CREATE TABLE $table (";
        $q .= self::query($blueprint);
        $q .= ");";
        $conn = new DB();
        $stmt = $conn->query($q);
        $stmt->execute();
        echo "$table table has been created!";
    }
}