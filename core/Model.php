<?php

namespace Core;

use Closure;
use Core\DB;

class Model
{
    protected $conn;

    protected $table;

    protected $fillable;

    protected $appends;

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

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    protected function realMethodName($name)
    {
        $names = explode("_", $name);
        $finalName = "";
        foreach ($names as $item) {
            $finalName .= ucwords($item);
        }
        return $finalName;
    }

    public function __get($name)
    {
        $rName = $this->realMethodName($name);
        if (method_exists($this, "get".$rName."Attribute")) {
            $str = "get".$rName."Attribute";
            return $this->$str();
        } elseif (method_exists($this, "rel$rName")) {
            $str = "rel$rName";
            return $this->$str;
        } elseif (method_exists($this, $name)) {
            return $this->$name();
        } else {
            return "Attribute not found!";
        }
    }

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    protected function all()
    {
        $stmt = $this->conn->query("SELECT * FROM `$this->table`");
        $stmt->execute();
        $col = [];
        foreach ($stmt->fetchAll() as $item) {
            $obj = new \stdClass();
            foreach ($item as $key => $val) {
                $obj->$key = $val;
            }
            array_push($col, $obj);
        }
        return $col;
    }

    protected function allWhere($where)
    {
        $stmt = $this->conn->query("SELECT * FROM `$this->table` WHERE $where");
        $stmt->execute();
        $col = [];
        foreach ($stmt->fetchAll() as $item) {
            $obj = $this->find($item->id);
            array_push($col, $obj);
        }
        return $col;
    }
    

    protected function find($id = null)
    {
        $stmt = $this->conn->query("SELECT * FROM `$this->table` WHERE `id`=:id");
        $stmt->execute([
            "id" => $id,
        ]);
        foreach ($stmt->fetch() as $key => $val) {
            $this->__set($key, $val);
        }
        return $this;
    }

    protected function findWhere($where)
    {
        $stmt = $this->conn->query("SELECT * FROM `$this->table` WHERE $where");
        $stmt->execute();
        foreach ($stmt->fetch() as $key => $val) {
            $this->__set($key, $val);
        }
        return $this;
    }

    protected function findOrFail($id)
    {
        $stmt = $this->conn->query("SELECT * FROM `$this->table` WHERE `id`=:id");
        $stmt->execute([
            "id" => $id,
        ]);
        if ($stmt->rowCount() == 1) {
            foreach ($stmt->fetch() as $key => $val) {
                $this->__set($key, $val);
            }
            return $this;
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

    protected function belongsTo($class, $key)
    {
        if (class_exists($class)) {
            $obj = new $class;
            return $obj->find($this->$key);
        } else {
            return false;
        }
    }

    protected function hasMany($class, $key)
    {
        if (class_exists($class)) {
            $obj = new $class;
            return $obj->allWhere("`$key` = '$this->id'");
        } else {
            return false;
        }
    }
}