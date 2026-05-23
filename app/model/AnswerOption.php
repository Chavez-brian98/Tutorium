<?php

class AnswerOption {
    private $db;

    public function __construct() {
        $this->db = \App\Database::getConnection();
    }

    public function crear($pregunta_id, $texto, $es_correcta) {
        $stmt = $this->db->prepare("
            INSERT INTO opciones_respuesta (pregunta_id, texto, es_correcta)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$pregunta_id, $texto, $es_correcta]);
    }
}