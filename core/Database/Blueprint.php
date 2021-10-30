<?php

namespace Core\Database;

class Blueprint
{
    protected $columns;

    public function __construct()
    {
        $this->columns = [];
    }

    public function col($name, $type, $length = null)
    {
        $col = new \stdClass();
        $col->name = $name;
        $col->type = $type;
        $col->len = $length;
        array_push($this->columns, $col);
    }

    public function id()
    {
        $this->col("id", "int", 11);
    }

    public function string($name)
    {
        $this->col($name, "varchar", 191);
        return $this;
    }

    public function integer($name)
    {
        $this->col($name, "int", "");
    }

    public function tinyInteger($name)
    {
        $this->col($name, "tinyint", "");
    }

    public function smallInteger($name)
    {
        $this->col($name, "smallint", "");
    }

    public function mediumInteger($name)
    {
        $this->col($name, "mediumint", "");
    }

    public function bigInteger($name)
    {
        $this->col($name, "bigint", "");
    }

    public function decimal($name)
    {
        $this->col($name, "decimal", "");
    }

    public function float($name)
    {
        $this->col($name, "float", "");
    }

    public function double($name)
    {
        $this->col($name, "double", "");
    }

    public function real($name)
    {
        $this->col($name, "real", "");
    }

    public function bit($name)
    {
        $this->col($name, "bit", "");
    }

    public function boolean($name)
    {
        $this->col($name, "boolean", "");
    }

    public function timestamps()
    {
        $this->col("created_at", "timestamp", "");
        $this->col("updated_at", "timestamp", "");
    }

    public function getColumns()
    {
        return $this->columns;
    }
}