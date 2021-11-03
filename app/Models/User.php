<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected static $table = "users";

    protected static $fillable = [
        'fname',
        'lname',
        'email',
        'password',
    ];

    public static function get()
    {
        $tb = self::$table;
        return parent::all($tb);
    }

    public static function find($id)
    {
        $tb = self::$table;
        return parent::single($tb, $id);
    }

    public static function findOrFail($id)
    {
        $tb = self::$table;
        return parent::singleFail($tb, $id);
    }

    public static function create($data)
    {
        $tb = self::$table;
        $fb = self::$fillable;
        return parent::insert($tb, $data, $fb);
    }

    public static function update($id, $data)
    {
        $tb = self::$table;
        $fb = self::$fillable;
        return parent::edit($tb, $data, $fb, $id);
    }

    public static function allWhere($where)
    {
        $tb = self::$table;
        $fb = self::$fillable;
        return parent::statement($tb, $where, 'all');
    }

    public static function singleWhere($where)
    {
        $tb = self::$table;
        $fb = self::$fillable;
        return parent::statement($tb, $where, 'single');
    }
}