<?php

namespace App\Model;

use App\Database;
use PDO;
use PDOException;

class Materia {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerTodos() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM materias ORDER BY nombre ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener materias: ' . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId(int $id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM materias WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener materia por ID: ' . $e->getMessage());
            return false;
        }
    }

    public function crear(string $nombre, string $codigo, string $descripcion, string $estado = 'ACTIVO') {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO materias (nombre, codigo, descripcion, estado)
                 VALUES (?, ?, ?, ?)"
            );
            return $stmt->execute([$nombre, $codigo, $descripcion, $estado]);
        } catch (PDOException $e) {
            error_log('Error al crear materia: ' . $e->getMessage());
            return false;
        }
    }

    public function actualizar(int $id, string $nombre, string $codigo, string $descripcion, string $estado = 'ACTIVO') {
        try {
            $stmt = $this->db->prepare(
                "UPDATE materias SET nombre = ?, codigo = ?, descripcion = ?, estado = ? WHERE id = ?"
            );
            return $stmt->execute([$nombre, $codigo, $descripcion, $estado, $id]);
        } catch (PDOException $e) {
            error_log('Error al actualizar materia: ' . $e->getMessage());
            return false;
        }
    }

    public function eliminar(int $id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM materias WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log('Error al eliminar materia: ' . $e->getMessage());
            return false;
        }
    }
}
