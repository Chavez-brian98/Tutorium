<?php
// Variables esperadas:
// $evaluacion - array con datos de la evaluación (id, titulo, descripcion, fecha_creacion, nombres, apellidos, materia, alumno_nombre)
// $detalle    - array de preguntas con respuestas (pregunta_id, enunciado, tipo, respuesta_alumno, respuesta_correcta, es_correcta)
// $logoPath   - ruta absoluta al logo

$aciertos = 0;
$total = count($detalle);
foreach ($detalle as $d) { if ($d['es_correcta']) $aciertos++; }
$nota = $total > 0 ? round(($aciertos / $total) * 10, 1) : 0;
$colorNota = $nota >= 7 ? '#15803d' : '#dc2626';
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 10pt; color: #333; line-height: 1.5; margin: 0; padding: 0; }
        h1 { color: #5c1313; font-size: 16pt; margin: 0 0 4px 0; }
        .subtitulo { color: #888; font-size: 10pt; margin: 0 0 16px 0; }
        .card { background: #faf7f5; border: 1px solid #e8ddd8; padding: 14px 18px; margin: 12px 0; }
        .card table { width: 100%; }
        .card td { padding: 3px 0; font-size: 10pt; vertical-align: top; }
        .label { color: #9e2820; font-weight: bold; width: 100px; }
        .descripcion { font-style: italic; color: #666; margin: 8px 0 16px 0; padding: 10px 14px; border-left: 3px solid #9e2820; background: #fdfcfa; }
        table.resultados { width: 100%; border-collapse: collapse; margin: 16px 0; }
        table.resultados thead th { background: #9e2820; color: #fff; padding: 8px 10px; text-align: left; font-size: 9pt; font-weight: 600; }
        table.resultados td { padding: 8px 10px; border-bottom: 1px solid #e8ddd8; font-size: 9.5pt; }
        table.resultados tr:nth-child(even) td { background: #faf7f5; }
        .correcto { color: #15803d; font-weight: bold; }
        .incorrecto { color: #dc2626; font-weight: bold; }
        .resumen { background: #f9f3f0; border: 1px solid #e8ddd8; border-radius: 10px; padding: 16px 20px; margin-top: 18px; text-align: right; }
        .resumen .nota { font-size: 20pt; font-weight: bold; }
        .resumen .detalle { font-size: 9pt; color: #888; }
    </style>
</head>
<body>

    <h1><?= htmlspecialchars($evaluacion['titulo']) ?></h1>
    <p class="subtitulo">Reporte de evaluación académica</p>

    <div class="card">
        <table>
            <tr><td class="label">Estudiante</td><td><?= htmlspecialchars($evaluacion['alumno_nombre'] ?? '—') ?></td></tr>
            <tr><td class="label">Materia</td><td><?= htmlspecialchars($evaluacion['materia']) ?></td></tr>
            <tr><td class="label">Tutor</td><td><?= htmlspecialchars(trim(($evaluacion['nombres'] ?? '') . ' ' . ($evaluacion['apellidos'] ?? ''))) ?></td></tr>
            <tr><td class="label">Fecha</td><td><?= date('d/m/Y', strtotime($evaluacion['fecha_creacion'])) ?></td></tr>
        </table>
    </div>

    <?php if (!empty($evaluacion['descripcion'])): ?>
    <div class="descripcion"><?= htmlspecialchars($evaluacion['descripcion']) ?></div>
    <?php endif; ?>

    <table class="resultados">
        <thead>
            <tr>
                <th style="width:32px;">#</th>
                <th>Pregunta</th>
                <th>Tu respuesta</th>
                <th>Respuesta correcta</th>
                <th style="width:52px;">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalle as $i => $d): $num = $i + 1; ?>
            <tr>
                <td style="text-align:center;"><?= $num ?></td>
                <td><?= htmlspecialchars($d['enunciado']) ?></td>
                <td><?= $d['respuesta_alumno'] ? htmlspecialchars($d['respuesta_alumno']) : '<em style="color:#999;">Sin responder</em>' ?></td>
                <td class="correcto"><?= htmlspecialchars($d['respuesta_correcta'] ?? '—') ?></td>
                <td style="text-align:center;" class="<?= $d['es_correcta'] ? 'correcto' : 'incorrecto' ?>"><?= $d['es_correcta'] ? '✓' : '✗' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="resumen">
        <div style="font-size:9pt; color:#888; margin-bottom:4px;">Calificación final</div>
        <span class="nota" style="color:<?= $colorNota ?>;"><?= number_format($nota, 1) ?> <span style="font-size:12pt;color:#999;">/ 10</span></span>
        <div class="detalle"><?= $aciertos ?> de <?= $total ?> preguntas correctas</div>
    </div>

</body>
</html>
