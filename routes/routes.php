<?php

/**
 * Definición de Rutas de la Aplicación
 */

use App\Router;
use App\Controller\AttendanceController;
use App\Controller\EvaluationController;
use App\Controller\MaterialController;
use App\Controller\SessionController;
use App\Controller\TutoriasController;
use App\Controller\UsuariosController;

// ==========================================
// RUTAS DE AUTENTICACIÓN (LOGIN)
// ==========================================

Router::get('/', function () {
    return view('auth/login', ['title' => 'Login Tutorium']);
});

Router::get('/login', function () {
    return view('auth/login', ['title' => 'Login Tutorium']);
});

Router::post('/login', 'Auth\\LoginController@handle');


// ==========================================
// VISTAS DEL DASHBOARD / SECCIONES
// ==========================================

// USUARIOS (Corregido: Ya no llama a layout/base manualmente)
Router::get('/users/inicio', function () {
    return view('users/inicio', ['title' => 'Mis tutorias']);
});

// ADMINISTRADOR (Corregido: Ya no llama a layout/base manualmente)
Router::get('/admin/dashboard', function () {
    return view('admin/dashboard', ['title' => 'Dashboard']);
});

Router::get('/dashboard', function () {
    return view('admin/dashboard', ['title' => 'Dashboard']);
});

Router::get('/tutorias', function () {
    $controller = new \App\Controller\TutorialController();
    return $controller->index();
});

// NUEVA RUTA DINÁMICA: Capta el ID de la tutoría de forma limpia
Router::get('/tutorias/{tutoria_id}/sesiones', function ($tutoria_id) {
    $controller = new SessionController();
    return $controller->mostrar($tutoria_id);
});

Router::get('/tutorias/admin', function () {
    $controller = new TutoriasController();
    return $controller->index();
});

Router::get('/materias', function () {
    $controller = new \App\Controller\MateriasController();
    return $controller->index();
});

Router::get('/usuarios', function () {
    $controller = new \App\Controller\UsuariosController();
    return $controller->index();
});

Router::get('/evaluaciones', function () {
    $model = new \App\Model\Evaluation();
    $rol = $_SESSION['rol'] ?? 'alumno';
    $evaluaciones = $model->obtenerHistorial($_SESSION['id'], $rol);

    $db = \App\Database::getConnection();
    $stmt = $db->query("SELECT DISTINCT m.nombre FROM materias m JOIN tutorias t ON t.materia_id = m.id JOIN evaluaciones e ON e.tutoria_id = t.id ORDER BY m.nombre");
    $materias = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return view('users/EvaluationHistory/EvaluationHistory', [
        'title' => 'Evaluaciones',
        'evaluaciones' => $evaluaciones,
        'rol' => $rol,
        'materias' => $materias,
    ]);
});

Router::get('/evaluacion/pdf/{id}', function ($evaluacion_id) {
    $rol = $_SESSION['rol'] ?? 'alumno';
    $usuario_id = $_SESSION['id'];
    $db = \App\Database::getConnection();

    // Obtener datos de la evaluación
    $stmt = $db->prepare("
        SELECT e.id, e.titulo, e.descripcion, e.fecha_creacion,
               u.nombres, u.apellidos, m.nombre AS materia
        FROM evaluaciones e
        JOIN tutorias t ON t.id = e.tutoria_id
        JOIN materias m ON m.id = t.materia_id
        JOIN usuarios u ON u.id = e.creada_por
        WHERE e.id = ?
    ");
    $stmt->execute([$evaluacion_id]);
    $evaluacion = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$evaluacion) {
        http_response_code(404);
        echo 'Evaluación no encontrada.';
        exit;
    }

    // Si es alumno, solo su propio historial (obtener el alumno_id de la tutoria)
    $alumno_id = $usuario_id;
    if ($rol === 'alumno') {
        $stmt = $db->prepare("SELECT t.alumno_id FROM evaluaciones e JOIN tutorias t ON t.id = e.tutoria_id WHERE e.id = ?");
        $stmt->execute([$evaluacion_id]);
        $tutoria = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$tutoria || $tutoria['alumno_id'] != $usuario_id) {
            http_response_code(403);
            echo 'No tienes acceso a esta evaluación.';
            exit;
        }
    }

    // Obtener detalle de preguntas y respuestas
    $model = new \App\Model\Evaluation();
    $detalle = $model->obtenerDetallePdf($evaluacion_id, $alumno_id);

    // Generar PDF
    $mpdf = new \Mpdf\Mpdf([
        'margin_left'   => 15,
        'margin_right'  => 15,
        'margin_top'    => 20,
        'margin_bottom' => 20,
    ]);

    $html = '
    <html>
    <head>
        <style>
            body { font-family: sans-serif; font-size: 11pt; color: #333; }
            h1 { color: #9e2820; font-size: 18pt; border-bottom: 2px solid #9e2820; padding-bottom: 5px; }
            h2 { color: #555; font-size: 14pt; margin-top: 20px; }
            .meta { background: #f5f5f5; padding: 10px; border-radius: 5px; margin: 10px 0; font-size: 10pt; }
            .meta strong { color: #555; }
            table { width: 100%; border-collapse: collapse; margin: 15px 0; }
            th { background: #9e2820; color: #fff; padding: 8px 10px; text-align: left; font-size: 10pt; }
            td { padding: 8px 10px; border-bottom: 1px solid #ddd; font-size: 10pt; }
            .correcto { color: #15803d; font-weight: bold; }
            .incorrecto { color: #dc2626; font-weight: bold; }
            .footer { text-align: center; color: #999; font-size: 9pt; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; }
        </style>
    </head>
    <body>
        <h1>' . htmlspecialchars($evaluacion['titulo']) . '</h1>
        <div class="meta">
            <strong>Materia:</strong> ' . htmlspecialchars($evaluacion['materia']) . '<br>
            <strong>Fecha:</strong> ' . date('d/m/Y', strtotime($evaluacion['fecha_creacion'])) . '<br>
            <strong>Tutor:</strong> ' . htmlspecialchars(trim($evaluacion['nombres'] . ' ' . $evaluacion['apellidos'])) . '
        </div>';

    if ($evaluacion['descripcion']) {
        $html .= '<p style="font-style:italic;color:#666;">' . htmlspecialchars($evaluacion['descripcion']) . '</p>';
    }

    $html .= '<h2>Resultado por pregunta</h2>
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Pregunta</th>
                    <th>Tu respuesta</th>
                    <th>Respuesta correcta</th>
                    <th style="width:60px;">Estado</th>
                </tr>
            </thead>
            <tbody>';

    $aciertos = 0;
    $total = count($detalle);

    foreach ($detalle as $i => $d) {
        $num = $i + 1;
        $estado = $d['es_correcta'] ? 'correcto' : 'incorrecto';
        $icono = $d['es_correcta'] ? '✓' : '✗';
        $clase = $d['es_correcta'] ? 'correcto' : 'incorrecto';
        $respuesta = $d['respuesta_alumno'] ? htmlspecialchars($d['respuesta_alumno']) : '<em style="color:#999;">Sin responder</em>';
        $correcta = htmlspecialchars($d['respuesta_correcta'] ?? '—');

        $html .= '<tr>
            <td>' . $num . '</td>
            <td>' . htmlspecialchars($d['enunciado']) . '</td>
            <td>' . $respuesta . '</td>
            <td class="correcto">' . $correcta . '</td>
            <td class="' . $clase . '">' . $icono . '</td>
        </tr>';

        if ($d['es_correcta']) $aciertos++;
    }

    $nota = $total > 0 ? round(($aciertos / $total) * 10, 1) : 0;

    $html .= '</tbody></table>';

    $html .= '<div style="text-align:right;font-size:12pt;margin-top:10px;">
        <strong>Calificación:</strong> 
        <span style="color:' . ($nota >= 7 ? '#15803d' : '#dc2626') . ';font-size:16pt;">' . number_format($nota, 1) . ' / 10</span>
        <br><small style="color:#999;">' . $aciertos . ' de ' . $total . ' preguntas correctas</small>
    </div>';

    $html .= '<div class="footer">Generado el ' . date('d/m/Y H:i') . ' • Sistema de Tutorías</div>';

    $html .= '</body></html>';

    $mpdf->WriteHTML($html);
    $filename = 'evaluacion_' . $evaluacion_id . '_' . preg_replace('/[^a-z0-9]/i', '_', $evaluacion['titulo']) . '.pdf';
    $mpdf->Output($filename, 'D');
    exit;
});

Router::get('/perfil', function () {
    $db = \App\Database::getConnection();
    $stmt = $db->prepare("SELECT id, nombres, apellidos, email, telefono, rol FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    return view('layout/Profile', ['title' => 'Perfil', 'usuario' => $usuario]);
});


// ==========================================
// CONTROLADORES Y PROCESOS (POST/GET)
// ==========================================

Router::get('/evaluation/crear', function () {
    $controller = new EvaluationController();
    return $controller->mostrarFormulario();
});

Router::post('/evaluation/guardar', function () {
    $controller = new EvaluationController();
    return $controller->guardar();
});

Router::post('/evaluation/importar-xml', function () {
    $controller = new EvaluationController();
    return $controller->importarXML();
});

Router::post('/attendance/marcar', function () {
    $controller = new AttendanceController();
    return $controller->marcar();
});

Router::post('/material/guardar', function () {
    $controller = new MaterialController();
    return $controller->guardar();
});

Router::post('/materias/guardar', function () {
    $controller = new \App\Controller\MateriasController();
    return $controller->guardar();
});

Router::post('/materias/actualizar', function () {
    $controller = new \App\Controller\MateriasController();
    return $controller->actualizar();
});

Router::post('/materias/eliminar', function () {
    $controller = new \App\Controller\MateriasController();
    return $controller->eliminar();
});

Router::post('/tutorias/guardar', function () {
    $controller = new \App\Controller\TutoriasController();
    return $controller->guardar();
});

Router::post('/tutorias/actualizar', function () {
    $controller = new \App\Controller\TutoriasController();
    return $controller->actualizar();
});

Router::post('/tutorias/eliminar', function () {
    $controller = new \App\Controller\TutoriasController();
    return $controller->eliminar();
});

Router::post('/usuarios/guardar', function () {
    $controller = new \App\Controller\UsuariosController();
    return $controller->guardar();
});

Router::post('/usuarios/actualizar', function () {
    $controller = new \App\Controller\UsuariosController();
    return $controller->actualizar();
});

Router::post('/usuarios/eliminar', function () {
    $controller = new \App\Controller\UsuariosController();
    return $controller->eliminar();
});

Router::post('/session/guardarLink', function () {
    $controller = new SessionController();
    return $controller->guardarLink();
});

Router::post('/perfil/actualizar', function () {
    $db = \App\Database::getConnection();
    $usuario_id = $_SESSION['id'];

    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['password'] ?? '';
    $confirm_password = $_POST['password_confirm'] ?? '';

    // Verificar contraseña actual
    $stmt = $db->prepare("SELECT password FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($current_password, $user['password'])) {
        $_SESSION['perfil_error'] = 'La contraseña actual no es correcta.';
        header('Location: /perfil');
        exit;
    }

    // Validar y actualizar contraseña
    if ($new_password !== '') {
        if (strlen($new_password) < 6) {
            $_SESSION['perfil_error'] = 'La nueva contraseña debe tener al menos 6 caracteres.';
            header('Location: /perfil');
            exit;
        }
        if ($new_password !== $confirm_password) {
            $_SESSION['perfil_error'] = 'Las contraseñas nuevas no coinciden.';
            header('Location: /perfil');
            exit;
        }
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $stmt->execute([$hash, $usuario_id]);
    }

    $_SESSION['perfil_ok'] = 'Credenciales actualizadas correctamente.';
    header('Location: /perfil');
    exit;
});


// ==========================================
// HELPER PARA RENDERIZAR VISTAS
// ==========================================

function view($name, $data = [])
{
    extract($data);
    $viewPath = __DIR__ . '/../resources/view/' . $name . '.php';

    if (!file_exists($viewPath)) {
        http_response_code(404);
        echo "Vista $name no encontrada";
        return;
    }

    // Evita bucles: Si se pide directamente el layout base, solo lo incluye
    $sinLayout = ['layout/base'];

    if (in_array($name, $sinLayout)) {
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }

    ob_start();
    include $viewPath;
    $content = ob_get_clean();

    $layoutPath = __DIR__ . '/../resources/view/layout/base.php';
    ob_start();
    include $layoutPath;
    return ob_get_clean();
}