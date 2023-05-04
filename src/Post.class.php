<?php
class Post {
    private int $id;
    private string $filename;
    private string $timestamp;
    private string $authorId;
    private string $authorName;
    
    
    function __construct(int $i, string $f, string $t, int $authorId)
    {
        $this->id = $i;
        $this->filename = $f;
        $this->timestamp = $t;
        $this->authorId = $authorId;
        //pobiera z bazy danych login autora
        global $db;
        $this->authorName = User::getNameById($this->authorId);
    }

    public function getId() {
        return $this->id;
    }
    public function getFilename() : string {
        return $this->filename;
    }
    public function getTimestamp() : string {
        return $this->timestamp;
    }
    public function getAuthorName() : string {
        return $this->authorName;
    }

    //ostatnio dodany obrazek
    static function getLast() : Post {
        //odwołuje się do bazy danych
        global $db;
        //przygotowuje kwerendę do bazy danych
        $query = $db->prepare("SELECT * FROM post ORDER BY timestamp DESC LIMIT 1");
        //wykonuje kwerende
        $query->execute();
        //pobiera wynik
        $result = $query->get_result();
        //przetwarza tablice asocjacyjną - bez pętli bo będzie tlko jeden
        $row = $result->fetch_assoc();
        //tworzy obiekt
        $p = new Post($row['id'], $row['filename'], $row['timestamp'], $row['userId']);
        //zwraca obiekt
        return $p;
    }

    //funkcja zwraca jedną stronę obrazków
    static function getPage(int $pageNumber = 1, int $postsPerPage = 10) : array {
        global $db;
        //kwerenda
        $query = $db->prepare("SELECT * FROM post WHERE removed = false ORDER BY timestamp DESC LIMIT ? OFFSET ?");
        //oblicza przesunięcie - numer strony * ilość zdjęć na stronie
        $offset = ($pageNumber-1)*$postsPerPage;
        //postawia do kwerendy
        $query->bind_param('ii', $postsPerPage, $offset);
        //wykonuje kwerendę
        $query->execute();
        //odbierz wyniki
        $result = $query->get_result();
        //tworzy tablice na obiekty
        $postsArray = array();
        //pobieraj wiersz po wierszu jako tablice assoc indeksowaną nazwami kolumn z mysql
        while($row = $result->fetch_assoc()){
            $post = new Post($row['id'], $row['filename'], $row['timestamp'], $row['userId']);
            array_push($postsArray, $post);
        }
        return $postsArray;
    }


    static function upload(string $tempFileName, int $userId) {
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
        
        //uzywa globalnego połączenia
        global $db;
        //tworzy kwerendę
        $query = $db->prepare("INSERT INTO post VALUES(NULL, ?, ?, ?, false)");
        //przygotowuje znacznik czasu dla bazy danych
        $dbTimestamp = date("Y-m-d H:i:s");
        //zapisuje dane do bazy
        $query->bind_param("ssi", $dbTimestamp, $hash, $userId);
        if(!$query->execute()){
            die("ERROR: Can't save file to database!");
        }
    }

    static function remove(int $id) : bool {
        global $db;
        $q = $db->prepare("UPDATE post SET removed = true WHERE id = ?");
        $q->bind_param('i', $id);
        if ($q->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

?>