<?php

require_once('./../vendor/autoload.php');
$db = new mysqli('localhost', 'root', '', 'cms');
require("Post.class.php");

//loader pomaga ładować szablony
$loader = new Twig\Loader\FilesystemLoader('./../src/templates');
//inicjuje twiga
$twig = new Twig\Environment($loader);


?>