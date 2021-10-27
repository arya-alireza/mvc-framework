<?php
namespace Core;

use Core\DB;

class Model
{
    public static function all($table)
    {
        $conn = new DB;
        $stmt = $conn->query("SELECT * FROM `$table`");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function single($table, $id)
    {
        $conn = new DB;
        $stmt = $conn->query("SELECT * FROM `$table` WHERE `id` = :id");
        $stmt->execute([
            "id" => $id,
        ]);
        return $stmt->fetch();
    }

    public static function singleFail($table, $id)
    {
        $conn = new DB;
        $stmt = $conn->query("SELECT * FROM `$table` WHERE `id` = :id");
        $stmt->execute([
            "id" => $id,
        ]);
        if ($stmt->rowCount() == 1) {
            return $stmt->fetch();
        } else {
            return false;
        }
    }

    public static function insert($table, $data, $fillable)
    {
        $columns = "";
        $values = "";
        $i = 0;
        $max = count($fillable) - 1;
        foreach ($fillable as $col) {
            if ($i < $max) {
                $columns .= "`$col`,";
                $values .= ":$col,";
            } else {
                $columns .= "`$col`";
                $values .= ":$col";
            }
            $i++;
        }
        $conn = new DB;
        $stmt = $conn->query("INSERT INTO `$table`($columns) VALUES($values)");
        $stmt->execute($data);
        return $conn->lastId();
    }

    public static function edit($table, $data, $fillable, $id)
    {
        $columns = "";
        $i = 0;
        $max = count($fillable) - 1;
        foreach ($fillable as $col) {
            if ($i < $max) {
                $columns .= "`$col`=:$col,";
            } else {
                $columns .= "`$col`=:$col";
            }
            $i++;
        }
        $conn = new DB;
        $stmt = $conn->query("UPDATE `$table` SET $columns WHERE `id` = '$id'");
        $stmt->execute($data);
        return true;
    }
}