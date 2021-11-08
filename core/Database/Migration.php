<?php

namespace Core\Database;

use Core\DB;

class Migration
{
    protected $table;

    protected $conn;

    public function __construct()
    {
        $this->conn = new DB();
        $this->setTable();
    }
    
    protected function setTable()
    {
        $table = get_class($this);
        $table = str_replace("Database\\Create", "", $table);
        $table = str_replace("Table", "", $table);
        $table = strtolower($table);
        $this->table = $table;
    }

    protected function getTable()
    {
        return $this->table;
    }

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    protected function insertMigrate()
    {
        $qCheck = "SELECT * FROM `migrations` WHERE `name` = 'create_$this->table'";
        $stmtCheck = $this->conn->query($qCheck);
        $stmtCheck->execute();
        $r = $stmtCheck->fetchAll();
        if (count($r) == 0) {
            $q = "INSERT INTO `migrations`(`name`) VALUE('create_$this->table');";
            $stmt = $this->conn->query($q);
            $stmt->execute();
        }
    }

    protected function deleteMigrate()
    {
        $q = "DELETE FROM `migrations` WHERE `name` = 'create_$this->table';";
        $stmt = $this->conn->query($q);
        $stmt->execute();
    }
}