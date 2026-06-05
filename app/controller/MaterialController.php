<?php

namespace App\Controller;
use App\Model\Material;

class MaterialController {
    private $materialModel;

    public function __construct() {
        $this->materialModel = new \App\Model\Material();
    }

    // POST /material/guardar
    public function guardar() {
        header('Content-Type: application/json');

        $rol = $_SESSION['rol'] ?? 'alumno';
        if ($rol !== 'tutor' && $rol !== 'admin') {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'Solo el tutor puede editar el contenido.']);
            exit;
        }

        $sesion_id = (int)   ($_POST['sesion_id'] ?? 0);
        $texto     = trim($_POST['texto']     ?? '');

        if (!$sesion_id) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Falta el id de sesión.']);
            exit;
        }

        $this->materialModel->guardar($sesion_id, $texto);

        echo json_encode(['ok' => true]);
        exit;
    }
}