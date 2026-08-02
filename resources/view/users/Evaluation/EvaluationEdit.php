<?php
$evaluacion = $evaluacion ?? [];
$preguntas  = $evaluacion['preguntas'] ?? [];
$tutoria_id = $evaluacion['tutoria_id'] ?? 0;

$tipoMap = [
    'opcion_multiple' => 'seleccion',
    'respuesta_corta' => 'cerrada',
    'VyF'            => 'VyF',
];

$tipoLabel = [
    'VyF'       => 'Verdadero / Falso',
    'cerrada'   => 'Pregunta cerrada',
    'seleccion' => 'Opción múltiple',
];
?>
<div class="flex min-h-screen">

<?php include __DIR__ . '/../../layout/sidebar.php'; ?>

<div class="flex-1 p-6 md:p-8">

    <div style="background:white; border-radius:16px; border-top:8px solid #9e2820; padding:24px; margin-bottom:16px; box-shadow:0 1px 4px rgba(0,0,0,0.08);">
        <div class="mb-4">
            <label style="font-size:12px; font-weight:600; color:#666; display:block; margin-bottom:4px;">Título</label>
            <input type="text" id="eval-titulo" value="<?= htmlspecialchars($evaluacion['titulo'] ?? '') ?>"
                   style="width:100%; border:1.5px solid #e0dbd3; border-radius:8px; padding:10px 14px; font-size:16px; font-weight:600; outline:none; box-sizing:border-box; font-family:'Nunito',sans-serif; color:#333;">
        </div>
        <div>
            <label style="font-size:12px; font-weight:600; color:#666; display:block; margin-bottom:4px;">Descripción</label>
            <textarea id="eval-descripcion" rows="2"
                      style="width:100%; border:1.5px solid #e0dbd3; border-radius:8px; padding:10px 14px; font-size:14px; outline:none; resize:none; box-sizing:border-box; font-family:'Nunito',sans-serif; color:#555;"><?= htmlspecialchars($evaluacion['descripcion'] ?? '') ?></textarea>
        </div>
    </div>

    <form id="form-evaluacion" action="/evaluation/actualizar" method="POST">
        <input type="hidden" name="evaluacion_id" value="<?= $evaluacion['id'] ?>">
        <input type="hidden" name="tutoria_id"    value="<?= $tutoria_id ?>">
        <input type="hidden" name="titulo"        id="input-titulo" value="<?= htmlspecialchars($evaluacion['titulo'] ?? '') ?>">
        <input type="hidden" name="descripcion"   id="input-descripcion" value="<?= htmlspecialchars($evaluacion['descripcion'] ?? '') ?>">

        <div id="preguntas-container">
            <?php $n = 0; ?>
            <?php foreach ($preguntas as $pq): ?>
                <?php
                $n++;
                $tipoForm = $tipoMap[$pq['tipo']] ?? 'cerrada';
                $opciones = $pq['opciones'] ?? [];
                ?>
                <div id="pregunta-<?= $n ?>" style="background:white; border-radius:16px; padding:20px; margin-bottom:14px; border-left:4px solid #9e2820; box-shadow:0 1px 4px rgba(0,0,0,0.07);">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                        <span style="font-size:12px; font-weight:700; color:#9e2820; text-transform:uppercase; letter-spacing:.05em;">Pregunta <?= $n ?></span>
                        <button type="button" onclick="this.closest('[id^=pregunta-]').remove()"
                                style="color:#999; background:none; border:none; font-size:1.1rem; cursor:pointer;"
                                onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#999'">✕</button>
                    </div>

                    <textarea name="enunciado[<?= $n ?>]" rows="2"
                              placeholder="Escribe el enunciado..."
                              style="width:100%; border:1.5px solid #e0dbd3; border-radius:8px; padding:8px 12px; font-size:14px; outline:none; resize:none; box-sizing:border-box; font-family:'Nunito',sans-serif; margin-bottom:12px;"><?= htmlspecialchars($pq['enunciado']) ?></textarea>

                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                        <label style="font-size:12px; color:#666;">Tipo:</label>
                        <select name="tipo[<?= $n ?>]" onchange="cambiarTipoEdit(<?= $n ?>, this.value)"
                                style="border:1.5px solid #e0dbd3; border-radius:8px; padding:5px 10px; font-size:13px; outline:none; color:#333; background:white;">
                            <option value="VyF"       <?= $tipoForm === 'VyF'       ? 'selected' : '' ?>>Verdadero / Falso</option>
                            <option value="cerrada"   <?= $tipoForm === 'cerrada'   ? 'selected' : '' ?>>Pregunta cerrada</option>
                            <option value="seleccion" <?= $tipoForm === 'seleccion' ? 'selected' : '' ?>>Opción múltiple</option>
                        </select>
                    </div>

                    <div id="opciones-<?= $n ?>">
                        <?php if ($tipoForm === 'VyF'): ?>
                            <?php
                            $correctaVyF = '';
                            foreach ($opciones as $o) {
                                if ($o['es_correcta']) {
                                    $correctaVyF = strtolower($o['texto']) === 'verdadero' ? 'verdadero' : 'falso';
                                }
                            }
                            ?>
                            <div style="display:flex; gap:24px; margin-top:4px;">
                                <label style="display:flex; align-items:center; gap:6px; font-size:13px; cursor:pointer;">
                                    <input type="radio" name="correcta_vyf[<?= $n ?>]" value="verdadero" style="accent-color:#9e2820;" <?= $correctaVyF === 'verdadero' ? 'checked' : '' ?>>
                                    Verdadero
                                </label>
                                <label style="display:flex; align-items:center; gap:6px; font-size:13px; cursor:pointer;">
                                    <input type="radio" name="correcta_vyf[<?= $n ?>]" value="falso" style="accent-color:#9e2820;" <?= $correctaVyF === 'falso' ? 'checked' : '' ?>>
                                    Falso
                                </label>
                            </div>
                            <p style="font-size:11px; color:#999; margin-top:4px;">Marca la respuesta correcta.</p>
                        <?php elseif ($tipoForm === 'cerrada'): ?>
                            <p style="font-size:12px; color:#999; margin-top:4px;">El alumno responderá con texto libre.</p>
                        <?php elseif ($tipoForm === 'seleccion'): ?>
                            <div id="opts-<?= $n ?>" style="display:flex; flex-direction:column; gap:8px; margin-bottom:8px;">
                                <?php foreach ($opciones as $i => $o): ?>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <input type="radio" name="correcta_sel[<?= $n ?>]" value="<?= $i ?>" style="accent-color:#9e2820;" title="Marcar como correcta" <?= $o['es_correcta'] ? 'checked' : '' ?>>
                                        <input type="text" name="opcion[<?= $n ?>][]" value="<?= htmlspecialchars($o['texto']) ?>" placeholder="Opción <?= $i + 1 ?>"
                                               style="flex:1; border:1.5px solid #e0dbd3; border-radius:8px; padding:6px 10px; font-size:13px; outline:none; font-family:'Nunito',sans-serif;">
                                        <button type="button" onclick="this.parentElement.remove()"
                                                style="color:#999; background:none; border:none; cursor:pointer; font-size:1rem;"
                                                onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#999'">✕</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" onclick="agregarOpcionEdit(<?= $n ?>)"
                                    style="font-size:13px; color:#9e2820; background:none; border:none; cursor:pointer; padding:0; font-family:'Nunito',sans-serif;">+ Agregar opción</button>
                            <p style="font-size:11px; color:#999; margin-top:4px;">Marca el radio de la opción correcta.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center; margin:12px 0;">
            <button type="button" onclick="agregarPreguntaEdit()"
                    style="border:2px dashed #b88484; color:#9e2820; background:none; border-radius:12px; padding:10px 24px; font-size:13px; cursor:pointer; transition:all .15s;"
                    onmouseover="this.style.borderColor='#9e2820';this.style.color='#7a2019'"
                    onmouseout="this.style.borderColor='#b88484';this.style.color='#9e2820'">
                + Agregar pregunta
            </button>
        </div>

        <div style="background:white; border-radius:16px; padding:16px 20px; display:flex; justify-content:flex-end; gap:10px; box-shadow:0 1px 4px rgba(0,0,0,0.08);">
            <a href="/evaluaciones" style="font-size:13px; padding:7px 18px; border:1.5px solid #ddd; border-radius:8px; color:#666; text-decoration:none; display:inline-flex; align-items:center;">Cancelar</a>
            <button type="button" onclick="guardarEvaluacionEdit()"
                    style="font-size:13px; padding:7px 18px; background:#9e2820; color:white; border:none; border-radius:8px; cursor:pointer; display:inline-flex; align-items:center;">
                Guardar cambios
            </button>
        </div>
    </form>
</div>

<script>
let contadorPreguntasEdit = <?= $n ?>;
let contadorOpcionesEdit = {};

document.querySelectorAll('[id^="opts-"]').forEach(el => {
    const n = parseInt(el.id.replace('opts-', ''));
    contadorOpcionesEdit[n] = el.children.length;
});

function cambiarTipoEdit(n, tipo) {
    const contenedor = document.getElementById(`opciones-${n}`);
    if (tipo === 'VyF') {
        contenedor.innerHTML = `
            <div style="display:flex; gap:24px; margin-top:4px;">
                <label style="display:flex; align-items:center; gap:6px; font-size:13px; cursor:pointer;">
                    <input type="radio" name="correcta_vyf[${n}]" value="verdadero" style="accent-color:#9e2820;">
                    Verdadero
                </label>
                <label style="display:flex; align-items:center; gap:6px; font-size:13px; cursor:pointer;">
                    <input type="radio" name="correcta_vyf[${n}]" value="falso" style="accent-color:#9e2820;">
                    Falso
                </label>
            </div>
            <p style="font-size:11px; color:#999; margin-top:4px;">Marca la respuesta correcta.</p>
        `;
    } else if (tipo === 'cerrada') {
        contenedor.innerHTML = `<p style="font-size:12px; color:#999; margin-top:4px;">El alumno responderá con texto libre.</p>`;
    } else if (tipo === 'seleccion') {
        contenedor.innerHTML = `
            <div id="opts-${n}" style="display:flex; flex-direction:column; gap:8px; margin-bottom:8px;"></div>
            <button type="button" onclick="agregarOpcionEdit(${n})"
                    style="font-size:13px; color:#9e2820; background:none; border:none; cursor:pointer; padding:0; font-family:'Nunito',sans-serif;">+ Agregar opci&oacute;n</button>
            <p style="font-size:11px; color:#999; margin-top:4px;">Marca el radio de la opci&oacute;n correcta.</p>
        `;
        contadorOpcionesEdit[n] = 0;
        agregarOpcionEdit(n);
        agregarOpcionEdit(n);
    }
}

function agregarOpcionEdit(n) {
    const idx = contadorOpcionesEdit[n] !== undefined ? contadorOpcionesEdit[n]++ : (contadorOpcionesEdit[n] = 0);
    const container = document.getElementById(`opts-${n}`);
    if (!container) return;
    const div = document.createElement('div');
    div.style.cssText = 'display:flex; align-items:center; gap:8px;';
    div.innerHTML = `
        <input type="radio" name="correcta_sel[${n}]" value="${idx}" style="accent-color:#9e2820;" title="Marcar como correcta">
        <input type="text" name="opcion[${n}][]" placeholder="Opci&oacute;n ${idx + 1}"
               style="flex:1; border:1.5px solid #e0dbd3; border-radius:8px; padding:6px 10px; font-size:13px; outline:none; font-family:'Nunito',sans-serif;">
        <button type="button" onclick="this.parentElement.remove()"
                style="color:#999; background:none; border:none; cursor:pointer; font-size:1rem;"
                onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#999'">✕</button>
    `;
    container.appendChild(div);
}

function agregarPreguntaEdit() {
    contadorPreguntasEdit++;
    const n = contadorPreguntasEdit;
    const container = document.getElementById('preguntas-container');
    const div = document.createElement('div');
    div.id = `pregunta-${n}`;
    div.style.cssText = 'background:white; border-radius:16px; padding:20px; margin-bottom:14px; border-left:4px solid #9e2820; box-shadow:0 1px 4px rgba(0,0,0,0.07);';
    div.innerHTML = `
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <span style="font-size:12px; font-weight:700; color:#9e2820; text-transform:uppercase; letter-spacing:.05em;">Pregunta ${n}</span>
            <button type="button" onclick="this.closest('[id^=pregunta-]').remove()"
                    style="color:#999; background:none; border:none; font-size:1.1rem; cursor:pointer;"
                    onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#999'">✕</button>
        </div>
        <textarea name="enunciado[${n}]" rows="2" placeholder="Escribe el enunciado..."
                  style="width:100%; border:1.5px solid #e0dbd3; border-radius:8px; padding:8px 12px; font-size:14px; outline:none; resize:none; box-sizing:border-box; font-family:'Nunito',sans-serif; margin-bottom:12px;"></textarea>
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
            <label style="font-size:12px; color:#666;">Tipo:</label>
            <select name="tipo[${n}]" onchange="cambiarTipoEdit(${n}, this.value)"
                    style="border:1.5px solid #e0dbd3; border-radius:8px; padding:5px 10px; font-size:13px; outline:none; color:#333; background:white;">
                <option value="cerrada">Pregunta cerrada</option>
                <option value="VyF">Verdadero / Falso</option>
                <option value="seleccion">Opci&oacute;n m&uacute;ltiple</option>
            </select>
        </div>
        <div id="opciones-${n}"><p style="font-size:12px; color:#999; margin-top:4px;">El alumno responder&aacute; con texto libre.</p></div>
    `;
    container.appendChild(div);
}

function guardarEvaluacionEdit() {
    const enunciados = document.querySelectorAll('textarea[name^="enunciado"]');
    for (const e of enunciados) {
        if (!e.value.trim()) {
            Swal.fire({ title: 'Falta información', text: 'Todos los enunciados deben estar completos.', icon: 'warning', confirmButtonColor: '#9e2820' });
            e.focus();
            return;
        }
    }
    const titulo = document.getElementById('eval-titulo').value.trim();
    if (!titulo) {
        Swal.fire({ title: 'Falta el título', text: 'La evaluación debe tener un título.', icon: 'warning', confirmButtonColor: '#9e2820' });
        document.getElementById('eval-titulo').focus();
        return;
    }
    document.getElementById('input-titulo').value = titulo;
    document.getElementById('input-descripcion').value = document.getElementById('eval-descripcion').value.trim();
    document.getElementById('form-evaluacion').submit();
}
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</div>
