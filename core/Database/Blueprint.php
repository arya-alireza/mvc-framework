<?php

namespace Core\Database;

use Closure;

class Blueprint
{
    protected $columns;
    protected $indexes;
    protected $keys;

    public function __construct(Closure $callback)
    {
        $this->columns = [];
        $this->indexes = [];
        $this->keys = [];
        $callback($this);
    }

    public function col($name, $type, $length = null, $opt = null)
    {
        $col = new \stdClass();
        $col->name = $name;
        $col->type = strtoupper($type);
        $col->len = $length;
        $col->opt = is_null($opt) ? "NOT NULL" : $opt;
        array_push($this->columns, $col);
    }

    public function inx($name, $type)
    {
        $index = new \stdClass();
        $index->name = $name;
        $index->type = $type;
        array_push($this->indexes, $index);
    }

    public function id()
    {
        $this->col("id", "int", 11, "NOT NULL AUTO_INCREMENT");
        $this->primary("id");
        return $this;
    }

    public function string($name)
    {
        $this->col($name, "varchar", 191);
        return $this;
    }

    public function integer($name)
    {
        $this->col($name, "int");
        return $this;
    }

    public function unsignedInteger($name)
    {
        $this->col($name, "int", null, "NOT NULL UNSIGNED");
        return $this;
    }

    public function tinyInteger($name)
    {
        $this->col($name, "tinyint");
        return $this;
    }

    public function smallInteger($name)
    {
        $this->col($name, "smallint");
        return $this;
    }

    public function mediumInteger($name)
    {
        $this->col($name, "mediumint");
        return $this;
    }

    public function bigInteger($name)
    {
        $this->col($name, "bigint");
        return $this;
    }

    public function decimal($name)
    {
        $this->col($name, "decimal");
        return $this;
    }

    public function float($name)
    {
        $this->col($name, "float");
        return $this;
    }

    public function double($name)
    {
        $this->col($name, "double");
        return $this;
    }

    public function real($name)
    {
        $this->col($name, "real");
        return $this;
    }

    public function bit($name)
    {
        $this->col($name, "bit");
        return $this;
    }

    public function boolean($name)
    {
        $this->col($name, "boolean");
        return $this;
    }

    public function timestamps()
    {
        $this->col("created_at", "timestamp", null, "NULL DEFAULT CURRENT_TIMESTAMP");
        $this->col("updated_at", "timestamp", null, "on update CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
        return $this;
    }

    public function index($name)
    {
        $this->inx($name, 'index');
        return $this;
    }

    public function unique($name)
    {
        $this->inx($name, 'unique');
        return $this;
    }

    public function primary($name)
    {
        array_push($this->keys, $name);
        return $this;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getIndexes()
    {
        return $this->indexes;
    }

    public function getKeys()
    {
        return $this->keys;
    }

    public function getData()
    {
        $data = new \stdClass();
        $data->columns = $this->columns;
        $data->indexes = $this->indexes;
        $data->keys = $this->keys;
        return $data;
    }
}