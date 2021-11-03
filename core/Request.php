<?php

namespace Core;

class Request
{
    public $req;

    public function __construct()
    {
        $this->req = new \stdClass();
        foreach ($_REQUEST as $key => $val)
        {
            if ($val != "") $this->req->$key = $val;
        }
    }

    public function all()
    {
        return (array) $this->req;
    }

    public function __get($name)
    {
        return $this->req->$name;
    }
}