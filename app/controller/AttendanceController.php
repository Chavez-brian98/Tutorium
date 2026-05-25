<?php

require_once __DIR__ . '/../model/Attendance.php';
require_once __DIR__ . '/../model/Session.php';

class AttendanceController {
    private $attendanceModel;
    private $sessionModel;

    public function __construct() {
        $this->attendanceModel = new Attendance();
        $this->sessionModel    = new Session();
    }

    // POST /attendance/marcar
    public function marcar() {
        header('Content-Type: application/json');

        $tutoria_id = (int) ($_POST['tutoria_id'] ?? 0);
        $sesion_id  = (int) ($_POST['sesion_id']  ?? 0);
        $alumno_id  = (int) ($_POST['alumno_id']  ?? 0);
        $presente   = (int) ($_POST['presente']   ?? 0);

        // Validar que los ids requeridos llegaron
        if (!$tutoria_id || !$sesion_id || !$alumno_id) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Faltan datos requeridos.']);
            exit;
        }

        // Validar que la sesión pertenece a esa tutoría
        $sesion = $this->sessionModel->obtenerPorId($sesion_id);

        if (!$sesion || (int) $sesion['tutoria_id'] !== $tutoria_id) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'La sesión no pertenece a esta tutoría.']);
            exit;
        }

        // Validar que el alumno pertenece a la tutoría
        if ((int) $sesion['alumno_id'] !== $alumno_id) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'El alumno no pertenece a esta tutoría.']);
            exit;
        }

        $fecha = date('Y-m-d');
        $this->attendanceModel->marcar($tutoria_id, $sesion_id, $alumno_id, $fecha, $presente);

        echo json_encode([
            'ok'       => true,
            'presente' => (bool) $presente,
            'fecha'    => $fecha
        ]);
        exit;
    }
}