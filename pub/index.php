<?php

require_once("./../src/config.php");

use Steampixel\Route;

Route::add('/', function() {
    //strona wyświetlająca obrazki
    global $twig;
    //pobiera 10 najnowszych postów
    $postArray = Post::getPage();
    $twigData = array("postArray" => $postArray, "pageTitle" => "Strona główna");
    $twig->display("index.html.twig", $twigData);
});

Route::add('/upload', function() {
    //strona z formularzem do wgrywania memów
    global $twig;
    $twigData = array("pageTitle" => "Upload meme");
    $twig->display('upload.html.twig', $twigData);
});

Route::add('/upload', function() {
    
    global $twig;
    if(isset($_POST['submit'])){
        Post::upload($_FILES['uploadedFile']['tmp_name']);
    }
    
    header("Location: http://localhost/wykopek/pub");
}, 'post');

Route::add('/register', function() {
    global $twig;
    $twigData = array("pageTitle" => "Zarejestruj użytkownika");
    $twig->display("register.html.twig", $twigData);
});

Route::add('/register', function() {
    global $twig;
    if(isset($_POST['submit'])) {
        User::register($_POST['email'], $_POST['password']);
        header("Location: http://localhost/wykopek/pub");
    }
}, 'post');

Route::run('/wykopek/pub/');
?>