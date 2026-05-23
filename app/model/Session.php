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

    // Trae una sesión por su id (para validar en attendance)
public function obtenerPorId($sesion_id) {
    $stmt = $this->db->prepare("
        SELECT s.*, t.alumno_id, t.tutor_id
        FROM sesiones_tutoria s
        JOIN tutorias t ON t.id = s.tutoria_id
        WHERE s.id = ?
        LIMIT 1
    ");
    $stmt->execute([$sesion_id]);
    return $stmt->fetch();
}

    public function obtenerDatosTutoria($tutoria_id) {
    $sql = "
        SELECT 
            t.hora_incio    AS hora_inicio,
            t.hora_fin,
            t.fecha,
            m.nombre        AS materia,
            CONCAT(u.nombres, ' ', u.apellidos) AS nombre_alumno
        FROM tutorias t
        JOIN materias m ON m.id = t.materia_id
        JOIN usuarios u ON u.id = t.alumno_id
        WHERE t.id = ?
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$tutoria_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function guardarLink($sesion_id, $link) {
    $stmt = $this->db->prepare("UPDATE sesiones_tutoria SET link = ? WHERE id = ?");
    $stmt->execute([$link, $sesion_id]);
}
}