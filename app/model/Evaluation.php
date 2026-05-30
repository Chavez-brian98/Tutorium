<?php

namespace App\Model;

class Evaluation {
    private $db;

    public function __construct() {
        $this->db = \App\Database::getConnection();
    }

    public function crear($tutoria_id, $tutor_id, $titulo, $descripcion) {
        $stmt = $this->db->prepare("
            INSERT INTO evaluaciones (tutoria_id, creada_por, titulo, descripcion)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$tutoria_id, $tutor_id, $titulo, $descripcion]);
        return $this->db->lastInsertId();
    }
}