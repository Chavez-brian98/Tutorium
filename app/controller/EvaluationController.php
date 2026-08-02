<?php

namespace App\Controller;
 use App\Model\Evaluation;
 use App\Model\Ask;
 use App\Model\AnswerOption;

class EvaluationController {

    private $evaluationModel;
    private $askModel;
    private $answerOptionModel;

    public function __construct() {
        $this->evaluationModel   = new \App\Model\Evaluation();
        $this->askModel          = new \App\Model\Ask();
        $this->answerOptionModel = new \App\Model\AnswerOption();
    }

    // GET /evaluation/crear
    public function mostrarFormulario() {
        $tutoria_id = (int) ($_GET['tutoria_id'] ?? 0);
        $sesion_id  = (int) ($_GET['sesion_id'] ?? 0);

        if (!$tutoria_id) {
            http_response_code(400);
            echo "Falta el parámetro tutoria_id.";
            return;
        }

        return view('users/Evaluation/Evaluation', [
            'tutoria_id' => $tutoria_id,
            'sesion_id'  => $sesion_id,
        ]);
    }

    // POST /evaluation/guardar
    public function guardar() {
        $rol = $_SESSION['rol'] ?? 'alumno';
        if ($rol !== 'tutor' && $rol !== 'admin') {
            http_response_code(403);
            echo 'Solo el tutor puede crear evaluaciones.';
            exit;
        }

        $tutoria_id  = (int)   $_POST['tutoria_id'];
        $sesion_id   = (int)   ($_POST['sesion_id'] ?? 0);
        $tutor_id    = (int)   ($_SESSION['usuario_id'] ?? 1);
        $titulo      = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion'] ?? '');

        // Crear la evaluación (con sesion_id si se creó desde una sesión específica)
        $evaluacion_id = $this->evaluationModel->crear($tutoria_id, $tutor_id, $titulo, $descripcion, $sesion_id ?: null);

        // Preguntas y tipos vienen como arrays del formulario
        // enunciado[1], enunciado[2]...  tipo[1], tipo[2]...
        $enunciados = $_POST['enunciado'] ?? [];
        $tipos      = $_POST['tipo']      ?? [];

        foreach ($enunciados as $n => $enunciado) {
            $enunciado = trim($enunciado);
            $tipo      = $tipos[$n] ?? 'respuesta_corta';

            // Mapear el valor del select al ENUM de la BD
            $tipoDb = match($tipo) {
                'seleccion' => 'opcion_multiple',
                'cerrada'   => 'respuesta_corta',
                'VyF'       => 'VyF',
                default     => 'respuesta_corta',
            };

            $pregunta_id = $this->askModel->crear($evaluacion_id, $enunciado, $tipoDb);

            if ($tipo === 'VyF') {
                $correcta = $_POST['correcta_vyf'][$n] ?? 'verdadero';
                $this->answerOptionModel->crear($pregunta_id, 'Verdadero', $correcta === 'verdadero' ? 1 : 0);
                $this->answerOptionModel->crear($pregunta_id, 'Falso',     $correcta === 'falso'     ? 1 : 0);

            } elseif ($tipo === 'seleccion') {
                $opciones = $_POST['opcion'][$n]        ?? [];
                $correcta = (int) ($_POST['correcta_sel'][$n] ?? 0);

                foreach ($opciones as $i => $texto) {
                    $this->answerOptionModel->crear($pregunta_id, trim($texto), $i === $correcta ? 1 : 0);
                }
            }
            // cerrada no tiene opciones, no hace nada más
        }

        if ($tutoria_id > 0) {
            header("Location: /tutorias/{$tutoria_id}/sesiones?numero=1&ok=evaluacion");
        } else {
            // Si por alguna razón mística el ID llegó en 0, te manda al listado general de tutorías
            header("Location: /tutorias"); 
        }
        exit;
    }

    // GET /evaluation/editar/{id}
    public function mostrarFormularioEditar($evaluacion_id) {
        $rol = $_SESSION['rol'] ?? 'alumno';
        if ($rol !== 'tutor' && $rol !== 'admin') {
            http_response_code(403);
            echo 'No autorizado.';
            return;
        }

        $evaluacion = $this->evaluationModel->obtenerConPreguntas($evaluacion_id);
        if (!$evaluacion) {
            http_response_code(404);
            echo 'Evaluación no encontrada.';
            return;
        }

        return view('users/Evaluation/EvaluationEdit', [
            'evaluacion' => $evaluacion,
        ]);
    }

    // POST /evaluation/actualizar
    public function actualizar() {
        $rol = $_SESSION['rol'] ?? 'alumno';
        if ($rol !== 'tutor' && $rol !== 'admin') {
            http_response_code(403);
            echo 'Solo el tutor puede editar evaluaciones.';
            exit;
        }

        $evaluacion_id = (int) $_POST['evaluacion_id'];
        $titulo        = trim($_POST['titulo']);
        $descripcion   = trim($_POST['descripcion'] ?? '');
        $tutoria_id    = (int) ($_POST['tutoria_id'] ?? 0);

        $this->evaluationModel->actualizar($evaluacion_id, $titulo, $descripcion);

        $this->evaluationModel->eliminarPreguntasConOpciones($evaluacion_id);

        $enunciados = $_POST['enunciado'] ?? [];
        $tipos      = $_POST['tipo'] ?? [];

        foreach ($enunciados as $n => $enunciado) {
            $enunciado = trim($enunciado);
            $tipo      = $tipos[$n] ?? 'respuesta_corta';

            $tipoDb = match($tipo) {
                'seleccion' => 'opcion_multiple',
                'cerrada'   => 'respuesta_corta',
                'VyF'       => 'VyF',
                default     => 'respuesta_corta',
            };

            $pregunta_id = $this->askModel->crear($evaluacion_id, $enunciado, $tipoDb);

            if ($tipo === 'VyF') {
                $correcta = $_POST['correcta_vyf'][$n] ?? 'verdadero';
                $this->answerOptionModel->crear($pregunta_id, 'Verdadero', $correcta === 'verdadero' ? 1 : 0);
                $this->answerOptionModel->crear($pregunta_id, 'Falso',     $correcta === 'falso'     ? 1 : 0);
            } elseif ($tipo === 'seleccion') {
                $opciones = $_POST['opcion'][$n]        ?? [];
                $correcta = (int) ($_POST['correcta_sel'][$n] ?? 0);
                foreach ($opciones as $i => $texto) {
                    $this->answerOptionModel->crear($pregunta_id, trim($texto), $i === $correcta ? 1 : 0);
                }
            }
        }

        if ($tutoria_id > 0) {
            header("Location: /tutorias/{$tutoria_id}/sesiones?numero=1&ok=evaluacion");
        } else {
            header("Location: /evaluaciones");
        }
        exit;
    }

    // GET /certificado/pdf/{tutoria_id}
    public function generarCertificado($tutoria_id) {
        $usuario_id = (int) ($_SESSION['id'] ?? 0);
        $rol = $_SESSION['rol'] ?? 'alumno';

        $tutorialModel = new \App\Model\Tutorial();
        $datos = $tutorialModel->obtenerDatosCertificado($tutoria_id);
        if (!$datos) {
            http_response_code(404);
            echo 'Tutoría no encontrada.';
            return;
        }

        if ($datos['estado'] !== 'COMPLETADA') {
            http_response_code(403);
            echo 'La tutoría aún no ha sido completada.';
            return;
        }

        // Solo el alumno o el tutor/admin pueden ver el certificado
        if ($rol === 'alumno') {
            $db = \App\Database::getConnection();
            $stmt = $db->prepare("SELECT alumno_id FROM tutorias WHERE id = ?");
            $stmt->execute([$tutoria_id]);
            $t = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$t || $t['alumno_id'] != $usuario_id) {
                http_response_code(403);
                echo 'No tienes acceso a esta tutoría.';
                return;
            }
        }

        $notasData = $this->evaluationModel->obtenerNotasTutoria($tutoria_id, $datos['alumno_id']);
        $notas = $notasData['evaluaciones'];
        $promedio = $notasData['promedio'];

        if ($promedio < 6) {
            http_response_code(403);
            echo 'El promedio mínimo de 6 no ha sido alcanzado.';
            return;
        }

        $logoPath = __DIR__ . '/../../public/img/logo.png';
        extract(compact('datos', 'notas', 'promedio', 'logoPath'));
        ob_start();
        include __DIR__ . '/../../resources/view/layout/CertificadoPDF.php';
        $html = ob_get_clean();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
        ]);

        $mpdf->SetHTMLHeader('');
        $mpdf->SetHTMLFooter('');
        $mpdf->WriteHTML($html);

        $filename = 'certificado_tutoria_' . $tutoria_id . '.pdf';
        $mpdf->Output($filename, 'D');
        exit;
    }

    // GET /evaluacion/pdf/{id}
    public function generarPDF($evaluacion_id) {
        $rol       = $_SESSION['rol'] ?? 'alumno';
        $usuario_id = $_SESSION['id'];

        $evaluacion = $this->evaluationModel->obtenerDatosPdf($evaluacion_id);
        if (!$evaluacion) {
            http_response_code(404);
            echo 'Evaluación no encontrada.';
            return;
        }

        if ($rol === 'alumno') {
            $db = \App\Database::getConnection();
            $stmt = $db->prepare("SELECT t.alumno_id FROM evaluaciones e JOIN tutorias t ON t.id = e.tutoria_id WHERE e.id = ?");
            $stmt->execute([$evaluacion_id]);
            $tutoria = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!$tutoria || $tutoria['alumno_id'] != $usuario_id) {
                http_response_code(403);
                echo 'No tienes acceso a esta evaluación.';
                return;
            }
        }

        $detalle = $this->evaluationModel->obtenerDetallePdf($evaluacion_id, $usuario_id);

        $logoPath = __DIR__ . '/../../public/img/logo.png';
        extract(compact('evaluacion', 'detalle', 'logoPath'));
        ob_start();
        include __DIR__ . '/../../resources/view/layout/EvaluacionPDF.php';
        $html = ob_get_clean();

        $mpdf = new \Mpdf\Mpdf([
            'margin_left'   => 18,
            'margin_right'  => 18,
            'margin_top'    => 45,
            'margin_bottom' => 30,
        ]);

        $mpdf->SetHTMLHeader('
            <table style="width:100%; border-bottom: 2px solid #9e2820; padding-bottom: 6px;">
                <tr>
                    <td style="width:60px;"><img src="' . $logoPath . '" width="50" /></td>
                    <td style="vertical-align:middle;">
                        <span style="font-size:14pt; font-weight:bold; color:#5c1313;">UNIVERSIDAD CATÓLICA DE EL SALVADOR</span><br>
                        <span style="font-size:9pt; color:#888;">FACULTAD DE INGENIERÍA — SISTEMA DE TUTORÍAS</span>
                    </td>
                    <td style="width:100px; text-align:right; vertical-align:middle; font-size:9pt; color:#999;">' . date('d/m/Y') . '</td>
                </tr>
            </table>
        ');

        $mpdf->SetHTMLFooter('
            <table style="width:100%; border-top: 1px solid #ddd; padding-top: 4px;">
                <tr>
                    <td style="text-align:left; font-size:8pt; color:#aaa;">UNICAES — Tutorías Académicas</td>
                    <td style="text-align:right; font-size:8pt; color:#aaa;">{PAGENO} / {nbpg}</td>
                </tr>
            </table>
        ');

        $mpdf->WriteHTML($html);
        $filename = 'evaluacion_' . $evaluacion_id . '_' . preg_replace('/[^a-z0-9]/i', '_', $evaluacion['titulo']) . '.pdf';
        $mpdf->Output($filename, 'D');
        exit;
    }

    // POST /evaluation/importar-xml
    public function importarXML() {
        $rol = $_SESSION['rol'] ?? 'alumno';
        if ($rol !== 'tutor' && $rol !== 'admin') {
            http_response_code(403);
            echo 'Solo el tutor puede importar evaluaciones.';
            exit;
        }

        $tutoria_id  = (int)   $_POST['tutoria_id'];
        $tutor_id    = (int)   ($_SESSION['usuario_id'] ?? 1);
        $titulo      = trim($_POST['titulo']      ?? 'Evaluación XML');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (!isset($_FILES['archivo_xml']) || $_FILES['archivo_xml']['error'] !== 0) {
            http_response_code(400);
            echo "Error al subir el archivo XML.";
            return;
        }

        $xml = simplexml_load_file($_FILES['archivo_xml']['tmp_name']);
        if ($xml === false) {
            http_response_code(400);
            echo "El archivo no es un XML válido.";
            return;
        }

        $evaluacion_id = $this->evaluationModel->crear($tutoria_id, $tutor_id, $titulo, $descripcion);

        /*
         * Estructura esperada del XML:
         *
         * <evaluacion>
         *   <pregunta tipo="opcion_multiple">
         *     <enunciado>¿Cuál es la capital de Francia?</enunciado>
         *     <opciones>
         *       <opcion correcta="no">Madrid</opcion>
         *       <opcion correcta="si">París</opcion>
         *     </opciones>
         *   </pregunta>
         *   <pregunta tipo="VyF">
         *     <enunciado>El agua hierve a 100°C</enunciado>
         *     <opciones>
         *       <opcion correcta="si">Verdadero</opcion>
         *       <opcion correcta="no">Falso</opcion>
         *     </opciones>
         *   </pregunta>
         *   <pregunta tipo="respuesta_corta">
         *     <enunciado>¿Qué es la fotosíntesis?</enunciado>
         *   </pregunta>
         * </evaluacion>
         */

        foreach ($xml->pregunta as $preg) {
            $tipo      = (string) $preg['tipo'];
            $enunciado = (string) $preg->enunciado;

            $pregunta_id = $this->askModel->crear($evaluacion_id, $enunciado, $tipo);

            if (isset($preg->opciones)) {
                foreach ($preg->opciones->opcion as $opcion) {
                    $texto       = (string) $opcion;
                    $es_correcta = ((string) $opcion['correcta'] === 'si') ? 1 : 0;
                    $this->answerOptionModel->crear($pregunta_id, $texto, $es_correcta);
                }
            }
        }

        header("Location: /tutorias/{$tutoria_id}/sesiones?numero=1&ok=evaluacion");
        exit;
    }
}