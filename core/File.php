<?php

namespace Core;

class File
{
    public static function getUrl()
    {
        if (Config::APP_URL != "") {
            $url = Config::APP_URL;
        } else {
            $url = $_SERVER['SERVER_PORT'] == 80 ? "http://" : "https://";
            $url .= $_SERVER['SERVER_NAME'];
            $url .= str_replace("index.php", "", $_SERVER['SCRIPT_NAME']);
        }
        return $url;
    }

    public static function save($file, $dir = "")
    {
        $path = "storage";
        $fileName = $file['name'];
        $fileTmp = $file['tmp_name'];
        move_uploaded_file($fileTmp, "$path/$dir/$fileName");
        return "$dir/$fileName";
    }

    public static function get($name, $dir = null)
    {
        $file = $dir ? "storage/$dir/$name" : "storage/$name";
        $url = self::getUrl();
        return file_exists($file) ? $url . $file : false;
    }
}