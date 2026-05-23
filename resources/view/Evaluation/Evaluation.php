<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="/css/styles.css">
</head>
<body style="background-color:var(--fondo-base); padding:24px; min-height:100vh;">

<?php
$tutoria_id  = (int)   ($_GET['tutoria_id']  ?? 1);
$titulo      = htmlspecialchars($_GET['titulo']      ?? 'Sin título');
$descripcion = htmlspecialchars($_GET['descripcion'] ?? '');
$total       = max(1, (int) ($_GET['total'] ?? 1));
$tipos_raw   = $_GET['tipos'] ?? 'cerrada';
$tipos       = array_filter(explode(',', $tipos_raw));

// ── Distribución proporcional ──────────────────────────────────────
// Reparte el total entre los tipos elegidos lo más equitativo posible.
// Ejemplo: 10 preguntas, 3 tipos → [4, 3, 3]
// Si es 1 tipo → todas del mismo tipo.
$distribucion = [];
if (count($tipos) > 0) {
    $base  = intdiv($total, count($tipos));   // cociente entero
    $resto = $total % count($tipos);          // sobrante

    foreach (array_values($tipos) as $i => $tipo) {
        // Los primeros $resto tipos reciben 1 pregunta extra
        $distribucion[$tipo] = $base + ($i < $resto ? 1 : 0);
    }
}

// Etiquetas legibles
$etiquetas = [
    'VyF'      => 'Verdadero / Falso',
    'cerrada'  => 'Pregunta cerrada',
    'seleccion'=> 'Opción múltiple',
];
?>

<div style="max-width:680px; margin:0 auto;">

    <!-- Cabecera -->
    <div style="background:white; border-radius:16px; border-top:8px solid var(--granate-600); padding:24px; margin-bottom:16px; box-shadow:0 1px 4px rgba(0,0,0,0.08);">
        <h1 style="font-family:'Playfair Display',serif; color:var(--granate-700); font-size:1.5rem; margin:0 0 6px;">
            <?= $titulo ?>
        </h1>
        <?php if ($descripcion): ?>
            <p style="color:var(--texto-secundario); font-size:14px; margin:0 0 10px;"><?= $descripcion ?></p>
        <?php endif; ?>

        <!-- Resumen de distribución -->
        <div style="display:flex; flex-wrap:wrap; gap:8px; margin-top:10px;">
            <?php foreach ($distribucion as $tipo => $cantidad): ?>
                <span style="background:var(--granate-50); color:var(--granate-700); border:1px solid var(--granate-200); border-radius:20px; padding:3px 12px; font-size:12px; font-weight:600;">
                    <?= $cantidad ?> × <?= $etiquetas[$tipo] ?? $tipo ?>
                </span>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Formulario -->
    <form id="form-evaluacion" action="/evaluation/guardar" method="POST">
        <input type="hidden" name="tutoria_id"  value="<?= $tutoria_id ?>">
        <input type="hidden" name="titulo"       value="<?= $titulo ?>">
        <input type="hidden" name="descripcion"  value="<?= $descripcion ?>">

        <div id="preguntas-container"></div>

        <!-- Agregar pregunta extra -->
        <div style="text-align:center; margin:12px 0;">
            <button type="button" onclick="agregarPreguntaManual()"
                    style="border:2px dashed var(--granate-300); color:var(--granate-500); background:none; border-radius:12px; padding:10px 24px; font-size:13px; cursor:pointer; transition:all .15s;"
                    onmouseover="this.style.borderColor='var(--granate-600)';this.style.color='var(--granate-700)'"
                    onmouseout="this.style.borderColor='var(--granate-300)';this.style.color='var(--granate-500)'">
                + Agregar pregunta
            </button>
        </div>

        <!-- Footer -->
        <div style="background:white; border-radius:16px; padding:16px 20px; display:flex; justify-content:flex-end; gap:10px; box-shadow:0 1px 4px rgba(0,0,0,0.08);">
            <a href="/session" class="btn btn-outline" style="font-size:13px; padding:7px 18px;">Cancelar</a>
            <button type="button" onclick="guardarEvaluacion()" class="btn btn-primary" style="font-size:13px; padding:7px 18px;">
    Guardar evaluación
</button>
        </div>
    </form>
</div>

</body>
</html>

<script>
const TIPOS_DISPONIBLES = <?= json_encode(array_values($tipos)) ?>;
const DISTRIBUCION      = <?= json_encode($distribucion) ?>;
</script>
<script src="/javascript/Evaluation.js"></script>
