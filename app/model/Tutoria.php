<?php

namespace App\Model;

use App\Database;
use PDO;
use PDOException;

class Tutoria {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerTodos() {
        try {
            $sql = "
                SELECT
                    t.id,
                    t.alumno_id,
                    t.tutor_id,
                    t.materia_id,
                    t.fecha,
                    t.hora_incio AS hora_inicio,
                    t.hora_fin,
                    t.num_sesiones,
                    t.estado,
                    CONCAT(a.nombres, ' ', a.apellidos) AS alumno_nombre,
                    CONCAT(u.nombres, ' ', u.apellidos) AS tutor_nombre,
                    m.nombre AS materia_nombre
                FROM tutorias t
                JOIN usuarios a ON a.id = t.alumno_id
                JOIN usuarios u ON u.id = t.tutor_id
                JOIN materias m ON m.id = t.materia_id
                ORDER BY t.fecha DESC, t.hora_incio ASC
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener tutorías: ' . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId(int $id) {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM tutorias WHERE id = ? LIMIT 1"
            );
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener tutoría por ID: ' . $e->getMessage());
            return false;
        }
    }

    public function crear(int $alumno_id, int $tutor_id, int $materia_id, string $fecha, string $hora_inicio, string $hora_fin, int $num_sesiones, string $estado = 'PENDIENTE') {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO tutorias (alumno_id, tutor_id, materia_id, fecha, hora_incio, hora_fin, num_sesiones, estado)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            return $stmt->execute([$alumno_id, $tutor_id, $materia_id, $fecha, $hora_inicio, $hora_fin, $num_sesiones, $estado]);
        } catch (PDOException $e) {
            error_log('Error al crear tutoría: ' . $e->getMessage());
            return false;
        }
    }

    public function actualizar(int $id, int $alumno_id, int $tutor_id, int $materia_id, string $fecha, string $hora_inicio, string $hora_fin, int $num_sesiones, string $estado = 'PENDIENTE') {
        try {
            $stmt = $this->db->prepare(
                "UPDATE tutorias
                 SET alumno_id = ?, tutor_id = ?, materia_id = ?, fecha = ?, hora_incio = ?, hora_fin = ?, num_sesiones = ?, estado = ?
                 WHERE id = ?"
            );
            return $stmt->execute([$alumno_id, $tutor_id, $materia_id, $fecha, $hora_inicio, $hora_fin, $num_sesiones, $estado, $id]);
        } catch (PDOException $e) {
            error_log('Error al actualizar tutoría: ' . $e->getMessage());
            return false;
        }
    }

    public function eliminar(int $id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM tutorias WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log('Error al eliminar tutoría: ' . $e->getMessage());
            return false;
        }
    }
}
