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
        <article class="rounded-3xl bg-card p-6 border border-white/70 shadow-sm">
            <p class="text-sm font-semibold text-secundario">Total de materias</p>
            <p class="mt-4 text-4xl font-bold text-granate"><?= $totalMaterias ?></p>
            <p class="mt-2 text-sm text-secundario">Materias registradas en el catálogo</p>
        </article>

        <article class="rounded-3xl bg-card p-6 border border-white/70 shadow-sm">
            <p class="text-sm font-semibold text-secundario">Activas</p>
            <p class="mt-4 text-4xl font-bold text-granate"><?= $materiasActivas ?></p>
            <p class="mt-2 text-sm text-secundario">Materias con estado ACTIVO</p>
        </article>

        <article class="rounded-3xl bg-card p-6 border border-white/70 shadow-sm">
            <p class="text-sm font-semibold text-secundario">Inactivas</p>
            <p class="mt-4 text-4xl font-bold text-granate"><?= $materiasInactivas ?></p>
            <p class="mt-2 text-sm text-secundario">Materias con estado INACTIVO</p>
        </article>
    </div>

    <section class="rounded-[36px] bg-white shadow-lg border border-slate-200 p-6" style="background:white; border-radius:16px; border-top:8px solid var(--granate-600); padding:24px; box-shadow:0 1px 4px rgba(0,0,0,0.08);">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-granate">Catálogo de Materias</h2>
                <p class="mt-2 text-secundario">Revisa, edita o elimina las materias existentes.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative w-full max-w-xs">
                    <input id="searchInput" type="search" placeholder="Buscar materia..." class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                    <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">🔍</span>
                </div>
                <span class="inline-flex items-center rounded-full bg-granate/10 px-4 py-2 text-sm font-semibold text-granate">Total: <?= $totalMaterias ?></span>
            </div>
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
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <button type="button"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:border-granate hover:bg-granate hover:text-white js-edit-materia-btn"
                                                title="Editar"
                                                data-id="<?= (int) $materia['id'] ?>"
                                                data-nombre="<?= htmlspecialchars($materia['nombre'], ENT_QUOTES) ?>"
                                                data-codigo="<?= htmlspecialchars($materia['codigo'], ENT_QUOTES) ?>"
                                                data-descripcion="<?= htmlspecialchars($materia['descripcion'], ENT_QUOTES) ?>"
                                                data-estado="<?= htmlspecialchars($materia['estado'], ENT_QUOTES) ?>">
                                            ✎
                                        </button>
                                        <button type="button"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:border-red-400 hover:bg-red-500 hover:text-white"
                                                title="Eliminar"
                                                onclick="confirmDelete(<?= (int) $materia['id'] ?>)">
                                            🗑
                                        </button>
                                        <button type="button"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:border-granate hover:bg-granate hover:text-white"
                                                title="Ver detalle"
                                                onclick="openDetailModal('<?= htmlspecialchars($materia['nombre'], ENT_QUOTES) ?>', '<?= htmlspecialchars($materia['codigo'], ENT_QUOTES) ?>', '<?= htmlspecialchars($materia['descripcion'], ENT_QUOTES) ?>', '<?= htmlspecialchars($materia['estado'], ENT_QUOTES) ?>')">
                                            👁
                                        </button>
                                    </div>
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

<div id="detailMateriaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-xl rounded-[32px] bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-granate">Detalle de materia</h3>
                <p class="text-sm text-secundario">Revisa la información completa de la materia seleccionada.</p>
            </div>
            <button type="button" onclick="closeDetailModal()" class="text-slate-500 hover:text-granate">Cerrar</button>
        </div>

        <div class="space-y-4 text-sm text-slate-700">
            <div><span class="font-semibold text-slate-800">Nombre:</span> <span id="detailNombre"></span></div>
            <div><span class="font-semibold text-slate-800">Código:</span> <span id="detailCodigo"></span></div>
            <div><span class="font-semibold text-slate-800">Descripción:</span> <span id="detailDescripcion"></span></div>
            <div><span class="font-semibold text-slate-800">Estado:</span> <span id="detailEstado"></span></div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button" onclick="closeDetailModal()" class="btn btn-primary px-5 py-3">Cerrar</button>
        </div>
    </div>
</div>

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

    function openDetailModal(nombre, codigo, descripcion, estado) {
        document.getElementById('detailNombre').textContent = nombre;
        document.getElementById('detailCodigo').textContent = codigo;
        document.getElementById('detailDescripcion').textContent = descripcion;
        document.getElementById('detailEstado').textContent = estado;

        document.getElementById('detailMateriaModal').classList.remove('hidden');
        document.getElementById('detailMateriaModal').classList.add('flex');
    }

    function closeDetailModal() {
        document.getElementById('detailMateriaModal').classList.add('hidden');
    }

    function filterMateriasTable() {
        const term = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach((row) => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    }

    document.getElementById('searchInput').addEventListener('input', filterMateriasTable);

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
