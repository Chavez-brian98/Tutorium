<?php
$tutorias = $tutorias ?? [];
$alumnos = $alumnos ?? [];
$tutores = $tutores ?? [];
$materias = $materias ?? [];
$totalTutorias = count($tutorias);
$pendientes = count(array_filter($tutorias, fn($item) => strtoupper($item['estado'] ?? '') === 'PENDIENTE'));
$completadas = count(array_filter($tutorias, fn($item) => strtoupper($item['estado'] ?? '') === 'COMPLETADA'));
$canceladas = count(array_filter($tutorias, fn($item) => strtoupper($item['estado'] ?? '') === 'CANCELADA'));
?>

<?php include __DIR__ . '/../layout/sidebar.php'; ?>

<div class="md:ml-64 p-6 md:p-8" style="background-color: #f0ebe3;">
    <div class="mb-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-dorado">Panel de administración</p>
                <h1 class="text-4xl font-bold text-granate mt-3">Gestión de Tutorías</h1>
                <p class="mt-3 text-secundario max-w-2xl">Como administrador, asigna y administra las tutorías con el mismo estilo coherente del dashboard.</p>
            </div>
            <button type="button" onclick="openCreateModal()" class="btn btn-primary px-6 py-3" <?= (empty($alumnos) || empty($tutores) || empty($materias)) ? 'disabled' : '' ?>>Nueva tutoría</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <article class="rounded-3xl bg-card p-6 border border-white/70 shadow-sm">
            <p class="text-sm font-semibold text-secundario">Total de tutorías</p>
            <p class="mt-4 text-4xl font-bold text-granate"><?= $totalTutorias ?></p>
            <p class="mt-2 text-sm text-secundario">Tutorías registradas</p>
        </article>

        <article class="rounded-3xl bg-card p-6 border border-white/70 shadow-sm">
            <p class="text-sm font-semibold text-secundario">Pendientes</p>
            <p class="mt-4 text-4xl font-bold text-granate"><?= $pendientes ?></p>
            <p class="mt-2 text-sm text-secundario">Tutorías por iniciar</p>
        </article>

        <article class="rounded-3xl bg-card p-6 border border-white/70 shadow-sm">
            <p class="text-sm font-semibold text-secundario">Completadas</p>
            <p class="mt-4 text-4xl font-bold text-granate"><?= $completadas ?></p>
            <p class="mt-2 text-sm text-secundario">Tutorías finalizadas</p>
        </article>
    </div>

    <section class="rounded-[36px] bg-white shadow-lg border border-slate-200 p-6">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-granate">Catálogo de Tutorías</h2>
                <p class="mt-2 text-secundario">Revisa, edita o elimina las tutorías existentes.</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-granate/10 px-4 py-2 text-sm font-semibold text-granate">Total: <?= $totalTutorias ?></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] border-separate border-spacing-0 text-sm">
                <thead class="bg-granate text-white rounded-3xl">
                    <tr>
                        <th class="px-5 py-4 text-left">Alumno</th>
                        <th class="px-5 py-4 text-left">Tutor</th>
                        <th class="px-5 py-4 text-left">Materia</th>
                        <th class="px-5 py-4 text-left">Fecha</th>
                        <th class="px-5 py-4 text-left">Hora inicio</th>
                        <th class="px-5 py-4 text-left">Hora fin</th>
                        <th class="px-5 py-4 text-left">Sesiones</th>
                        <th class="px-5 py-4 text-left">Estado</th>
                        <th class="px-5 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tutorias)): ?>
                        <?php foreach ($tutorias as $tutoria): ?>
                            <?php $estadoActivo = strtoupper($tutoria['estado'] ?? '') === 'COMPLETADA'; ?>
                            <tr class="border-b border-slate-200 last:border-none hover:bg-slate-50">
                                <td class="px-5 py-4 text-slate-800"><?= htmlspecialchars($tutoria['alumno_nombre']) ?></td>
                                <td class="px-5 py-4 text-slate-800"><?= htmlspecialchars($tutoria['tutor_nombre']) ?></td>
                                <td class="px-5 py-4 text-slate-600"><?= htmlspecialchars($tutoria['materia_nombre']) ?></td>
                                <td class="px-5 py-4 text-slate-600"><?= htmlspecialchars($tutoria['fecha']) ?></td>
                                <td class="px-5 py-4 text-slate-600"><?= htmlspecialchars($tutoria['hora_inicio']) ?></td>
                                <td class="px-5 py-4 text-slate-600"><?= htmlspecialchars($tutoria['hora_fin']) ?></td>
                                <td class="px-5 py-4 text-slate-800"><?= (int) $tutoria['num_sesiones'] ?></td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold <?= $estadoActivo ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-700' ?>">
                                        <?= htmlspecialchars(ucfirst(strtolower($tutoria['estado']))) ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <button type="button"
                                            class="btn btn-outline mr-2 js-edit-tutoria-btn"
                                            data-id="<?= (int) $tutoria['id'] ?>"
                                            data-alumno-id="<?= (int) $tutoria['alumno_id'] ?>"
                                            data-tutor-id="<?= (int) $tutoria['tutor_id'] ?>"
                                            data-materia-id="<?= (int) $tutoria['materia_id'] ?>"
                                            data-fecha="<?= htmlspecialchars($tutoria['fecha'], ENT_QUOTES) ?>"
                                            data-hora-inicio="<?= htmlspecialchars($tutoria['hora_inicio'], ENT_QUOTES) ?>"
                                            data-hora-fin="<?= htmlspecialchars($tutoria['hora_fin'], ENT_QUOTES) ?>"
                                            data-num-sesiones="<?= (int) $tutoria['num_sesiones'] ?>"
                                            data-estado="<?= htmlspecialchars($tutoria['estado'], ENT_QUOTES) ?>">
                                        Editar
                                    </button>
                                    <button type="button"
                                            onclick="confirmDeleteTutoria(<?= (int) $tutoria['id'] ?>)"
                                            class="btn btn-danger">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="px-5 py-6 text-center text-secundario">No hay tutorías registradas todavía.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<form id="deleteTutoriaForm" action="/tutorias/eliminar" method="post" hidden>
    <input type="hidden" name="id" id="deleteTutoriaId">
</form>

<div id="createTutoriaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-2xl rounded-[32px] bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-granate">Nueva tutoría</h3>
                <p class="text-sm text-secundario">Asigna una nueva tutoría especificando alumno, tutor, materia y detalles de la sesión.</p>
            </div>
            <button type="button" onclick="closeCreateModal()" class="text-slate-500 hover:text-granate">Cerrar</button>
        </div>

        <form action="/tutorias/guardar" method="post" class="space-y-5">
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Alumno</span>
                    <select name="alumno_id" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10">
                        <option value="">Selecciona un alumno</option>
                        <?php foreach ($alumnos as $alumno): ?>
                            <option value="<?= (int) $alumno['id'] ?>"><?= htmlspecialchars($alumno['nombres'] . ' ' . $alumno['apellidos']) ?> - <?= htmlspecialchars($alumno['email']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Tutor</span>
                    <select name="tutor_id" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10">
                        <option value="">Selecciona un tutor</option>
                        <?php foreach ($tutores as $tutor): ?>
                            <option value="<?= (int) $tutor['id'] ?>"><?= htmlspecialchars($tutor['nombres'] . ' ' . $tutor['apellidos']) ?> - <?= htmlspecialchars($tutor['email']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Materia</span>
                    <select name="materia_id" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10">
                        <option value="">Selecciona una materia</option>
                        <?php foreach ($materias as $materia): ?>
                            <option value="<?= (int) $materia['id'] ?>"><?= htmlspecialchars($materia['nombre']) ?> (<?= htmlspecialchars($materia['codigo']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Fecha</span>
                    <input name="fecha" type="date" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
            </div>
            <div class="grid gap-4 md:grid-cols-3">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Hora inicio</span>
                    <input name="hora_inicio" type="time" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Hora fin</span>
                    <input name="hora_fin" type="time" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Sesiones</span>
                    <input name="num_sesiones" type="number" min="1" value="1" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
            </div>
            <label class="block">
                <span class="text-sm font-semibold text-slate-700">Estado</span>
                <select name="estado" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10">
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="COMPLETADA">Completada</option>
                    <option value="CANCELADA">Cancelada</option>
                </select>
            </label>
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeCreateModal()" class="btn btn-outline px-5 py-3">Cancelar</button>
                <button type="submit" class="btn btn-primary px-5 py-3">Guardar tutoría</button>
            </div>
        </form>
    </div>
</div>

<div id="editTutoriaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-2xl rounded-[32px] bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-granate">Editar tutoría</h3>
                <p class="text-sm text-secundario">Actualiza los datos de la tutoría seleccionada.</p>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-slate-500 hover:text-granate">Cerrar</button>
        </div>

        <form id="editTutoriaForm" action="/tutorias/actualizar" method="post" class="space-y-5">
            <input type="hidden" name="id" id="editTutoriaId" />
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Alumno</span>
                    <select id="editAlumnoId" name="alumno_id" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10">
                        <option value="">Selecciona un alumno</option>
                        <?php foreach ($alumnos as $alumno): ?>
                            <option value="<?= (int) $alumno['id'] ?>"><?= htmlspecialchars($alumno['nombres'] . ' ' . $alumno['apellidos']) ?> - <?= htmlspecialchars($alumno['email']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Tutor</span>
                    <select id="editTutorId" name="tutor_id" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10">
                        <option value="">Selecciona un tutor</option>
                        <?php foreach ($tutores as $tutor): ?>
                            <option value="<?= (int) $tutor['id'] ?>"><?= htmlspecialchars($tutor['nombres'] . ' ' . $tutor['apellidos']) ?> - <?= htmlspecialchars($tutor['email']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Materia</span>
                    <select id="editMateriaId" name="materia_id" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10">
                        <option value="">Selecciona una materia</option>
                        <?php foreach ($materias as $materia): ?>
                            <option value="<?= (int) $materia['id'] ?>"><?= htmlspecialchars($materia['nombre']) ?> (<?= htmlspecialchars($materia['codigo']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Fecha</span>
                    <input id="editFecha" name="fecha" type="date" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
            </div>
            <div class="grid gap-4 md:grid-cols-3">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Hora inicio</span>
                    <input id="editHoraInicio" name="hora_inicio" type="time" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Hora fin</span>
                    <input id="editHoraFin" name="hora_fin" type="time" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Sesiones</span>
                    <input id="editNumSesiones" name="num_sesiones" type="number" min="1" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
            </div>
            <label class="block">
                <span class="text-sm font-semibold text-slate-700">Estado</span>
                <select id="editEstado" name="estado" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10">
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="COMPLETADA">Completada</option>
                    <option value="CANCELADA">Cancelada</option>
                </select>
            </label>
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeEditModal()" class="btn btn-outline px-5 py-3">Cancelar</button>
                <button type="submit" class="btn btn-primary px-5 py-3">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createTutoriaModal').classList.remove('hidden');
        document.getElementById('createTutoriaModal').classList.add('flex');
    }

    function closeCreateModal() {
        document.getElementById('createTutoriaModal').classList.add('hidden');
    }

    function openEditModal(id, alumnoId, tutorId, materiaId, fecha, horaInicio, horaFin, numSesiones, estado) {
        document.getElementById('editTutoriaId').value = id;
        document.getElementById('editAlumnoId').value = alumnoId;
        document.getElementById('editTutorId').value = tutorId;
        document.getElementById('editMateriaId').value = materiaId;
        document.getElementById('editFecha').value = fecha;
        document.getElementById('editHoraInicio').value = horaInicio;
        document.getElementById('editHoraFin').value = horaFin;
        document.getElementById('editNumSesiones').value = numSesiones;
        document.getElementById('editEstado').value = estado;

        document.getElementById('editTutoriaModal').classList.remove('hidden');
        document.getElementById('editTutoriaModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editTutoriaModal').classList.add('hidden');
    }

    document.querySelectorAll('.js-edit-tutoria-btn').forEach((button) => {
        button.addEventListener('click', function () {
            openEditModal(
                this.dataset.id,
                this.dataset.alumnoId,
                this.dataset.tutorId,
                this.dataset.materiaId,
                this.dataset.fecha,
                this.dataset.horaInicio,
                this.dataset.horaFin,
                this.dataset.numSesiones,
                this.dataset.estado
            );
        });
    });

    function confirmDeleteTutoria(id) {
        if (!window.appAlerts) {
            if (confirm('¿Deseas eliminar esta tutoría?')) {
                document.getElementById('deleteTutoriaId').value = id;
                document.getElementById('deleteTutoriaForm').submit();
            }
            return;
        }

        window.appAlerts.confirm({
            title: 'Eliminar tutoría',
            text: '¿Estás seguro de eliminar esta tutoría? Esta acción no se puede deshacer.',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc2626',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteTutoriaId').value = id;
                document.getElementById('deleteTutoriaForm').submit();
            }
        });
    }
</script>
