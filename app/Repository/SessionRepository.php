<?php

namespace PROGAMERANYARAN\PHP\LOGIN\Repository;

use PROGAMERANYARAN\PHP\LOGIN\Domain\Session;

class SessionRepository
{
    private \PDO $connection;

    /**
     * Class constructor.
     */
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Session $session): Session
    {
        $stmt = $this->connection->prepare("INSERT INTO sessions (id, userId) VALUES (?, ?)");
        $stmt->execute([$session->id, $session->userId]);
        return $session;
    }

    public function findById(int $id): ?Session
    {
        $stmt = $this->connection->prepare("SELECT id, userId FROM sessions WHERE id = ? ");
        $stmt -> execute([$id]);

        try {
            if($row = $stmt->fetch()){
                $session = new Session();
                $session->id = $row['id'];
                $session->userId = $row['userId'];
                return $session;
            }else{
                return null;
            }
        }finally{
          $stmt->closeCursor();
        }

    }

    public function deleteById(int $id): void
    {
        $stmt = $this->connection->prepare("DELETE * FROM sessions WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE * FROM sessions");
    }
}
