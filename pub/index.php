<?php

require_once("./../src/config.php");

use Steampixel\Route;

Route::add('/', function() {
    //strona wyświetlająca obrazki
    global $twig;
    $twig->display("index.html.twig");
});

Route::add('/upload', function() {
    //strona z formularzem do wgrywania memów
    global $twig;
    $twig->display('upload.html.twig');
});

Route::add('/upload', function() {
    
    global $twig;
    if(isset($_POST['submit'])){
        Post::upload($_FILES['uploadedFile']['tmp_name']);
    }
    $twig->display('index.html.twig');
}, 'post');

Route::run('/wykopek/pub/');
?>