<?php
class User {
    private int $id;
    private string $email;

    public function __construct(int $id, string $email) {
        $this->id = $id;
        $this->email = $email;
    }   


    //gettery
    public function getId() : int {
        return $this->id;
    }
    public function getName() : string {
        return $this->email;
    }

    public static function isAuth() : bool {
        //sprawdza czy w sesji jest coś o nazwie user
        if(isset($_SESSION['user'])){
            //sprawdza czy to coś jest instancją klasy user
            if($_SESSION['user'] instanceof User) {
                //użytkownik zalogowany
                return true;
            }
        }
        return false;
    }
            
        
    

    public static function getNameById(int $userId){
        global $db;
        $query = $db->prepare("SELECT email FROM user WHERE id = ? LIMIT 1");
        $query->bind_param('i', $userId);
        $query->execute();
        $result = $query->get_result();
        $row= $result->fetch_assoc();
        return $row['email'];
    }


    //rejestracja
    public static function register(string $email, string $password ) : bool {
        global $db;
        $query = $db->prepare("INSERT INTO user VALUES (NULL, ?, ?)");
        $passwordHash = password_hash($password, PASSWORD_ARGON2I);
        $query->bind_param('ss', $email, $passwordHash);
        return $query->execute();
    }
    //logowanie
    public static function login(string $email, string $password) : bool {
        global $db;
        $query = $db->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
        $query->bind_param('s', $email);
        $query->execute();
        $result = $query->get_result();

        if($result->num_rows == 0)
            return false;

        $row = $result->fetch_assoc();
        $passwordHash = $row['password'];
        //jeśli autoryzacja się powiedzie zapisuje użytkownika w sesji
        if(password_verify($password, $passwordHash)){
            //hasła są zgodne
            $u = new User($row['id'], $email);
            $_SESSION['user'] = $u;
            return true;
        }
        else {
            return false;
        }
    } 
}




?>