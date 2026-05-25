<?php

namespace model;

use PDO;
use PDOException;

class users
{

private PDO $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function FindById($email): array|false{
        try {
            $stmt = $this->pdo->prepare("SELECT id, nombres, apellidos, email, password, rol, estado FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al cargar tu información: " . $e->getMessage());
            return false;
        }
    }

    public function UpdatePassword(int $id, string $hash): bool
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            return $stmt->execute([$hash, $id]);
        } catch (PDOException $e) {
            error_log("Error al actualizar contraseña: " . $e->getMessage());
            return false;
        }
    }
}