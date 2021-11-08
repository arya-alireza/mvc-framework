<?php

namespace Core;

use Core\DB;

class Model
{
    protected $conn;

    protected $table;

    protected $fillable;

    public function __construct()
    {
        $this->conn = new DB();
        $this->setTable();
    }

    protected $uncountable = [
        'audio',
        'bison',
        'cattle',
        'chassis',
        'compensation',
        'coreopsis',
        'data',
        'deer',
        'education',
        'emoji',
        'equipment',
        'evidence',
        'feedback',
        'firmware',
        'fish',
        'furniture',
        'gold',
        'hardware',
        'information',
        'jedi',
        'kin',
        'knowledge',
        'love',
        'metadata',
        'money',
        'moose',
        'news',
        'nutrition',
        'offspring',
        'plankton',
        'pokemon',
        'police',
        'rain',
        'recommended',
        'related',
        'rice',
        'series',
        'sheep',
        'software',
        'species',
        'swine',
        'traffic',
        'wheat',
    ];
    
    protected function setTable()
    {
        $table = get_class($this);
        $table = str_replace("App\\Models\\", "", $table);
        $table = strtolower($table);
        $table = in_array($table, $this->uncountable) ? $table : $table . "s";
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

    protected function all()
    {
        $stmt = $this->conn->query("SELECT * FROM `$this->table`");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    

    protected function find($id = null)
    {
        $stmt = $this->conn->query("SELECT * FROM `$this->table` WHERE `id`=:id");
        $stmt->execute([
            "id" => $id,
        ]);
        return $stmt->fetch();
    }

    protected function findOrFail($id)
    {
        $stmt = $this->conn->query("SELECT * FROM `$this->table` WHERE `id`=:id");
        $stmt->execute([
            "id" => $id,
        ]);
        if ($stmt->rowCount() == 1) {
            return $stmt->fetch();
        } else {
            return false;
        }
    }

    protected function create($data)
    {
        $columns = "";
        $values = "";
        $i = 0;
        $max = count($this->fillable) - 1;
        foreach ($this->fillable as $col) {
            if ($i < $max) {
                $columns .= "`$col`, ";
                $values .= ":$col, ";
            } else {
                $columns .= "`$col`";
                $values .= ":$col";
            }
            $i++;
        }
        $stmt = $this->conn->query("INSERT INTO `$this->table`($columns) VALUES($values)");
        return $stmt->execute($data) ? true : false;
    }

    protected function update($data, $id)
    {
        $columns = "";
        $i = 0;
        $max = count($this->fillable) - 1;
        foreach ($this->fillable as $col) {
            if ($i < $max) {
                $columns .= "`$col`=:$col, ";
            } else {
                $columns .= "`$col`=:$col";
            }
            $i++;
        }
        $stmt = $this->conn->query("UPDATE `$this->table` SET $columns WHERE `id`='$id'");
        return $stmt->execute($data) ? true : false;
    }

    protected function findWhere($where)
    {
        $stmt = $this->conn->query("SELECT * FROM `$this->table` WHERE $where");
        $stmt->execute();
        return $stmt->fetch();
    }
}