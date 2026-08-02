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

    // POST /material/subir-pdf
    public function subirPDF() {
        header('Content-Type: application/json');

        $rol = $_SESSION['rol'] ?? 'alumno';
        if ($rol !== 'tutor' && $rol !== 'admin') {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'Solo el tutor puede subir archivos.']);
            exit;
        }

        $sesion_id = (int) ($_POST['sesion_id'] ?? 0);
        if (!$sesion_id) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Falta el id de sesión.']);
            exit;
        }

        $archivo = $_FILES['archivo_pdf'] ?? null;
        if (!$archivo || $archivo['error'] !== UPLOAD_ERR_OK) {
            $codigo = $archivo['error'] ?? -1;
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => "Error al subir el archivo (código $codigo)."]);
            exit;
        }

        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        if ($extension !== 'pdf') {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Solo se permiten archivos PDF.']);
            exit;
        }

        $nombreUnico = 'sesion_' . $sesion_id . '_' . time() . '.pdf';
        $rutaRelativa = 'resources/files/';
        $destino = __DIR__ . '/../../' . $rutaRelativa . $nombreUnico;

        if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'No se pudo guardar el archivo en el servidor.']);
            exit;
        }

        $this->materialModel->guardarPDF($sesion_id, $nombreUnico, $rutaRelativa, 'pdf');

        echo json_encode([
            'ok'      => true,
            'archivo' => $nombreUnico,
            'ruta'    => $rutaRelativa . $nombreUnico,
        ]);
        exit;
    }

    // GET /material/pdf/{sesion_id}
    public function descargarPDF($sesion_id) {
        $material = $this->materialModel->obtenerPorSesion($sesion_id);
        if (!$material || !$material['archivo'] || !$material['ruta']) {
            http_response_code(404);
            echo 'PDF no encontrado.';
            return;
        }

        $archivoFisico = __DIR__ . '/../../' . $material['ruta'] . $material['archivo'];
        if (!file_exists($archivoFisico)) {
            http_response_code(404);
            echo 'El archivo ya no existe en el servidor.';
            return;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $material['archivo'] . '"');
        readfile($archivoFisico);
        exit;
    }

    // POST /material/eliminar-pdf
    public function eliminarPDF() {
        header('Content-Type: application/json');

        $rol = $_SESSION['rol'] ?? 'alumno';
        if ($rol !== 'tutor' && $rol !== 'admin') {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'No autorizado.']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $sesion_id = (int) ($input['sesion_id'] ?? 0);
        if (!$sesion_id) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Falta el id de sesión.']);
            exit;
        }

        $this->materialModel->eliminarPDF($sesion_id);

        echo json_encode(['ok' => true]);
        exit;
    }
}