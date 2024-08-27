<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $lastname;
    public $nickname;
    public $email;
    public $phone;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
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

    public function getUserData() {
        // Código para obtener los datos del usuario autenticado
        // Por ejemplo, puedes hacer una consulta a la base de datos para obtener la información
        $query = "SELECT id, name, lastname, nickname, email, phone FROM users WHERE nickname = :nickname";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nickname', $this->nickname);
        $stmt->execute();
        
        // Suponiendo que solo hay un resultado, obtenemos el primer resultado
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, lastname, nickname, email, phone, password) VALUES (:name, :lastname, :nickname, :email, :phone, :password)";
        
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->nickname = htmlspecialchars(strip_tags($this->nickname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':nickname', $this->nickname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':password', password_hash($this->password, PASSWORD_BCRYPT));

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name = :name, lastname = :lastname, nickname = :nickname, email = :email, phone = :phone, password = :password WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->nickname = htmlspecialchars(strip_tags($this->nickname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':nickname', $this->nickname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':password', password_hash($this->password, PASSWORD_BCRYPT));
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>