<?php

require_once("./../src/config.php");

session_start();

use Steampixel\Route;



Route::add('/', function() {
    //strona wyświetlająca obrazki
    global $twig;
    //pobiera 10 najnowszych postów
    $postArray = Post::getPage();
    $twigData = array("postArray" => $postArray, "pageTitle" => "Main site",);
    if(isset($_SESSION['user'])){
        $twigData['user'] = $_SESSION['user'];
    }
    $twig->display("index.html.twig", $twigData);
});

Route::add('/upload', function() {
    //strona z formularzem do wgrywania memów
    global $twig;
    $twigData = array("pageTitle" => "Upload meme");
    if(isset($_SESSION['user'])){
        $twigData['user'] = $_SESSION['user'];
    }
    $twig->display('upload.html.twig', $twigData);
});

Route::add('/upload', function() {
    
    global $twig;
    if(isset($_POST['submit'])){
        $tempFileName = $_FILES['uploadedFile']['tmp_name'];
        Post::upload($tempFileName, $_POST['userId']);
    }
    
    header("Location: http://localhost/wykopek/pub");
}, 'post');

Route::add('/register', function() {
    global $twig;
    $twigData = array("pageTitle" => "Sign up");
    $twig->display("register.html.twig", $twigData);
});

Route::add('/register', function() {
    global $twig;
    if(isset($_POST['submit'])) {
        User::register($_POST['email'], $_POST['password']);
        header("Location: http://localhost/wykopek/pub");
    }
}, 'post');

Route::add('/login', function(){
    global $twig;
    $twigData = array("pageTitle" => "Sign in");
    $twig->display("login.html.twig", $twigData);
});

Route::add('/login', function() {
    global $twig;
    if(isset($_POST['submit'])) {
        User::login($_POST['email'], $_POST['password']);
    }
    header("Location: http://localhost/wykopek/pub");
    
}, 'post');

Route::run('/wykopek/pub/');
?>