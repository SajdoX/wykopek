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
    if(User::isAuth()){
        $twigData['user'] = $_SESSION['user'];
        $twig->display('upload.html.twig', $twigData);
    }
    else {
        //zwraca 403 czyli zabronione
        header('HTTP/1.0 403 Forbidden');
    }
    
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
        if(User::login($_POST['email'], $_POST['password'])) {
            //jeśli logowanie przejdzie przekierowuje na główną stronę
            header("Location: http://localhost/wykopek/pub");
        }
        else {
            //jeśli logowanie nie przeszło wyświetla komunikat o błędzie
            $twigData = array("pageTitle" => "Sign in", "message" => "Incorrect login or password");
            $twig->display("login.html.twig", $twigData);
        }
    }
    
}, 'post');

Route::add('/admin', function() {
    global $twig;
    if(User::isAuth()) {
        $t = array( "postList" => Post::getPage(1, 100));
        $twig->display("admin.html.twig", $t);
    } else {
        http_response_code(403);
    }
});

Route::add('/admin/remove/([0-9]*)', function($id) {
    if(User::isAuth()) {
        Post::remove($id);
        header("Location: http://localhost/wykopek/pub/admin");
    } else {
        http_response_code(403);
    }
});

Route::add('/like/([0-9])', function($post_id) {
    if(!User::isAuth()) {
        http_response_code(403);
    } else {
    $user_id = $_SESSION['user']->getId();
    $like = new Likes($post_id, $user_id, 1);
    header("Location: http://localhost/wykopek/pub");
}});

Route::add('/dislike/([0-9])', function($post_id)  {
    if(!User::isAuth()) {
        http_response_code(403);
    } else {
    $user_id = $_SESSION['user']->getId();
    $like = new Likes($post_id, $user_id, -1);
    header("Location: http://localhost/wykopek/pub");
}});


Route::run('/wykopek/pub/');
?>