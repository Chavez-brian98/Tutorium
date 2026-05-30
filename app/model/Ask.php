<?php

namespace App\Model;

class Ask {
    private $db;

    public function __construct() {
        $this->db = \App\Database::getConnection();
    }

    // tipo: 'opcion_multiple' | 'respuesta_corta' | 'VyF'
    public function crear($evaluacion_id, $enunciado, $tipo) {
        $stmt = $this->db->prepare("
            INSERT INTO preguntas (evaluacion_id, enunciado, tipo)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$evaluacion_id, $enunciado, $tipo]);
        return $this->db->lastInsertId();
    }
}