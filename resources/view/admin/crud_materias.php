<?php
$materias = $materias ?? [];
$totalMaterias = count($materias);
$materiasActivas = count(array_filter($materias, fn($item) => strtoupper($item['estado'] ?? '') === 'ACTIVO'));
$materiasInactivas = $totalMaterias - $materiasActivas;
?>

<?php include __DIR__ . '/../layout/sidebar.php'; ?>

<div class="md:ml-64 p-6 md:p-8" style="background-color: #f0ebe3;">
    <div class="mb-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-dorado">Panel de administración</p>
                <h1 class="text-4xl font-bold text-granate mt-3">Gestión de Materias</h1>
            </div>
            <button type="button" onclick="openCreateModal()" class="btn btn-primary px-6 py-3">Nueva materia</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <article class="rounded-2xl p-6 transition-all hover:shadow-lg hover:-translate-y-1 bg-white"
                 style="border: 1px solid rgba(123, 28, 28, 0.1);">
            <p class="text-sm font-semibold" style="color: #5a5a5a;">Total de materias</p>
            <p class="mt-4 text-4xl font-bold" style="color: #5c1313;"><?= $totalMaterias ?></p>
            <p class="mt-2 text-sm" style="color: #5a5a5a;">Materias registradas en el catálogo</p>
        </article>

        <article class="rounded-2xl p-6 transition-all hover:shadow-lg hover:-translate-y-1 bg-white"
                 style="border: 1px solid rgba(123, 28, 28, 0.1);">
            <p class="text-sm font-semibold" style="color: #5a5a5a;">Activas</p>
            <p class="mt-4 text-4xl font-bold" style="color: #5c1313;"><?= $materiasActivas ?></p>
            <p class="mt-2 text-sm" style="color: #5a5a5a;">Materias con estado ACTIVO</p>
        </article>

        <article class="rounded-2xl p-6 transition-all hover:shadow-lg hover:-translate-y-1 bg-white"
                 style="border: 1px solid rgba(123, 28, 28, 0.1);">
            <p class="text-sm font-semibold" style="color: #5a5a5a;">Inactivas</p>
            <p class="mt-4 text-4xl font-bold" style="color: #5c1313;"><?= $materiasInactivas ?></p>
            <p class="mt-2 text-sm" style="color: #5a5a5a;">Materias con estado INACTIVO</p>
        </article>
    </div>

    <section class="rounded-[36px] bg-white shadow-lg border border-slate-200 p-6">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-granate">Catálogo de Materias</h2>
                <p class="mt-2 text-secundario">Revisa, edita o elimina las materias existentes.</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-granate/10 px-4 py-2 text-sm font-semibold text-granate">Total: <?= $totalMaterias ?></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] border-separate border-spacing-0 text-sm">
                <thead class="bg-granate text-white rounded-3xl">
                    <tr>
                        <th class="px-5 py-4 text-left">Nombre</th>
                        <th class="px-5 py-4 text-left">Código</th>
                        <th class="px-5 py-4 text-left">Descripción</th>
                        <th class="px-5 py-4 text-left">Estado</th>
                        <th class="px-5 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($materias)): ?>
                        <?php foreach ($materias as $materia): ?>
                            <tr class="border-b border-slate-200 last:border-none hover:bg-slate-50">
                                <td class="px-5 py-4 text-slate-800"><?= htmlspecialchars($materia['nombre']) ?></td>
                                <td class="px-5 py-4 text-slate-600"><?= htmlspecialchars($materia['codigo']) ?></td>
                                <td class="px-5 py-4 text-slate-600"><?= htmlspecialchars($materia['descripcion']) ?></td>
                                <td class="px-5 py-4">
                                    <?php $activo = strtoupper($materia['estado'] ?? '') === 'ACTIVO'; ?>
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold <?= $activo ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-700' ?>">
                                        <?= $activo ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <button type="button"
                                            class="btn btn-outline mr-2 js-edit-materia-btn"
                                            data-id="<?= (int) $materia['id'] ?>"
                                            data-nombre="<?= htmlspecialchars($materia['nombre'], ENT_QUOTES) ?>"
                                            data-codigo="<?= htmlspecialchars($materia['codigo'], ENT_QUOTES) ?>"
                                            data-descripcion="<?= htmlspecialchars($materia['descripcion'], ENT_QUOTES) ?>"
                                            data-estado="<?= htmlspecialchars($materia['estado'], ENT_QUOTES) ?>">
                                        Editar
                                    </button>
                                    <button type="button"
                                            onclick="confirmDelete(<?= (int) $materia['id'] ?>)"
                                            class="btn btn-danger">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-5 py-6 text-center text-secundario">No hay materias registradas todavía.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<form id="deleteMateriaForm" action="/materias/eliminar" method="post" hidden>
    <input type="hidden" name="id" id="deleteMateriaId">
</form>

<div id="createModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-2xl rounded-[32px] bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-granate">Nueva materia</h3>
                <p class="text-sm text-secundario">Registra una nueva materia para el catálogo.</p>
            </div>
            <button type="button" onclick="closeCreateModal()" class="text-slate-500 hover:text-granate">Cerrar</button>
        </div>

        <form action="/materias/guardar" method="post" class="space-y-5">
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Nombre</span>
                    <input name="nombre" type="text" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" placeholder="Ej: Matemáticas" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Código</span>
                    <input name="codigo" type="text" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" placeholder="Ej: MAT-101" />
                </label>
            </div>
            <label class="block">
                <span class="text-sm font-semibold text-slate-700">Descripción</span>
                <textarea name="descripcion" rows="4" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" placeholder="Descripción de la materia..."></textarea>
            </label>
            <label class="block">
                <span class="text-sm font-semibold text-slate-700">Estado</span>
                <select name="estado" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10">
                    <option value="ACTIVO">Activo</option>
                    <option value="INACTIVO">Inactivo</option>
                </select>
            </label>
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeCreateModal()" class="btn btn-outline px-5 py-3">Cancelar</button>
                <button type="submit" class="btn btn-primary px-5 py-3">Guardar materia</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-2xl rounded-[32px] bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-granate">Editar materia</h3>
                <p class="text-sm text-secundario">Actualiza los datos de la materia seleccionada.</p>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-slate-500 hover:text-granate">Cerrar</button>
        </div>

        <form id="editMateriaForm" action="/materias/actualizar" method="post" class="space-y-5">
            <input type="hidden" name="id" id="editMateriaId" />
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Nombre</span>
                    <input id="editNombre" name="nombre" type="text" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Código</span>
                    <input id="editCodigo" name="codigo" type="text" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
            </div>
            <label class="block">
                <span class="text-sm font-semibold text-slate-700">Descripción</span>
                <textarea id="editDescripcion" name="descripcion" rows="4" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10"></textarea>
            </label>
            <label class="block">
                <span class="text-sm font-semibold text-slate-700">Estado</span>
                <select id="editEstado" name="estado" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10">
                    <option value="ACTIVO">Activo</option>
                    <option value="INACTIVO">Inactivo</option>
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
        document.getElementById('createModal').classList.remove('hidden');
        document.getElementById('createModal').classList.add('flex');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(id, nombre, codigo, descripcion, estado) {
        document.getElementById('editMateriaId').value = id;
        document.getElementById('editNombre').value = nombre;
        document.getElementById('editCodigo').value = codigo;
        document.getElementById('editDescripcion').value = descripcion;
        document.getElementById('editEstado').value = estado;

        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    document.querySelectorAll('.js-edit-materia-btn').forEach((button) => {
        button.addEventListener('click', function () {
            openEditModal(
                this.dataset.id,
                this.dataset.nombre,
                this.dataset.codigo,
                this.dataset.descripcion,
                this.dataset.estado
            );
        });
    });

    function confirmDelete(id) {
        if (!window.appAlerts) {
            if (confirm('¿Deseas eliminar esta materia?')) {
                document.getElementById('deleteMateriaId').value = id;
                document.getElementById('deleteMateriaForm').submit();
            }
            return;
        }

        window.appAlerts.confirm({
            title: 'Eliminar materia',
            text: '¿Estás seguro de eliminar esta materia? Esta acción no se puede deshacer.',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc2626',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteMateriaId').value = id;
                document.getElementById('deleteMateriaForm').submit();
            }
        });
    }
</script>
