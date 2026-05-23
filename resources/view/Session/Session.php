<?php
// Variables que llegan desde SessionController::mostrar():
// $sesion, $sesiones, $tutoria_id, $numero, $sesion_id, $alumno_id, $yaAsistencia, $asisPresente
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-xl font-semibold mb-4">Asignatura: <?= htmlspecialchars($materia) ?></h1>

    <div class="flex gap-6">

        <!-- Columna izquierda -->
        <div class="w-48 bg-white shadow p-4 flex flex-col gap-2">
            <?php foreach ($sesiones as $s): ?>
                <a href="/session?numero=<?= $s['numero'] ?>"
                   class="text-center py-2 px-4 rounded-xl border text-sm bg-card
                          <?= $numero == $s['numero']
                              ? 'bg-[#800000]/90 border-[#9e2820] font-semibold text-white'
                              : 'hover:bg-gray-100' ?>">
                    Sesión <?= $s['numero'] ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Columna derecha -->
        <div class="flex-1 bg-white rounded-2xl shadow p-6">

            <!-- Cabecera -->
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Horario asignado: <?= htmlspecialchars($horario) ?></p>
                    <button id="btn-asistencia" onclick="marcarAsistencia()"
                            class="text-sm border rounded-xl px-4 py-2 mt-2 cursor-pointer hover:bg-gray-100 flex items-center gap-2">
                        <span id="icono-asistencia">
                            <?php if ($yaAsistencia): ?>
                                <i class="bi <?= $asisPresente ? 'bi-check-circle-fill text-green-600' : 'bi-x-circle-fill text-red-500' ?>"></i>
                            <?php else: ?>
                                <i class="bi bi-calendar-check"></i>
                            <?php endif; ?>
                        </span>
                        <span id="texto-asistencia">
                            <?= $yaAsistencia ? 'Modificar asistencia' : 'Marcar asistencia' ?>
                        </span>
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-400" id="toggle-label">Edición desactivada</span>
                    <button onclick="toggleEdicion()" id="toggle-btn"
                            class="relative w-11 h-6 rounded-full bg-gray-200 transition-colors duration-300 focus:outline-none">
                        <span id="toggle-dot"
                              class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform duration-300">
                        </span>
                    </button>
                    <button onclick="abrirModalEvaluacion()"
                            class="border rounded-xl px-4 py-2 text-sm hover:bg-gray-100">
                        Crear evaluación
                    </button>
                </div>
            </div>

            <!-- VISTA LECTURA (visible por defecto) -->
            <!-- Link de la sesión (SIEMPRE visible) -->
            <div class="border border-gray-200 flex-1 rounded-xl p-5 m-1 bg-white shadow-sm hover:shadow-md transition-shadow duration-300">
                <label for="link-sesion"
                       class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">
                    Link de la sesión
                </label>
                <div class="relative flex items-center">
                    <span class="absolute left-3 text-gray-300 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                    </span>
                    <input type="text" id="link-sesion" name="link-sesion"
                           disabled
                           value="<?= htmlspecialchars($sesion['link'] ?? '') ?>"
                           placeholder="https://..."
                           class="w-full pl-10 pr-4 py-2.5 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg outline-none transition-all duration-200 placeholder:text-gray-300 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 hover:border-gray-300 disabled:opacity-50 disabled:cursor-not-allowed" />
                </div>
            </div>
            <div id="seccion-lectura" class="border rounded-xl p-4 m-1 min-h-48 text-gray-400 text-sm">
                <?php if (!empty($material['texto'])): ?>
                    <div class="text-gray-700"><?= $material['texto'] ?></div>
                <?php else: ?>
                    Sin contenido aún.
                <?php endif; ?>
            </div>

            <!-- VISTA EDICIÓN (oculta por defecto) -->
            <div id="seccion-editable" class="hidden">
                <!-- CKEditor -->
                <div class="border rounded-xl p-4 m-1 text-gray-400 text-sm">
                    <textarea id="editor-sesion"><?= htmlspecialchars($material['texto'] ?? '') ?></textarea>
                </div>
                <div class="flex justify-end mt-3 mr-1">
                    <button onclick="guardarContenido()"
                            class="btn btn-primary text-sm px-5 py-2">
                        Guardar contenido
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-between mt-4 text-sm text-gray-400">
                <span>Estudiante asignado: <?= htmlspecialchars($nombre_alumno) ?></span>
                <span>Fecha <?= date('d/m/Y') ?></span>
            </div>

        </div>
    </div>

    <!-- Modal crear evaluación -->
    <div id="modal-evaluacion"
         class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div style="background:white; border-radius:16px; width:100%; max-width:580px; box-shadow:0 8px 32px rgba(0,0,0,0.18); overflow:hidden;">

            <!-- Header -->
            <div style="background:var(--granate-700); padding:14px 20px; display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="color:var(--dorado-400); font-size:18px; font-weight:700;">+</span>
                    <span style="color:white; font-family:'Playfair Display',serif; font-size:15px; letter-spacing:.02em;">
                        Crear evaluación
                    </span>
                </div>
                <button onclick="cerrarModalEvaluacion()"
                        style="color:white; background:none; border:none; font-size:18px; cursor:pointer; line-height:1; opacity:.8;"
                        onmouseover="this.style.opacity='1'"
                        onmouseout="this.style.opacity='.8'">✕</button>
            </div>

            <!-- Cuerpo -->
            <div style="padding:24px 24px 20px;">

                <!-- Fila 1: Título y Descripción -->
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
                    <div>
                        <label style="font-size:12px; color:var(--texto-secundario); display:block; margin-bottom:5px;">Título</label>
                        <input type="text" id="eval-titulo"
                               style="width:100%; background:var(--fondo-card); border:none; border-radius:8px; padding:9px 12px; font-size:14px; outline:none; box-sizing:border-box; color:var(--texto-principal);"
                               onfocus="this.style.background='var(--fondo-hover)'"
                               onblur="this.style.background='var(--fondo-card)'">
                    </div>
                    <div>
                        <label style="font-size:12px; color:var(--texto-secundario); display:block; margin-bottom:5px;">Descripción / indicaciones</label>
                        <input type="text" id="eval-descripcion"
                               style="width:100%; background:var(--fondo-card); border:none; border-radius:8px; padding:9px 12px; font-size:14px; outline:none; box-sizing:border-box; color:var(--texto-principal);"
                               onfocus="this.style.background='var(--fondo-hover)'"
                               onblur="this.style.background='var(--fondo-card)'">
                    </div>
                </div>

                <!-- Separador -->
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
                    <div style="flex:1; height:1px; background:var(--granate-100);"></div>
                    <span style="font-size:11px; color:var(--granate-400); font-weight:600; letter-spacing:.06em; text-transform:uppercase;">
                        Sección de preguntas
                    </span>
                    <div style="flex:1; height:1px; background:var(--granate-100);"></div>
                </div>

                <!-- Filas de tipo + cantidad -->
                <div id="filas-tipos" style="display:flex; flex-direction:column; gap:10px; margin-bottom:14px;"></div>

                <!-- Botón agregar tipo -->
                <button type="button" onclick="agregarFilaTipo()"
                        id="btn-agregar-tipo"
                        style="font-size:12px; color:var(--granate-500); background:none; border:none; cursor:pointer; padding:0; font-family:'Nunito',sans-serif; margin-bottom:20px;"
                        onmouseover="this.style.color='var(--granate-700)'"
                        onmouseout="this.style.color='var(--granate-500)'">
                    + Agregar tipo
                </button>

                <!-- Botones footer -->
                <div style="display:flex; justify-content:flex-end; gap:10px;">
                    <button onclick="abrirImportarXML()"
                            style="background:var(--granate-100); color:var(--granate-600); border:none; border-radius:8px; padding:8px 16px; font-size:13px; font-weight:600; cursor:pointer; font-family:'Nunito',sans-serif;"
                            onmouseover="this.style.background='var(--granate-200)'"
                            onmouseout="this.style.background='var(--granate-100)'">
                        Importar XML
                    </button>
                    <button onclick="guardarConfigEvaluacion()"
                            style="background:var(--granate-600); color:white; border:none; border-radius:8px; padding:8px 20px; font-size:13px; font-weight:600; cursor:pointer; font-family:'Nunito',sans-serif;"
                            onmouseover="this.style.background='var(--granate-700)'"
                            onmouseout="this.style.background='var(--granate-600)'">
                        Guardar
                    </button>
                    <button onclick="cerrarModalEvaluacion()"
                            style="background:var(--fondo-card); color:var(--texto-secundario); border:none; border-radius:8px; padding:8px 16px; font-size:13px; font-weight:600; cursor:pointer; font-family:'Nunito',sans-serif;"
                            onmouseover="this.style.background='var(--fondo-hover)'"
                            onmouseout="this.style.background='var(--fondo-card)'">
                        Cancelar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <input type="file" id="input-xml" accept=".xml" class="hidden" onchange="procesarXML(this)">

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
    // Variables PHP → JS (deben quedarse aquí)
    const SESSION_CONFIG = {
        numero:             <?= $numero ?>,
        sesionId:           <?= $sesion_id ?>,
        tutoriaId:          <?= $tutoria_id ?>,
        alumnoId:           <?= $alumno_id ?>,
        yaAsistencia:       <?= $yaAsistencia ? 'true' : 'false' ?>,
        asisPresente:       <?= $asisPresente === null ? 'null' : ($asisPresente ? 'true' : 'false') ?>,
    };
    const FLASH = "<?= $_GET['ok'] ?? '' ?>";
</script>
<script src="/javascript/Session.js"></script>

</body>
</html>