<?php
namespace App\Model;

use PDO;

class Tutorial {
    private $db;

    public function __construct() {
        $this->db = \App\Database::getConnection();
    }

    /**
     * Obtiene las tutorías asignadas a un usuario (ya sea como alumno o como tutor)
     */
    public function obtenerPorUsuario($usuario_id) {
        $sql = "
            SELECT 
                t.id,
                t.fecha,
                t.hora_incio AS hora_inicio,
                t.hora_fin,
                t.estado,
                m.nombre AS materia_nombre
            FROM tutorias t
            JOIN materias m ON m.id = t.materia_id
            WHERE t.alumno_id = ? OR t.tutor_id = ?
            ORDER BY t.fecha DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDatosCertificado($tutoria_id) {
        $sql = "
            SELECT
                t.id,
                t.fecha,
                t.estado,
                t.alumno_id,
                m.nombre AS materia_nombre,
                CONCAT(al.nombres, ' ', al.apellidos) AS alumno_nombre,
                CONCAT(tu.nombres, ' ', tu.apellidos) AS tutor_nombre
            FROM tutorias t
            JOIN materias m ON m.id = t.materia_id
            JOIN usuarios al ON al.id = t.alumno_id
            JOIN usuarios tu ON tu.id = t.tutor_id
            WHERE t.id = ?
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tutoria_id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}