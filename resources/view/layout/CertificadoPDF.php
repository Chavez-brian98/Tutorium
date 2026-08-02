<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page {
        margin: 20px;
    }
    body {
        font-family: 'serif';
        margin: 0;
        padding: 0;
        background: #faf8f5;
    }
    .certificate-border {
        border: 3px double #9e2820;
        padding: 15px;
        background: white;
    }
    .certificate-border-inner {
        border: 1px solid #c8922a;
        padding: 30px 40px;
    }
    .header {
        text-align: center;
        margin-bottom: 18px;
    }
    .header img {
        width: 80px;
        margin-bottom: 6px;
    }
    .header h1 {
        font-size: 16pt;
        color: #5c1313;
        margin: 4px 0;
        letter-spacing: 1.5pt;
        font-weight: bold;
    }
    .header h2 {
        font-size: 10pt;
        color: #888;
        margin: 2px 0;
        font-weight: normal;
        letter-spacing: 1pt;
    }
    .divider {
        border: none;
        border-top: 2px solid #c8922a;
        margin: 15px auto;
        width: 70%;
    }
    .divider-thin {
        border: none;
        border-top: 1px solid #ddd;
        margin: 12px auto;
        width: 50%;
    }
    .cert-title {
        text-align: center;
        font-size: 18pt;
        color: #9e2820;
        font-weight: bold;
        letter-spacing: 2pt;
        margin: 15px 0 5px;
        text-transform: uppercase;
    }
    .cert-subtitle {
        text-align: center;
        font-size: 10pt;
        color: #666;
        margin: 0 0 18px;
        font-style: italic;
    }
    .body-text {
        text-align: center;
        font-size: 11pt;
        color: #444;
        line-height: 1.8;
        margin: 0 10px 20px;
    }
    .student-name {
        text-align: center;
        font-size: 22pt;
        color: #5c1313;
        font-weight: bold;
        letter-spacing: 1pt;
        margin: 8px 0;
        text-transform: uppercase;
    }
    .info-table {
        width: 70%;
        margin: 15px auto;
        border-collapse: collapse;
    }
    .info-table td {
        padding: 5px 10px;
        font-size: 10.5pt;
        color: #444;
    }
    .info-table td.label {
        font-weight: bold;
        color: #5c1313;
        text-align: right;
        width: 30%;
    }
    .info-table td.value {
        text-align: left;
    }
    .grade-box {
        text-align: center;
        margin: 15px auto;
        padding: 10px 25px;
        display: inline-block;
        width: auto;
    }
    .grade-box .grade-number {
        font-size: 28pt;
        font-weight: bold;
        margin: 0;
    }
    .grade-box .grade-label {
        font-size: 9pt;
        color: #888;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1pt;
    }
    .signature-area {
        margin-top: 30px;
        display: block;
        text-align: center;
    }
    .signature-area table {
        width: 100%;
        margin-top: 10px;
    }
    .signature-area td {
        text-align: center;
        width: 50%;
        vertical-align: bottom;
    }
    .signature-line {
        border-top: 1px solid #333;
        width: 60%;
        margin: 0 auto 4px;
    }
    .signature-name {
        font-size: 10pt;
        font-weight: bold;
        color: #333;
        margin: 2px 0;
    }
    .signature-title {
        font-size: 8.5pt;
        color: #666;
        margin: 0;
    }
    .seal {
        margin-top: -5px;
    }
    .seal img {
        width: 85px;
        opacity: 0.85;
    }
    .footer {
        text-align: center;
        margin-top: 20px;
        font-size: 7.5pt;
        color: #aaa;
    }
    .footer-line {
        border: none;
        border-top: 1px solid #ddd;
        margin: 20px auto 6px;
        width: 100%;
    }
    .badge-approval {
        text-align: center;
        margin: 10px 0;
    }
    .badge-approval span {
        display: inline-block;
        padding: 5px 20px;
        border: 2px solid #15803d;
        color: #15803d;
        font-size: 10pt;
        font-weight: bold;
        letter-spacing: 1pt;
        text-transform: uppercase;
    }
    .eval-summary {
        width: 80%;
        margin: 10px auto;
        border-collapse: collapse;
        font-size: 9pt;
    }
    .eval-summary th {
        background: #5c1313;
        color: white;
        padding: 5px 8px;
        text-align: left;
        font-weight: bold;
        font-size: 8.5pt;
        text-transform: uppercase;
        letter-spacing: 0.5pt;
    }
    .eval-summary td {
        padding: 4px 8px;
        border-bottom: 1px solid #eee;
        color: #444;
    }
    .eval-summary tr:last-child td {
        border-bottom: none;
    }
</style>
</head>
<body>

<div class="certificate-border">
<div class="certificate-border-inner">

    <!-- Header -->
    <div class="header">
        <img src="<?= $logoPath ?>" alt="UNICAES">
        <h1>UNIVERSIDAD CATÓLICA DE EL SALVADOR</h1>
        <h2>FACULTAD DE INGENIERÍA Y ARQUITECTURA</h2>
        <h2>SISTEMA DE TUTORÍAS ACADÉMICAS</h2>
    </div>

    <hr class="divider">

    <!-- Title -->
    <div class="cert-title">Certificado de Finalización</div>
    <div class="cert-subtitle">— Programa de Tutorías Académicas —</div>

    <hr class="divider-thin">

    <!-- Body -->
    <div class="body-text">
        Por medio del presente, se hace constar que el estudiante
    </div>

    <div class="student-name"><?= htmlspecialchars($datos['alumno_nombre']) ?></div>

    <div class="body-text">
        ha completado satisfactoriamente el programa de tutorías en la asignatura de<br>
        <strong><?= htmlspecialchars($datos['materia_nombre']) ?></strong>,<br>
        bajo la tutoría del docente <strong><?= htmlspecialchars($datos['tutor_nombre']) ?></strong>,<br>
        obteniendo un rendimiento académico sobresaliente durante el desarrollo del mismo.
    </div>

    <hr class="divider-thin">

    <!-- Grade -->
    <div style="text-align:center; margin: 10px 0;">
        <div class="grade-box" style="border: 2px solid #15803d; border-radius: 8px;">
            <p class="grade-number" style="color: #15803d;"><?= number_format($promedio, 1) ?></p>
            <p class="grade-label">Promedio general</p>
        </div>
    </div>

    <?php if (!empty($notas) && count($notas) > 1): ?>
    <table class="eval-summary">
        <tr>
            <th>Evaluación</th>
            <th style="text-align:center; width:80px;">Nota</th>
        </tr>
        <?php foreach ($notas as $n): ?>
        <tr>
            <td><?= htmlspecialchars($n['titulo']) ?></td>
            <td style="text-align:center;"><?= $n['nota'] !== null ? number_format($n['nota'], 1) : '—' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <div class="badge-approval">
        <span>✓ Aprobado</span>
    </div>

    <hr class="divider-thin">


    <div class="footer">
        <p>UNICAES — Sistema de Tutorías Académicas • <?= date('Y') ?></p>
        <p>Documento emitido electrónicamente el <?= date('d \d\e F \d\e\l Y') ?></p>
    </div>

</div>
</div>

</body>
</html>
