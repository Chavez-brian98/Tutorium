<?php

require_once __DIR__ . '/../model/Evaluation.php';
require_once __DIR__ . '/../model/Ask.php';
require_once __DIR__ . '/../model/AnswerOption.php';

class EvaluationController {

    private $evaluationModel;
    private $askModel;
    private $answerOptionModel;

    public function __construct() {
        $this->evaluationModel   = new Evaluation();
        $this->askModel          = new Ask();
        $this->answerOptionModel = new AnswerOption();
    }

    // GET /evaluation/crear
    public function mostrarFormulario() {
        $tutoria_id = (int) ($_GET['tutoria_id'] ?? 0);

        if (!$tutoria_id) {
            http_response_code(400);
            echo "Falta el parámetro tutoria_id.";
            return;
        }

        return view('Evaluation/Evaluation', [
            'tutoria_id' => $tutoria_id,
        ]);
    }

    // POST /evaluation/guardar
    public function guardar() {
        $tutoria_id  = (int)   $_POST['tutoria_id'];
        $tutor_id    = (int)   ($_SESSION['usuario_id'] ?? 1); // cuando tengas sesión real
        $titulo      = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion'] ?? '');

        // Crear la evaluación
        $evaluacion_id = $this->evaluationModel->crear($tutoria_id, $tutor_id, $titulo, $descripcion);

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

        header('Location: /session?ok=evaluacion');
        exit;
    }

    // POST /evaluation/importar-xml
    public function importarXML() {
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

        header('Location: /session?ok=evaluacion');
        exit;
    }
}