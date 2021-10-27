<?php

namespace Core;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class View
{
    public static function render($file, $args = [])
    {
        $loader = new FilesystemLoader(__DIR__ . "/../views");
        $twig = new Environment($loader);
        echo $twig->render("$file.twig", $args);
    }
}