<?php

namespace Core;

use PDO;
use Core\Config;

class DB
{
    protected $conn;

    public function __construct()
    {
        $this->conn = new PDO("mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME . ";charset=utf8;", Config::DB_USER, Config::DB_PASS);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    public function query($q)
    {
        return $this->conn->prepare($q);
    }

    public function lastId()
    {
        return $this->conn->lastInsertId();
    }
}