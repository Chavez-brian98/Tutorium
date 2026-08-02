<?php

namespace App\Controller;

// Mantenemos los "use" hacia la carpeta Model
use App\Model\Material;
use App\Model\Session;
use App\Model\Attendance;

// ❌ BORRADOS COMPLETAMENTE LOS require_once DE AQUÍ

class SessionController {

    private $sessionModel;

    public function __construct() {
        // Corrección: Forzar la búsqueda en el Namespace global con '\'
        $this->sessionModel = new \App\Model\Session();
    }

    // GET /tutorias/{tutoria_id}/sesiones?numero=1
    public function mostrar($tutoria_id) {
        $numero = isset($_GET['numero']) ? (int) $_GET['numero'] : 1;

        $sesion   = $this->sessionModel->obtenerPorTutoria($tutoria_id, $numero);
        $sesiones = $this->sessionModel->obtenerTodasPorTutoria($tutoria_id);

        if (!$sesion) {
            http_response_code(404);
            echo "Sesión no encontrada en la base de datos.";
            return;
        }

        // Asistencia: Corrección agregando '\'
        $attendanceModel = new \App\Model\Attendance();
        
        $alumno_id = $sesion['alumno_id'] ?? 2; 
        $asistencia      = $attendanceModel->obtenerPorSesion($sesion['id'], $alumno_id);
        $yaAsistencia    = $asistencia !== false && $asistencia !== null;
        $asisPresente    = $yaAsistencia ? (bool) $asistencia['presente'] : null;

        // Datos de la tutoría (materia, alumno, horario)
        $datosTutoria  = $this->sessionModel->obtenerDatosTutoria($tutoria_id);

        $materia = "Materia no asignada";
        $nombre_alumno = "Alumno no asignado";
        $horario = "Horario no definido";

        if ($datosTutoria) {
            $materia = $datosTutoria['materia'] ?? 'Matemáticas';
            $nombre_alumno = $datosTutoria['nombre_alumno'] ?? 'María López';

            $dias = [
                'Monday'    => 'Lunes',
                'Tuesday'   => 'Martes',
                'Wednesday' => 'Miércoles',
                'Thursday'  => 'Jueves',
                'Friday'    => 'Viernes',
                'Saturday'  => 'Sábado',
                'Sunday'    => 'Domingo',
            ];

            $fechaString = $datosTutoria['fecha'] ?? $sesion['fecha'] ?? null;
            $dia = $fechaString ? ($dias[date('l', strtotime($fechaString))] ?? 'Por definir') : 'Por definir';

            $hInicioRaw = $datosTutoria['hora_incio'] ?? $datosTutoria['hora_inicio'] ?? null;
            $hFinRaw = $datosTutoria['hora_fin'] ?? null;

            $horaInicio = $hInicioRaw ? date('g:ia', strtotime($hInicioRaw)) : 'Por definir';
            $horaFin = $hFinRaw ? date('g:ia', strtotime($hFinRaw)) : 'Por definir';

            $horario = "$dia / $horaInicio - $horaFin";
        }

        // Material: Corrección agregando '\'
        $materialModel = new \App\Model\Material();
        $material      = $materialModel->obtenerPorSesion($sesion['id']);

        // Verificar si existe una evaluación para esta sesión
        $evaluationModel = new \App\Model\Evaluation();
        $evaluacion = $evaluationModel->obtenerPorSesion($sesion['id']);

        // Fallback: si la columna sesion_id no existe (migración pendiente), usar tutoría
        if (!$evaluacion && !$evaluationModel->columnaExiste('evaluaciones', 'sesion_id')) {
            $evaluacion = $evaluationModel->obtenerPorTutoria($tutoria_id);
        }

        $rol = $_SESSION['rol'] ?? 'alumno';

        // Verificar si el estudiante ya respondió
        $yaRespondida = false;
        if ($evaluacion) {
            $stmtYa = \App\Database::getConnection()->prepare(
                "SELECT COUNT(*) FROM respuestas_alumno WHERE evaluacion_id = ? AND alumno_id = ?"
            );
            $stmtYa->execute([$evaluacion['id'], $alumno_id]);
            $yaRespondida = (int) $stmtYa->fetchColumn() > 0;
        }

        return view('users/Session/Session', [
            'sesion'        => $sesion,
            'sesiones'      => $sesiones,
            'tutoria_id'    => $tutoria_id,
            'numero'        => $numero,
            'sesion_id'     => $sesion['id'],
            'alumno_id'     => $alumno_id,
            'yaAsistencia'  => $yaAsistencia,
            'asisPresente'  => $asisPresente,
            'material'      => $material,
            'materia'       => $materia,
            'horario'       => $horario,
            'nombre_alumno' => $nombre_alumno,
            'rol'           => $rol,
            'evaluacion'    => $evaluacion,
            'yaRespondida'  => $yaRespondida,
        ]);
    }

    public function guardarLink() {
        $rol = $_SESSION['rol'] ?? 'alumno';
        if ($rol !== 'tutor' && $rol !== 'admin') {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'Solo el tutor puede editar el enlace.']);
            exit;
        }

        $sesion_id = (int)  $_POST['sesion_id'];
        $link      = trim($_POST['link'] ?? '');

        $this->sessionModel->guardarLink($sesion_id, $link);

        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit; // Agregado exit preventivo para evitar fugas de buffer plano
    }
}