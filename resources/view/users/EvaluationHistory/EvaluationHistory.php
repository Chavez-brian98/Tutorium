<div class="min-h-screen">

    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <div class="p-2 md:ml-64 p-6 md:p-8">

        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold text-gray-800">Historial de Evaluaciones</h1>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-2xl shadow-sm p-4 mb-4 flex flex-col sm:flex-row gap-3">

            <!-- Buscador -->
            <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <i class="bi bi-search"></i>
            </span>
                <input type="text" id="buscador"
                       placeholder="Buscar por título..."
                       oninput="filtrarTabla()"
                       class="w-full pl-9 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-[#9e2820] focus:ring-2 focus:ring-[#9e2820]/10 transition-all" />
            </div>

            <!-- Filtro por materia -->
            <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <i class="bi bi-book"></i>
            </span>
                <select id="filtro-materia"
                        onchange="filtrarTabla()"
                        class="pl-9 pr-8 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-[#9e2820] focus:ring-2 focus:ring-[#9e2820]/10 transition-all appearance-none cursor-pointer">
                    <option value="">Todas las materias</option>
                    <?php foreach ($materias as $m): ?>
                        <option value="<?= htmlspecialchars($m) ?>"><?= htmlspecialchars($m) ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                <i class="bi bi-chevron-down text-xs"></i>
            </span>
            </div>

        </div>

        <!-- Tabla -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-sm" id="tabla-evaluaciones">
                <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Título</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Estudiante</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Asignatura</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Fecha</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Nota</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                </tr>
                </thead>
                <tbody id="tbody-evaluaciones">
                <?php $evaluaciones = $evaluaciones ?? []; ?>

                <?php foreach ($evaluaciones as $ev): ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors fila-evaluacion"
                        data-id="<?= $ev['id'] ?>"
                        data-titulo="<?= strtolower($ev['titulo']) ?>"
                        data-materia="<?= $ev['materia'] ?>">

                        <td class="px-5 py-3.5 font-medium text-gray-700">
                            <?= htmlspecialchars($ev['titulo']) ?>
                        </td>
                        <td class="px-5 py-3.5 text-gray-600">
                            <?= htmlspecialchars($ev['estudiante']) ?>
                        </td>
                        <td class="px-5 py-3.5 text-gray-600">
                            <?= htmlspecialchars($ev['materia']) ?>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500">
                            <?= date('d/m/Y', strtotime($ev['fecha'])) ?>
                        </td>
                        <td class="px-5 py-3.5">
                            <?php if ($ev['nota'] !== null): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                <?= $ev['nota'] >= 7 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <?= number_format($ev['nota'], 1) ?>
                            </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                Pendiente
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-center gap-2">

                                <?php if ($rol === 'tutor' || $rol === 'admin'): ?>
                                    <!-- Ver -->
                                    <button title="Ver evaluación"
                                            onclick="verDetalles(
                                                '<?= addslashes($ev['titulo']) ?>',
                                                '<?= addslashes($ev['estudiante']) ?>',
                                                '<?= addslashes($ev['materia']) ?>',
                                                '<?= $ev['fecha'] ?>',
                                            <?= $ev['nota'] !== null ? $ev['nota'] : 'null' ?>,
                                                '<?= addslashes($ev['creado'] ?? date('Y-m-d H:i:s')) ?>',
                                                '<?= addslashes($ev['actualizado'] ?? date('Y-m-d H:i:s')) ?>',
                                                '<?= addslashes($ev['descripcion'] ?? '') ?>'
                                                )"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-blue-500 hover:bg-blue-50 transition-colors">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <!-- Calificar -->
                                    <button title="Calificar"
                                            onclick="abrirModalCalificar(
                                                '<?= addslashes($ev['titulo']) ?>',
                                                '<?= addslashes($ev['estudiante']) ?>',
                                                '<?= addslashes($ev['materia']) ?>',
                                                '<?= $ev['fecha'] ?>',
                                            <?= $ev['id'] ?>,
                                            <?= $ev['nota'] !== null ? $ev['nota'] : 'null' ?>,
                                                '<?= addslashes($ev['descripcion'] ?? '') ?>'
                                                )"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-[#9e2820] hover:bg-[#9e2820]/10 transition-colors">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                <?php endif; ?>

                                <!-- Descargar PDF -->
                                <a href="/evaluacion/pdf/<?= $ev['id'] ?>"
                                   title="Descargar PDF"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>

                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>

            <!-- Sin resultados -->
            <div id="sin-resultados" class="hidden text-center py-12 text-gray-400">
                <i class="bi bi-search text-3xl mb-2 block"></i>
                <p class="text-sm">No se encontraron evaluaciones.</p>
            </div>

        </div>

        <!-- Modal Ver Detalles -->
        <div id="modal-ver" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4" onclick="cerrarModal(event)">
            <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl" onclick="event.stopPropagation()">

                <!-- Header -->
                <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                            <i class="bi bi-file-text text-blue-500 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Detalles de la Evaluación</h2>
                    </div>
                    <button onclick="cerrarModal(event)" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center transition-colors">
                        <i class="bi bi-x-lg text-gray-400 text-sm"></i>
                    </button>
                </div>

                <!-- Contenido -->
                <div class="p-6">
                    <div class="space-y-5">
                        <!-- Título -->
                        <div class="border-b border-gray-100 pb-3">
                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Título</label>
                            <p class="text-gray-800 font-medium mt-1" id="modal-titulo">-</p>
                        </div>

                        <!-- Estudiante y Materia -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-gray-100 pb-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Estudiante</label>
                                <p class="text-gray-800 mt-1" id="modal-estudiante">-</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Asignatura</label>
                                <p class="text-gray-800 mt-1" id="modal-materia">-</p>
                            </div>
                        </div>

                        <!-- Fecha y Nota -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-gray-100 pb-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Fecha de Evaluación</label>
                                <p class="text-gray-800 mt-1" id="modal-fecha">-</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Calificación</label>
                                <div id="modal-nota" class="mt-1"></div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="border-b border-gray-100 pb-3">
                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Descripción / Comentarios</label>
                            <p class="text-gray-600 mt-1 text-sm leading-relaxed" id="modal-descripcion">Sin descripción adicional.</p>
                        </div>

                        <!-- Metadatos -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <span class="text-gray-400">Creado:</span>
                                    <span class="text-gray-600 block font-mono text-xs mt-0.5" id="modal-creado">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-400">Actualizado:</span>
                                    <span class="text-gray-600 block font-mono text-xs mt-0.5" id="modal-actualizado">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="sticky bottom-0 bg-gray-50 rounded-b-2xl px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                    <button onclick="cerrarModal(event)" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        Cerrar
                    </button>
                    <button id="btn-calificar-from-modal"
                            class="px-4 py-2 text-sm font-medium bg-[#9e2820] text-white rounded-lg hover:bg-[#7a2019] transition-colors flex items-center gap-2">
                        <i class="bi bi-pencil-square text-sm"></i>
                        Calificar Evaluación
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Calificar Evaluación -->
        <div id="modal-calificar" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4" onclick="cerrarModalCalificar(event)">
            <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl" onclick="event.stopPropagation()">

                <!-- Header -->
                <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#9e2820]/10 flex items-center justify-center">
                            <i class="bi bi-pencil-square text-[#9e2820] text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Calificar Evaluación</h2>
                    </div>
                    <button onclick="cerrarModalCalificar(event)" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center transition-colors">
                        <i class="bi bi-x-lg text-gray-400 text-sm"></i>
                    </button>
                </div>

                <!-- Formulario -->
                <form id="form-calificar" onsubmit="return guardarCalificacion(event)">
                    <div class="p-6 space-y-5">

                        <!-- Información fija -->
                        <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                            <div class="flex items-center gap-2 text-sm">
                                <i class="bi bi-file-text text-gray-400"></i>
                                <span class="text-gray-500">Evaluación:</span>
                                <span class="font-medium text-gray-700" id="calif-titulo">-</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <i class="bi bi-person text-gray-400"></i>
                                <span class="text-gray-500">Estudiante:</span>
                                <span class="font-medium text-gray-700" id="calif-estudiante">-</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <i class="bi bi-book text-gray-400"></i>
                                <span class="text-gray-500">Asignatura:</span>
                                <span class="font-medium text-gray-700" id="calif-materia">-</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <i class="bi bi-calendar text-gray-400"></i>
                                <span class="text-gray-500">Fecha:</span>
                                <span class="font-medium text-gray-700" id="calif-fecha">-</span>
                            </div>
                        </div>

                        <!-- Nota -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Calificación <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="bi bi-star"></i>
                            </span>
                                <input type="number"
                                       id="calif-nota"
                                       name="nota"
                                       step="0.1"
                                       min="0"
                                       max="10"
                                       required
                                       placeholder="0.0 - 10.0"
                                       class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-[#9e2820] focus:ring-2 focus:ring-[#9e2820]/10 transition-all" />
                            </div>
                            <p class="text-xs text-gray-400">Valor entre 0 y 10 (puede usar decimales como 7.5)</p>
                        </div>

                        <!-- Comentarios -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Comentarios / Retroalimentación
                                <span class="text-gray-400 text-xs font-normal">(opcional)</span>
                            </label>
                            <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-400">
                                <i class="bi bi-chat"></i>
                            </span>
                                <textarea id="calif-comentarios"
                                          name="comentarios"
                                          rows="4"
                                          placeholder="Escribe aquí tus comentarios sobre la evaluación..."
                                          class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-[#9e2820] focus:ring-2 focus:ring-[#9e2820]/10 transition-all resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Barra de progreso -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Insuficiente</span>
                                <span>Aceptable</span>
                                <span>Excelente</span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div id="nota-progress" class="h-full bg-gradient-to-r from-red-500 via-yellow-500 to-green-500 transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="sticky bottom-0 bg-gray-50 rounded-b-2xl px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                        <button type="button" onclick="cerrarModalCalificar(event)" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium bg-[#9e2820] text-white rounded-lg hover:bg-[#7a2019] transition-colors flex items-center gap-2">
                            <i class="bi bi-check-lg"></i>
                            Guardar Calificación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        .animate-fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }
    </style>

    <script>
        let currentCalificacionData = null;
        let currentEvaluationData = null;

        function abrirModalCalificar(titulo, estudiante, materia, fecha, evaluacionId, notaActual = null, comentariosActuales = null) {
            currentCalificacionData = {
                id: evaluacionId,
                titulo: titulo,
                estudiante: estudiante,
                materia: materia,
                fecha: fecha,
                notaActual: notaActual,
                comentariosActuales: comentariosActuales
            };

            document.getElementById('calif-titulo').textContent = titulo;
            document.getElementById('calif-estudiante').textContent = estudiante;
            document.getElementById('calif-materia').textContent = materia;

            const fechaObj = new Date(fecha);
            const fechaFormateada = fechaObj.toLocaleDateString('es-ES', {
                year: 'numeric', month: 'long', day: 'numeric'
            });
            document.getElementById('calif-fecha').textContent = fechaFormateada;

            const notaInput = document.getElementById('calif-nota');
            if (notaActual !== null && notaActual !== undefined) {
                notaInput.value = notaActual;
                actualizarBarraProgreso(notaActual);
            } else {
                notaInput.value = '';
                actualizarBarraProgreso(0);
            }

            const comentariosTextarea = document.getElementById('calif-comentarios');
            comentariosTextarea.value = comentariosActuales || '';

            const modal = document.getElementById('modal-calificar');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalCalificar(event) {
            const modal = document.getElementById('modal-calificar');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
            document.getElementById('form-calificar').reset();
            actualizarBarraProgreso(0);
        }

        function actualizarBarraProgreso(nota) {
            const porcentaje = (nota / 10) * 100;
            const progressBar = document.getElementById('nota-progress');
            if (progressBar) progressBar.style.width = `${porcentaje}%`;
        }

        function verDetalles(titulo, estudiante, materia, fecha, nota, creado = null, actualizado = null, descripcion = null) {
            currentEvaluationData = { titulo, estudiante, materia, fecha, nota, creado, actualizado, descripcion };

            document.getElementById('modal-titulo').textContent = titulo;
            document.getElementById('modal-estudiante').textContent = estudiante;
            document.getElementById('modal-materia').textContent = materia;

            const fechaObj = new Date(fecha);
            const fechaFormateada = fechaObj.toLocaleDateString('es-ES', {
                year: 'numeric', month: 'long', day: 'numeric'
            });
            document.getElementById('modal-fecha').textContent = fechaFormateada;

            const notaContainer = document.getElementById('modal-nota');
            if (nota !== null && nota !== undefined && nota !== '') {
                const notaNum = parseFloat(nota);
                const badgeClass = notaNum >= 7 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                notaContainer.innerHTML = `<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold ${badgeClass}">${notaNum.toFixed(1)} / 10</span>`;
            } else {
                notaContainer.innerHTML = `<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700"><i class="bi bi-clock-history mr-1 text-xs"></i>Pendiente de calificar</span>`;
            }

            document.getElementById('modal-descripcion').textContent = descripcion || 'Sin descripción adicional.';
            document.getElementById('modal-creado').textContent = creado || 'No disponible';
            document.getElementById('modal-actualizado').textContent = actualizado || 'No disponible';

            const modal = document.getElementById('modal-ver');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModal(event) {
            const modal = document.getElementById('modal-ver');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        async function guardarCalificacion(event) {
            event.preventDefault();

            const nota = parseFloat(document.getElementById('calif-nota').value);
            const comentarios = document.getElementById('calif-comentarios').value;

            if (isNaN(nota) || nota < 0 || nota > 10) {
                mostrarAlerta('La calificación debe ser un número entre 0 y 10', 'error');
                return false;
            }

            const btnSubmit = event.submitter;
            const textoOriginal = btnSubmit.innerHTML;
            btnSubmit.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Guardando...';
            btnSubmit.disabled = true;

            try {
                const response = await fetch('/api/calificar-evaluacion', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id: currentCalificacionData.id,
                        nota: nota,
                        comentarios: comentarios
                    })
                });

                const result = await response.json();

                if (result.success) {
                    mostrarAlerta('Calificación guardada exitosamente', 'success');
                    cerrarModalCalificar();
                    actualizarNotaEnTabla(currentCalificacionData.id, nota);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mostrarAlerta(result.message || 'Error al guardar la calificación', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('Error de conexión al guardar la calificación', 'error');
            } finally {
                btnSubmit.innerHTML = textoOriginal;
                btnSubmit.disabled = false;
            }

            return false;
        }

        function actualizarNotaEnTabla(evaluacionId, nuevaNota) {
            const fila = document.querySelector(`.fila-evaluacion[data-id="${evaluacionId}"]`);
            if (fila) {
                const celdaNota = fila.querySelector('td:nth-child(5)');
                if (celdaNota) {
                    const notaFormateada = nuevaNota.toFixed(1);
                    const badgeClass = nuevaNota >= 7 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                    celdaNota.innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold ${badgeClass}">${notaFormateada}</span>`;
                }
            }
        }

        function mostrarAlerta(mensaje, tipo = 'info') {
            const alerta = document.createElement('div');
            alerta.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg flex items-center gap-2 animate-slide-in ${
                tipo === 'success' ? 'bg-green-500 text-white' :
                    tipo === 'error' ? 'bg-red-500 text-white' : 'bg-blue-500 text-white'
            }`;

            const icono = tipo === 'success' ? 'bi-check-circle' : tipo === 'error' ? 'bi-x-circle' : 'bi-info-circle';
            alerta.innerHTML = `<i class="bi ${icono} text-xl"></i><span class="text-sm">${mensaje}</span>`;
            document.body.appendChild(alerta);

            setTimeout(() => {
                alerta.classList.add('animate-fade-out');
                setTimeout(() => alerta.remove(), 300);
            }, 3000);
        }

        function filtrarTabla() {
            const busqueda = document.getElementById('buscador').value.toLowerCase();
            const materia = document.getElementById('filtro-materia').value;
            const filas = document.querySelectorAll('.fila-evaluacion');
            let visibles = 0;

            filas.forEach(fila => {
                const titulo = fila.dataset.titulo;
                const materiaFila = fila.dataset.materia;
                const coincideTitulo = titulo.includes(busqueda);
                const coincideMateria = materia === '' || materiaFila === materia;

                if (coincideTitulo && coincideMateria) {
                    fila.classList.remove('hidden');
                    visibles++;
                } else {
                    fila.classList.add('hidden');
                }
            });

            document.getElementById('sin-resultados').classList.toggle('hidden', visibles > 0);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const notaInput = document.getElementById('calif-nota');
            if (notaInput) {
                notaInput.addEventListener('input', function(e) {
                    let valor = parseFloat(e.target.value);
                    if (isNaN(valor)) valor = 0;
                    if (valor > 10) valor = 10;
                    if (valor < 0) valor = 0;
                    actualizarBarraProgreso(valor);
                });
            }

            const btnCalificar = document.getElementById('btn-calificar-from-modal');
            if (btnCalificar) {
                btnCalificar.addEventListener('click', function() {
                    if (currentEvaluationData) {
                        cerrarModal();
                        abrirModalCalificar(
                            currentEvaluationData.titulo,
                            currentEvaluationData.estudiante,
                            currentEvaluationData.materia,
                            currentEvaluationData.fecha,
                            currentEvaluationData.id || null,
                            currentEvaluationData.nota,
                            currentEvaluationData.descripcion
                        );
                    }
                });
            }
        });
    </script>