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
}