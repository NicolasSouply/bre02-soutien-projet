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
                email,
                password,
                role
            ) VALUES (
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
        $query->execute(['email' => $email]);
        
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            return null;
        }

        return new Users($user['id'], $user['email'], $user['password']);
    }

    public function findAllUsers(): array
    {
        $query = $this->db->prepare(
            "SELECT *
            FROM users"
        );

        $query->execute(); 

        $usersData = $query->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach ($usersData as $userData) {
            if (isset($userData['id'], $userData['email'], $userData['password'])) {
                $users[] = new Users($userData['id'], $userData['email'], $userData['password']);
            }
        }

        return $users;
    }

public function findUserById(int $id) : ?Users 
{
    $query = $this->db->prepare
    (
        "SELECT id
        FROM users
        WHERE id = :id"
    );

    $query->execute(['id' => $id]);
   
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user === false) {
        return null;
    }

    return new Users($user['id'], $user['email'], $user['password']); 
}

public function updateUser(Users $user) : ?Users
    {
        $query = $this->db->prepare(
            "UPDATE users
            SET email = :email, role = :role
            WHERE id = :id"
        );

        $parameters = [
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'id' => $user->getId()
        ];

        if ($query->execute($parameters)) {
            
            return $user;
        } else 
            {
            return null;
            }
    }
public function deleteUser(int $id) : void 
{
    $query = $this->db->prepare(
        "DELETE FROM users WHERE id = :id"
    );

    $parameters = [
        'id' => $id
    ];

    if (!$query->execute($parameters)) {

    }

}
}