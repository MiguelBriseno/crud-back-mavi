<?php
class User {
    private $conn;
    private $table_name = "users";

    public int $id;
    public string $name;
    public string $lastname;
    public string $nickname;
    public string $email;
    public int $phone;
    public string $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login():bool {
        $query = "SELECT id, name, lastname, email, phone, password FROM " . $this->table_name . " WHERE nickname = :nickname LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $this->nickname = htmlspecialchars(strip_tags($this->nickname));

        $stmt->bindParam(':nickname', $this->nickname);

        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->lastname = $row['lastname'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $stored_password = $row['password'];

            if(password_verify($this->password, $stored_password)) {
                return true;
            }
        }

        return false;
    }

    public function getUserData(): ?array {
        $query = "SELECT id, name, lastname, nickname, email, phone FROM users WHERE nickname = :nickname";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nickname', $this->nickname);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user;
    }
}
?>