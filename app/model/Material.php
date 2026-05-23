<?php

class Material {
    private $db;

    public function __construct() {
        $this->db = \App\Database::getConnection();
    }

    // Trae el material de una sesión
    public function obtenerPorSesion($sesion_id) {
        $stmt = $this->db->prepare("
            SELECT * FROM material_tutoria
            WHERE id_sesion = ?
            LIMIT 1
        ");
        $stmt->execute([$sesion_id]);
        return $stmt->fetch();
    }

    // Crea o actualiza el material de una sesión
    public function guardar($sesion_id, $texto, $link = null) {
        $stmt = $this->db->prepare("
            INSERT INTO material_tutoria (id_sesion, texto, fecha_creacion, fecha_modificacion)
            VALUES (?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                texto               = VALUES(texto),
                fecha_modificacion  = NOW()
        ");
        $stmt->execute([$sesion_id, $texto]);
    }
}