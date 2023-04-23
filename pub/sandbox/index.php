<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="uploadedFile">
            Select file to upload:
        </label><br>
        <input type="file" name="uploadedFile" id="uploadedFileInput" required><br>
        <input type="submit" value="Send file" name="submit"><br>
    </form>

    <?php
    //sprawdź czy formularz został wysłany
    if(isset($_POST['submit']))
    {
        //folder do którego trafiają zdjecia
        $targetDir = "img/";

        //pobiera nazwę pliku z $_FILES
        $sourceFileName = $_FILES['uploadedFile']['name'];

       //pobiera tymczasową ścieżkę do pliku na serwerze
       $tempUrl = $_FILES['uploadedFile']['tmp_name'];

        //sprawdza czy przesłany plik to obraz
        $imgInfo = getimagesize($tempUrl);
        if(!is_array($imgInfo)) {
            die("ERROR: File you try to upload is not an image!");
        }

        

        //wyciąga pierwotne rozszerzenie pliku
        //$sourceFileExtension = pathinfo($sourceFileName, PATHINFO_EXTENSION);

        //zmienia litery rozszerzenia na małe
        //$sourceFileExtension = strtolower($sourceFileExtension);
        //niepotrzebne - generujemy webp

        //generuje hash
        $hash = hash("sha256", $sourceFileName . hrtime(true));
        $newFileName = $hash . ".webp";

        //zaczytuje cały obraz z folderu tymczasowego do stringa
        $imageString = file_get_contents($tempUrl);

        //generujemy obraz jako obiekt klasy GDImage
        //@ przed nazwą funkcji ignoruje warning
        $gdImage = @imagecreatefromstring($imageString);

        //generuje pełny docelowy URL
        $targetUrl = $targetDir . $newFileName;

        //tworzy URL pliku na serwerze
        //$targetUrl = $targetDir . $sourceFileName;
        //wycofane na rzecz hasha

        if(file_exists($targetUrl)){
            die('ERROR: There is already file with the same name!');
        }

        //przesuwa plik do /img
        //move_uploaded_file($tempUrl, $targetUrl);
        //nieaktualne - generujemy webp
        imagewebp($gdImage, $targetUrl);

        $db = new mysqli('localhost', 'root', '', 'cms');
        $query = $db->prepare("INSERT INTO post VALUES(NULL, ?, ?)");
        $dbTimestamp = date("Y-m-d H:i:s");
        $query->bind_param("ss", $dbTimestamp, $hash);
        if(!$query->execute()){
            die("ERROR: Can't save file to database!");
        }

        echo "File uploaded!";
    }

    ?>
</body>
</html>