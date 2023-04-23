<?php
class Post {
    static function upload(string $tempFileName) {
        //deklaruje folder do którego będą zaczytywane obrazy
        $targetDir = "img/";

        //sprawdza czy przesłany plik to obraz
        $imgInfo = getimagesize($tempFileName);
        //jeżeli $imgInfo nie jest tablicą to nie jest to obraz
        if(!is_array($imgInfo)) {
            die("ERROR: File you try to upload is not an image!");
        
        }
        //generuje losową liczbę o długości 5 znaków
        //znacznik czasu z dokładnością do ms
        $randomNumber = rand(10000, 99999) . hrtime(true);

        //generuje hash
        $hash = hash("sha256", $randomNumber);

        //tworzy docelowy url pliku graficznego na serwerze
        $newFileName = $targetDir . $hash . ".webp";

        //sprawdza czy plik o tej samej nawzie już nie istnieje
        if(file_exists($newFileName)){
            die('ERROR: There is already file with the same name!');
        }
        //zaczytuje cały obraz z folderu tymczasowego do stringa
        $imageString = file_get_contents($tempFileName);

        //generuje obraz jako obiekt klasy GDImage
        //@ przed nazwą funkcji ignoruje warning
        $gdImage = @imagecreatefromstring($imageString);

        //zapisuje w formacie webp
        imagewebp($gdImage, $newFileName);
    }
}

?>