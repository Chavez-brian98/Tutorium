<?php
$sesion = $_GET['sesion'] ?? 1;
$tutoria_id =  1; // 👈 agregar para pasar el tutoria_id a la vista
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.css">
</head>
<body class="bg-gray-100 p-6">

    <h1 class="text-xl font-semibold mb-4">Asignatura: Tutoria asignada</h1>

    <div class="flex gap-6">

        <!-- Columna izquierda -->
        <div class="w-48 bg-white shadow p-4 flex flex-col gap-2">
            <?php for($i = 1; $i <= 10; $i++): ?>
                <a href="Session.php?sesion=<?= $i ?>"
                   class="text-center py-2 px-4 rounded-xl border text-sm
                          <?= $sesion == $i ? 'bg-[#800000]/90 border-[#9e2820] font-semibold text-white' : 'hover:bg-gray-100' ?>">
                    Sesión <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>

        <!-- Columna derecha -->
        <div class="flex-1 bg-white rounded-2xl shadow p-6">

            <!-- Cabecera -->
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Horario asignado: lunes/2pm - 3pm</p>
                    <button onclick="marcarAsistencia()"
                            class="text-sm border rounded-xl px-4 py-2 mt-2 cursor-pointer hover:bg-gray-100">
                        Marcar asistencia
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
                     <!-- Botón crear evaluación: pasa el tutoria_id -->
                    <!-- Botón (reemplaza el que ya tienes) -->
<button onclick="abrirModalEvaluacion()"
        class="border rounded-xl px-4 py-2 text-sm hover:bg-gray-100">
    Crear evaluación
</button>
                </div>
            </div>

            <!-- VISTA LECTURA (visible por defecto) -->
            <div id="seccion-lectura" class="border rounded-xl p-4 m-1 min-h-48 text-gray-400 text-sm">
                Contenido de la sesión <?= $sesion ?>
            </div>

            <!-- VISTA EDICIÓN (oculta por defecto) -->
            <div id="seccion-editable" class="hidden">

                <!-- Link de la sesión -->
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
                               placeholder="https://..."
                               class="w-full pl-10 pr-4 py-2.5 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg outline-none transition-all duration-200 placeholder:text-gray-300 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 hover:border-gray-300" />
                    </div>
                </div>

                <!-- CKEditor -->
                <div class="border rounded-xl p-4 m-1 text-gray-400 text-sm">
                    <textarea id="editor-sesion"></textarea>
                </div>

            </div>

            <!-- Footer -->
            <div class="flex justify-between mt-4 text-sm text-gray-400">
                <span>Estudiante asignado</span>
                <span>Fecha <?= date('d/m/Y') ?></span>
            </div>

        </div>
    </div>

    <!-- Modal crear evaluación -->
<div id="modal-evaluacion"
     class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl p-6 w-full max-w-xl shadow-xl"
         style="border: 2px solid var(--granate-600);">

        <!-- Header -->
        <div class="flex justify-between items-center mb-5">
            <h2 style="font-family:'Playfair Display',serif; color:var(--granate-700); font-size:1.1rem; margin:0;">
                Crear evaluación
            </h2>
            <button onclick="cerrarModalEvaluacion()"
                    style="color:var(--granate-400); font-size:1.2rem; background:none; border:none; cursor:pointer;">✕</button>
        </div>

        <!-- Fila 1 -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label style="font-size:12px; color:var(--texto-secundario); display:block; margin-bottom:4px;">Título</label>
                <input type="text" id="eval-titulo"
                       style="width:100%; border:1.5px solid var(--granate-200); border-radius:8px; padding:8px 12px; font-size:14px; outline:none; box-sizing:border-box;"
                       onfocus="this.style.borderColor='var(--granate-600)'"
                       onblur="this.style.borderColor='var(--granate-200)'">
            </div>
            <div>
                <label style="font-size:12px; color:var(--texto-secundario); display:block; margin-bottom:4px;">Descripción / indicaciones</label>
                <input type="text" id="eval-descripcion"
                       style="width:100%; border:1.5px solid var(--granate-200); border-radius:8px; padding:8px 12px; font-size:14px; outline:none; box-sizing:border-box;"
                       onfocus="this.style.borderColor='var(--granate-600)'"
                       onblur="this.style.borderColor='var(--granate-200)'">
            </div>
        </div>

        <!-- Fila 2 -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label style="font-size:12px; color:var(--texto-secundario); display:block; margin-bottom:4px;">Total de preguntas</label>
                <input type="number" id="eval-total" min="1" max="50"
                       style="width:100%; border:1.5px solid var(--granate-200); border-radius:8px; padding:8px 12px; font-size:14px; outline:none; box-sizing:border-box;"
                       onfocus="this.style.borderColor='var(--granate-600)'"
                       onblur="this.style.borderColor='var(--granate-200)'"
                       placeholder="Ej: 10">
            </div>
            <div>
                <label style="font-size:12px; color:var(--texto-secundario); display:block; margin-bottom:6px;">Tipo de preguntas</label>
                <div style="border:1.5px solid var(--granate-200); border-radius:8px; padding:8px 12px; display:flex; flex-direction:column; gap:6px;">
                    <label style="display:flex; align-items:center; gap:8px; font-size:13px; cursor:pointer;">
                        <input type="checkbox" value="VyF" class="tipo-check" style="accent-color:var(--granate-600);">
                        Verdadero / Falso
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; font-size:13px; cursor:pointer;">
                        <input type="checkbox" value="cerrada" class="tipo-check" style="accent-color:var(--granate-600);">
                        Pregunta cerrada
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; font-size:13px; cursor:pointer;">
                        <input type="checkbox" value="seleccion" class="tipo-check" style="accent-color:var(--granate-600);">
                        Opción múltiple
                    </label>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button onclick="abrirImportarXML()" class="btn btn-outline" style="font-size:13px; padding:7px 16px;">
                Importar XML
            </button>
            <button onclick="guardarConfigEvaluacion()" class="btn btn-primary" style="font-size:13px; padding:7px 16px;">
                Guardar
            </button>
            <button onclick="cerrarModalEvaluacion()" class="btn btn-outline" style="font-size:13px; padding:7px 16px;">
                Cancelar
            </button>
        </div>

    </div>
</div>

<input type="file" id="input-xml" accept=".xml" class="hidden" onchange="procesarXML(this)">
</body>
</html>

<!-- CKEditor (una sola vez) -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    let editorInstance = null;
    let edicionActiva = false;

    function toggleEdicion() {
    edicionActiva = !edicionActiva;

    const seccionEditable = document.getElementById('seccion-editable');
    const seccionLectura  = document.getElementById('seccion-lectura');
    const toggleBtn       = document.getElementById('toggle-btn');
    const toggleDot       = document.getElementById('toggle-dot');
    const toggleLabel     = document.getElementById('toggle-label');
    const linkInput       = document.getElementById('link-sesion'); // 👈 agregar

    if (edicionActiva) {
        seccionEditable.classList.remove('hidden');
        seccionLectura.classList.add('hidden');
        linkInput.disabled = false; // 👈 habilitar
        linkInput.classList.remove('opacity-50', 'cursor-not-allowed');

        toggleBtn.classList.replace('bg-gray-200', 'bg-[#9e2820]');
        toggleDot.classList.add('translate-x-5');
        toggleLabel.textContent = 'Edición activada';
        toggleLabel.classList.replace('text-gray-400', 'text-[#9e2820]');

        if (!editorInstance) {
            ClassicEditor
                .create(document.querySelector('#editor-sesion'), {
                    placeholder: 'Escribe el contenido de la sesión <?= $sesion ?>...',
                    toolbar: ['heading','|','bold','italic','underline','|','bulletedList','numberedList','|','link','blockQuote','|','undo','redo']
                })
                .then(editor => { editorInstance = editor; })
                .catch(error => console.error(error));
        }

    } else {
        seccionEditable.classList.add('hidden');
        seccionLectura.classList.remove('hidden');
        linkInput.disabled = true; // 👈 deshabilitar
        linkInput.classList.add('opacity-50', 'cursor-not-allowed');

        toggleBtn.classList.replace('bg-[#9e2820]', 'bg-gray-200');
        toggleDot.classList.remove('translate-x-5');
        toggleLabel.textContent = 'Edición desactivada';
        toggleLabel.classList.replace('text-[#9e2820]', 'text-gray-400');
    }
}

    function marcarAsistencia() {
        Swal.fire({
            title: 'Sesión <?= $sesion ?>',
            text: '¿El estudiante asistió a esta sesión?',
            icon: 'question',
            showDenyButton: true,
            confirmButtonText: 'Sí asistió',
            denyButtonText: 'No asistió',
            confirmButtonColor: '#9e2820',
            denyButtonColor: '#6b7280'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Asistencia marcada', '', 'success')
            } else if (result.isDenied) {
                Swal.fire('Ausencia marcada', '', 'info')
            }
        })
    }
    function abrirModalEvaluacion() {
    document.getElementById('modal-evaluacion').classList.remove('hidden');
}

function cerrarModalEvaluacion() {
    document.getElementById('modal-evaluacion').classList.add('hidden');
}

function abrirImportarXML() {
    document.getElementById('input-xml').click();
}

function procesarXML(input) {
    // Por ahora solo confirmamos que se seleccionó el archivo
    // Cuando tengas el backend, aquí haces el fetch POST
    if (input.files.length > 0) {
        alert('Archivo seleccionado: ' + input.files[0].name + '\n(Se procesará al guardar)');
    }
}

function guardarConfigEvaluacion() {
    const titulo      = document.getElementById('eval-titulo').value.trim();
    const descripcion = document.getElementById('eval-descripcion').value.trim();
    const total       = parseInt(document.getElementById('eval-total').value);
    const tipos       = [...document.querySelectorAll('.tipo-check:checked')].map(c => c.value);

    if (!titulo) {
        alert('Escribe un título para la evaluación.');
        return;
    }
    if (!total || total < 1) {
        alert('Indica el total de preguntas.');
        return;
    }
    if (tipos.length === 0) {
        alert('Selecciona al menos un tipo de pregunta.');
        return;
    }

    // Pasamos los datos por query string a la página de evaluación
    const params = new URLSearchParams({
        tutoria_id:  1, // cuando tengas sesión: <?= $tutoria_id ?? 1 ?>
        titulo:      titulo,
        descripcion: descripcion,
        total:       total,
        tipos:       tipos.join(',')  // "VyF,seleccion"
    });

    window.location.href = '/evaluation/crear?' + params.toString();
}
</script>