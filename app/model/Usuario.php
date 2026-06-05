<?php

namespace App\Model;

use App\Database;
use PDO;
use PDOException;

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    private function campoExiste(string $campo): bool {
        try {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = 'usuarios'
                   AND COLUMN_NAME = ?"
            );
            $stmt->execute([$campo]);
            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Error al verificar columna usuarios: ' . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodos() {
        try {
            $columns = ['id', 'nombres', 'apellidos', 'email', 'rol', 'estado'];
            if ($this->campoExiste('telefono')) {
                $columns[] = 'telefono';
            }

            $stmt = $this->db->prepare('SELECT ' . implode(', ', $columns) . ' FROM usuarios ORDER BY apellidos ASC, nombres ASC');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener usuarios: ' . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId(int $id) {
        try {
            $columns = ['id', 'nombres', 'apellidos', 'email', 'rol', 'estado'];
            if ($this->campoExiste('telefono')) {
                $columns[] = 'telefono';
            }

            $stmt = $this->db->prepare('SELECT ' . implode(', ', $columns) . ' FROM usuarios WHERE id = ? LIMIT 1');
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener usuario por ID: ' . $e->getMessage());
            return false;
        }
    }

    public function crear(string $nombres, string $apellidos, string $email, string $password, string $rol = 'alumno', string $estado = 'ACTIVO', ?string $telefono = null) {
        try {
            $fields = ['nombres', 'apellidos', 'email', 'password', 'rol', 'estado'];
            $values = [$nombres, $apellidos, $email, password_hash($password, PASSWORD_DEFAULT), $rol, $estado];

            if ($telefono !== null && $this->campoExiste('telefono')) {
                $fields[] = 'telefono';
                $values[] = $telefono;
            }

            $placeholders = implode(', ', array_fill(0, count($fields), '?'));
            $stmt = $this->db->prepare('INSERT INTO usuarios (' . implode(', ', $fields) . ') VALUES (' . $placeholders . ')');
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log('Error al crear usuario: ' . $e->getMessage());
            return false;
        }
    }

    public function actualizar(int $id, string $nombres, string $apellidos, string $email, string $rol = 'alumno', string $estado = 'ACTIVO', ?string $password = null, ?string $telefono = null) {
        try {
            $fields = ['nombres = ?', 'apellidos = ?', 'email = ?', 'rol = ?', 'estado = ?'];
            $values = [$nombres, $apellidos, $email, $rol, $estado];

            if ($password !== null && $password !== '') {
                $fields[] = 'password = ?';
                $values[] = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($telefono !== null && $this->campoExiste('telefono')) {
                $fields[] = 'telefono = ?';
                $values[] = $telefono;
            }

            $values[] = $id;
            $stmt = $this->db->prepare('UPDATE usuarios SET ' . implode(', ', $fields) . ' WHERE id = ?');
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log('Error al actualizar usuario: ' . $e->getMessage());
            return false;
        }
    }

    public function eliminar(int $id) {
        try {
            $stmt = $this->db->prepare('DELETE FROM usuarios WHERE id = ?');
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log('Error al eliminar usuario: ' . $e->getMessage());
            return false;
        }
    }
}
