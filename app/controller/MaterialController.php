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