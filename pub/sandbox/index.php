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
        <input type="file" name="uploadedFile" id="uploadedFileInput"><br>
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

        //sprawdź czy przesłany plik to obraz
        $imgInfo = getimagesize($tempUrl);
        if(!is_array($imgInfo)) {
            die("ERROR: File you try to upload is not a image!");
        }

        //tworzy URL pliku na serwerze
        $targetUrl = $targetDir . $sourceFileName;

        if(file_exists($targetUrl)){
            die('ERROR: There is already file with the same name!');
        }

        //przesuwa plik do /img
        move_uploaded_file($tempUrl, $targetUrl);

        echo "File uploaded!";
    }

    ?>
</body>
</html>