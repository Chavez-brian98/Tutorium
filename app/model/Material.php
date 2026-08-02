<?php

namespace App\Model; // <--- ¡ESTO ERA LO QUE FALTABA!

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

    // Elimina el PDF de una sesión (archivo físico y BD)
    public function eliminarPDF($sesion_id) {
        $material = $this->obtenerPorSesion($sesion_id);
        if ($material && $material['archivo'] && $material['ruta']) {
            $archivoFisico = __DIR__ . '/../../' . $material['ruta'] . $material['archivo'];
            if (file_exists($archivoFisico)) {
                unlink($archivoFisico);
            }
        }
        $stmt = $this->db->prepare("
            UPDATE material_tutoria
            SET archivo = NULL, ruta = NULL, tipo = NULL, fecha_modificacion = NOW()
            WHERE id_sesion = ?
        ");
        $stmt->execute([$sesion_id]);
    }

    // Guarda o actualiza el PDF asociado a una sesión
    public function guardarPDF($sesion_id, $archivo, $ruta, $tipo) {
        $stmt = $this->db->prepare("
            INSERT INTO material_tutoria (id_sesion, archivo, ruta, tipo, fecha_creacion, fecha_modificacion)
            VALUES (?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                archivo             = VALUES(archivo),
                ruta                = VALUES(ruta),
                tipo                = VALUES(tipo),
                fecha_modificacion  = NOW()
        ");
        $stmt->execute([$sesion_id, $archivo, $ruta, $tipo]);
    }
}