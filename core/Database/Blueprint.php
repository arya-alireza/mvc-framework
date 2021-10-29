<?php

namespace Core\Database;

class Blueprint
{
    protected $columns;

    public function __construct()
    {
        $this->columns = [];
    }

    public function col($name, $type, $length)
    {
        $col = new \stdClass();
        $col->name = $name;
        $col->type = $type;
        $col->len = $length;
        array_push($this->columns, $col);
    }

    public function string($name)
    {
        $this->col($name, "varchar", 191);
    }

    public function getColumns()
    {
        return $this->columns;
    }
}