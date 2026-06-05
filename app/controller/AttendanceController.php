<?php

namespace App\Controller;

use App\Model\Attendance;
use App\Model\Session;

class AttendanceController {
    private $attendanceModel;
    private $sessionModel;

    public function __construct() {
        $this->attendanceModel = new \App\Model\Attendance();
        $this->sessionModel    = new \App\Model\Session();
    }

    // POST /attendance/marcar
    // POST /attendance/marcar
    public function marcar() {
        header('Content-Type: application/json');

        $rol = $_SESSION['rol'] ?? 'alumno';
        if ($rol !== 'tutor' && $rol !== 'admin') {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'Solo el tutor puede marcar asistencia.']);
            exit;
        }

        try {
            $tutoria_id = (int) ($_POST['tutoria_id'] ?? 0);
            $sesion_id  = (int) ($_POST['sesion_id']  ?? 0);
            $alumno_id  = (int) ($_POST['alumno_id']  ?? 0);
            $presente   = (int) ($_POST['presente']   ?? 0);

            if (!$tutoria_id || !$sesion_id || !$alumno_id) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Faltan datos requeridos en el controlador.']);
                exit;
            }

            // Aquí podría estar fallando si obtenerPorId no existe o devuelve algo inesperado
            $sesion = $this->sessionModel->obtenerPorId($sesion_id);

            if (!$sesion || (int) $sesion['tutoria_id'] !== $tutoria_id) {
                http_response_code(403);
                echo json_encode(['ok' => false, 'error' => 'La sesión no pertenece a esta tutoría o no existe.']);
                exit;
            }

            if ((int) $sesion['alumno_id'] !== $alumno_id) {
                http_response_code(403);
                echo json_encode(['ok' => false, 'error' => 'El alumno no pertenece a esta tutoría según la sesión.']);
                exit;
            }

            $fecha = date('Y-m-d');
            
            // Aquí podría estar fallando si la estructura de la tabla 'asistencias' no coincide
            $this->attendanceModel->marcar($tutoria_id, $sesion_id, $alumno_id, $fecha, $presente);

            echo json_encode([
                'ok'       => true,
                'presente' => (bool) $presente,
                'fecha'    => $fecha
            ]);
            exit;

        } catch (\Throwable $e) {
            // Si algo falla, capturamos el error real y se lo mandamos a SweetAlert
            http_response_code(500);
            echo json_encode([
                'ok' => false, 
                'error' => 'Error interno en PHP: ' . $e->getMessage()
            ]);
            exit;
        }
    }
}