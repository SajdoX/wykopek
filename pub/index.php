<?php
require('./../src/config.php');

?>

<form action="" method="post" enctype="multipart/form-data">
        <label for="uploadedFile">
            Select file to upload:
        </label><br>
        <input type="file" name="uploadedFile" id="uploadedFileInput" required><br>
        <input type="submit" value="Send file" name="submit"><br>
    </form>

<?php
    //sprawdza czy formularz został wysłany
    if(isset($_POST['submit'])){
        Post::upload($_FILES['uploadedFile']['tmp_name']);
    }

?>

Ostatni post:
<?php
var_dump(Post::getLast());

?>