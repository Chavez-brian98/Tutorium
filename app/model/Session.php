<?php

class Session {
    private $db;

    public function __construct() {
        $this->db = \App\Database::getConnection();
    }

    // Trae una sesión específica de una tutoría
    public function obtenerPorTutoria($tutoria_id, $numero) {
        $stmt = $this->db->prepare("
            SELECT s.*, t.hora_incio, t.hora_fin, t.alumno_id, t.tutor_id, t.materia_id,
                   m.nombre AS materia_nombre,
                   CONCAT(u.nombres, ' ', u.apellidos) AS alumno_nombre
            FROM sesiones_tutoria s
            JOIN tutorias t ON t.id = s.tutoria_id
            JOIN materias m ON m.id = t.materia_id
            JOIN usuarios u ON u.id = t.alumno_id
            WHERE s.tutoria_id = ? AND s.numero = ?
            LIMIT 1
        ");
        $stmt->execute([$tutoria_id, $numero]);
        return $stmt->fetch();
    }

    // Trae todas las sesiones de una tutoría (para el menú lateral)
    public function obtenerTodasPorTutoria($tutoria_id) {
        $stmt = $this->db->prepare("
            SELECT numero FROM sesiones_tutoria
            WHERE tutoria_id = ?
            ORDER BY numero ASC
        ");
        $stmt->execute([$tutoria_id]);
        return $stmt->fetchAll();
    }
}