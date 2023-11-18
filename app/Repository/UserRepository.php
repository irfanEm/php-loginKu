<?php

namespace PROGAMERANYARAN\PHP\LOGIN\Repository;

use PDO;
use PROGAMERANYARAN\PHP\LOGIN\Domain\User;

class UserRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): User
    {
        $stmt = $this->connection->prepare("INSERT INTO users(id, name, password) VALUES (?,?,?)");
        $stmt->execute([$user->id, $user->username, $user->password]);
        return $user;
    }

    public function update(User $user): User
    {
        $stmt = $this->connection->prepare("UPDATE users set username = ?, password = ? WHERE id = ?");
        $stmt -> execute([$user->username, $user->password, $user->id]);
        
        return $user;
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->connection->prepare("SELECT id, username, password FROM users WHERE id = ?");
        $stmt->execute([$id]);

        try{
            if($row = $stmt->fetch()){
                $user = new User();
                $user->id = $row['id'];
                $user->username = $row['username'];
                $user->password = $row['password'];
                return $user;
            }
        }finally{
            $stmt->closeCursor();
        }
    }
    public function deleteAll(): void
    {
        $this->connection->exec("DELETE * FROM users");
    }
}
