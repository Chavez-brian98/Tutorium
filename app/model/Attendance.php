<?php

namespace App\Model;

class Attendance {
    private $db;

    public function __construct() {
        $this->db = \App\Database::getConnection();
    }

    public function marcar($tutoria_id, $sesion_id, $alumno_id, $fecha, $presente) {
        $stmt = $this->db->prepare("
            INSERT INTO asistencias (tutoria_id, sesion_id, alumno_id, fecha, presente)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE presente = VALUES(presente), fecha = VALUES(fecha)
        ");
        $stmt->execute([$tutoria_id, $sesion_id, $alumno_id, $fecha, $presente]);
    }

    public function obtenerPorSesion($sesion_id, $alumno_id) {
        $stmt = $this->db->prepare("
            SELECT presente FROM asistencias
            WHERE sesion_id = ? AND alumno_id = ?
            LIMIT 1
        ");
        $stmt->execute([$sesion_id, $alumno_id]);
        return $stmt->fetch();
    }
}