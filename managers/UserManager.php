<?php

class UserManager extends AbstractManager {

    public function __construct() {
        // J'appelle le constructeur de l'AbstractManager pour qu'il initialise la connexion à la DB
        parent::__construct();
    }

    public function createUser(Users $user) : ?Users
    {
        $query = $this->db->prepare(
            "INSERT INTO users (
                id,
                email,
                password,
                role
            ) VALUES (
                NULL,
                :email,
                :password,
                :role
            
            )"
        );
        $parameters = [
            
            'email' => $user->getEmail(),
 
            'password' => $user->getPassword(),
            'role' => $user->getRole()
        ];
    
  
        if ($query->execute($parameters)) {

            $user->setId($this->db->lastInsertId());
            return $user;
        } else {

            $errorInfo = $query->errorInfo();
            echo "Erreur lors de la création de l'utilisateur : " . implode(", ", $errorInfo);
            return null;
        }
    }
    
public function findUserByEmail(string $email): ?Users
{
    $query = $this->db->prepare(
        "SELECT *
        FROM users
        WHERE email = :email"
    );
    $parameters = [
        "email" => $email
    ];
    $query->execute($parameters);

    if ($query->rowCount() === 1) {
        $userData = $query->fetch(PDO::FETCH_ASSOC);

        if (isset($userData["id"], $userData["email"], $userData["password"], $userData["role"])) {
            $user = new Users($userData["email"], $userData["password"], $userData["role"]);
            $user->setId($userData["id"]);
            return $user;
        } else {
            return null; 
        }
    } else {
        return null;
    }
}
}
