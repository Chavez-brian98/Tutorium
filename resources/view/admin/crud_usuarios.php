<?php
$usuarios = $usuarios ?? [];
$totalUsuarios = count($usuarios);
$usuariosActivos = count(array_filter($usuarios, fn($item) => strtoupper($item['estado'] ?? '') === 'ACTIVO'));
$usuariosInactivos = $totalUsuarios - $usuariosActivos;
?>

<?php include __DIR__ . '/../layout/sidebar.php'; ?>

<div class="md:ml-64 p-6 md:p-8" style="background-color: #f0ebe3;">
    <div class="mb-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-dorado">Panel de administración</p>
                <h1 class="text-4xl font-bold text-granate mt-3">Gestión de Usuarios</h1>
            </div>
            <button type="button" onclick="openCreateModal()" class="btn btn-primary px-6 py-3">Nuevo usuario</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <article class="rounded-3xl bg-card p-6 border border-white/70 shadow-sm">
            <p class="text-sm font-semibold text-secundario">Total de usuarios</p>
            <p class="mt-4 text-4xl font-bold text-granate"><?= $totalUsuarios ?></p>
            <p class="mt-2 text-sm text-secundario">Usuarios registrados en el sistema</p>
        </article>

        <article class="rounded-3xl bg-card p-6 border border-white/70 shadow-sm">
            <p class="text-sm font-semibold text-secundario">Activos</p>
            <p class="mt-4 text-4xl font-bold text-granate"><?= $usuariosActivos ?></p>
            <p class="mt-2 text-sm text-secundario">Usuarios con estado ACTIVO</p>
        </article>

        <article class="rounded-3xl bg-card p-6 border border-white/70 shadow-sm">
            <p class="text-sm font-semibold text-secundario">Inactivos</p>
            <p class="mt-4 text-4xl font-bold text-granate"><?= $usuariosInactivos ?></p>
            <p class="mt-2 text-sm text-secundario">Usuarios con estado INACTIVO</p>
        </article>
    </div>

    <section class="rounded-[36px] bg-white shadow-lg border border-slate-200 p-6" style="background:white; border-radius:16px; border-top:8px solid var(--granate-600); padding:24px; box-shadow:0 1px 4px rgba(0,0,0,0.08);">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-granate">Catalogo de Usuarios</h2>
                <p class="mt-2 text-secundario">Revisa, edita o elimina los usuarios registrados.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative w-full max-w-xs">
                    <input id="searchUsuariosInput" type="search" placeholder="Buscar usuario..." class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                    <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">🔍</span>
                </div>
                <span class="inline-flex items-center rounded-full bg-granate/10 px-4 py-2 text-sm font-semibold text-granate">Total: <?= $totalUsuarios ?></span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] border-separate border-spacing-0 text-sm">
                <thead class="bg-granate text-white rounded-3xl">
                    <tr>
                        <th class="px-5 py-4 text-left">Nombres</th>
                        <th class="px-5 py-4 text-left">Apellidos</th>
                        <th class="px-5 py-4 text-left">Correo</th>
                        <th class="px-5 py-4 text-left">Teléfono</th>
                        <th class="px-5 py-4 text-left">Rol</th>
                        <th class="px-5 py-4 text-left">Estado</th>
                        <th class="px-5 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr class="border-b border-slate-200 last:border-none hover:bg-slate-50">
                                <td class="px-5 py-4 text-slate-800"><?= htmlspecialchars($usuario['nombres']) ?></td>
                                <td class="px-5 py-4 text-slate-600"><?= htmlspecialchars($usuario['apellidos']) ?></td>
                                <td class="px-5 py-4 text-slate-600"><?= htmlspecialchars($usuario['email']) ?></td>
                                <td class="px-5 py-4 text-slate-600"><?= htmlspecialchars($usuario['telefono'] ?? '-') ?></td>
                                <td class="px-5 py-4 text-slate-800"><?= htmlspecialchars(ucfirst(strtolower($usuario['rol'] ?? ''))) ?></td>
                                <td class="px-5 py-4">
                                    <?php $activo = strtoupper($usuario['estado'] ?? '') === 'ACTIVO'; ?>
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold <?= $activo ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-700' ?>">
                                        <?= $activo ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <button type="button"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:border-granate hover:bg-granate hover:text-white js-edit-usuario-btn"
                                                title="Editar"
                                                data-id="<?= (int) $usuario['id'] ?>"
                                                data-nombres="<?= htmlspecialchars($usuario['nombres'], ENT_QUOTES) ?>"
                                                data-apellidos="<?= htmlspecialchars($usuario['apellidos'], ENT_QUOTES) ?>"
                                                data-email="<?= htmlspecialchars($usuario['email'], ENT_QUOTES) ?>"
                                                data-telefono="<?= htmlspecialchars($usuario['telefono'] ?? '', ENT_QUOTES) ?>"
                                                data-rol="<?= htmlspecialchars($usuario['rol'] ?? '', ENT_QUOTES) ?>"
                                                data-estado="<?= htmlspecialchars($usuario['estado'] ?? '', ENT_QUOTES) ?>">
                                            ✎
                                        </button>
                                        <button type="button"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:border-red-400 hover:bg-red-500 hover:text-white"
                                                title="Eliminar"
                                                onclick="confirmDeleteUsuario(<?= (int) $usuario['id'] ?>)">
                                            🗑
                                        </button>
                                        <button type="button"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:border-granate hover:bg-granate hover:text-white"
                                                title="Ver detalle"
                                                onclick="openDetailUsuarioModal('<?= htmlspecialchars($usuario['nombres'], ENT_QUOTES) ?>', '<?= htmlspecialchars($usuario['apellidos'], ENT_QUOTES) ?>', '<?= htmlspecialchars($usuario['email'], ENT_QUOTES) ?>', '<?= htmlspecialchars($usuario['telefono'] ?? '-', ENT_QUOTES) ?>', '<?= htmlspecialchars($usuario['rol'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($usuario['estado'] ?? '', ENT_QUOTES) ?>')">
                                            👁
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-5 py-6 text-center text-secundario">No hay usuarios registrados todavía.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<form id="deleteUsuarioForm" action="/usuarios/eliminar" method="post" hidden>
    <input type="hidden" name="id" id="deleteUsuarioId">
</form>

<div id="detailUsuarioModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-xl rounded-[32px] bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-granate">Detalle de usuario</h3>
                <p class="text-sm text-secundario">Revisa la información completa del usuario seleccionado.</p>
            </div>
            <button type="button" onclick="closeDetailUsuarioModal()" class="text-slate-500 hover:text-granate">Cerrar</button>
        </div>

        <div class="space-y-4 text-sm text-slate-700">
            <div><span class="font-semibold text-slate-800">Nombres:</span> <span id="detailUsuarioNombres"></span></div>
            <div><span class="font-semibold text-slate-800">Apellidos:</span> <span id="detailUsuarioApellidos"></span></div>
            <div><span class="font-semibold text-slate-800">Correo:</span> <span id="detailUsuarioEmail"></span></div>
            <div><span class="font-semibold text-slate-800">Teléfono:</span> <span id="detailUsuarioTelefono"></span></div>
            <div><span class="font-semibold text-slate-800">Rol:</span> <span id="detailUsuarioRol"></span></div>
            <div><span class="font-semibold text-slate-800">Estado:</span> <span id="detailUsuarioEstado"></span></div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button" onclick="closeDetailUsuarioModal()" class="btn btn-primary px-5 py-3">Cerrar</button>
        </div>
    </div>
</div>

<div id="createUsuarioModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-2xl rounded-[32px] bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-granate">Nuevo usuario</h3>
                <p class="text-sm text-secundario">Registra un nuevo usuario para el sistema.</p>
            </div>
            <button type="button" onclick="closeCreateModal()" class="text-slate-500 hover:text-granate">Cerrar</button>
        </div>

        <form action="/usuarios/guardar" method="post" class="space-y-5">
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Nombres</span>
                    <input name="nombres" type="text" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" placeholder="Ej: Juan" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Apellidos</span>
                    <input name="apellidos" type="text" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" placeholder="Ej: Pérez" />
                </label>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Correo</span>
                    <input name="email" type="email" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" placeholder="ejemplo@correo.com" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Teléfono</span>
                    <input name="telefono" type="tel" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" placeholder="Ej: +503 1234-5678" />
                </label>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Contraseña</span>
                    <input name="password" type="password" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" placeholder="Mínimo 8 caracteres" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Rol</span>
                    <select name="rol" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10">
                        <option value="admin">Administrador</option>
                        <option value="tutor">Tutor</option>
                        <option value="alumno">Alumno</option>
                    </select>
                </label>
            </div>
            <label class="block">
                <span class="text-sm font-semibold text-slate-700">Estado</span>
                <select name="estado" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10">
                    <option value="ACTIVO">Activo</option>
                    <option value="INACTIVO">Inactivo</option>
                </select>
            </label>
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeCreateModal()" class="btn btn-outline px-5 py-3">Cancelar</button>
                <button type="submit" class="btn btn-primary px-5 py-3">Guardar usuario</button>
            </div>
        </form>
    </div>
</div>

<div id="editUsuarioModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-2xl rounded-[32px] bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-granate">Editar usuario</h3>
                <p class="text-sm text-secundario">Actualiza los datos del usuario seleccionado.</p>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-slate-500 hover:text-granate">Cerrar</button>
        </div>

        <form id="editUsuarioForm" action="/usuarios/actualizar" method="post" class="space-y-5">
            <input type="hidden" name="id" id="editUsuarioId" />
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Nombres</span>
                    <input id="editNombres" name="nombres" type="text" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Apellidos</span>
                    <input id="editApellidos" name="apellidos" type="text" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Correo</span>
                    <input id="editEmail" name="email" type="email" required class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Teléfono</span>
                    <input id="editTelefono" name="telefono" type="tel" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
                </label>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Contraseña</span>
                    <input id="editPassword" name="password" type="password" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" placeholder="Dejar vacio para no cambiar" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-slate-700">Rol</span>
                    <select id="editRol" name="rol" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10">
                        <option value="admin">Administrador</option>
                        <option value="tutor">Tutor</option>
                        <option value="alumno">Alumno</option>
                    </select>
                </label>
            </div>
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
        document.getElementById('createUsuarioModal').classList.remove('hidden');
        document.getElementById('createUsuarioModal').classList.add('flex');
    }

    function closeCreateModal() {
        document.getElementById('createUsuarioModal').classList.add('hidden');
    }

    function openEditModal(id, nombres, apellidos, email, telefono, rol, estado) {
        document.getElementById('editUsuarioId').value = id;
        document.getElementById('editNombres').value = nombres;
        document.getElementById('editApellidos').value = apellidos;
        document.getElementById('editEmail').value = email;
        document.getElementById('editTelefono').value = telefono;
        document.getElementById('editRol').value = rol;
        document.getElementById('editEstado').value = estado;

        document.getElementById('editUsuarioModal').classList.remove('hidden');
        document.getElementById('editUsuarioModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editUsuarioModal').classList.add('hidden');
    }

    function openDetailUsuarioModal(nombres, apellidos, email, telefono, rol, estado) {
        document.getElementById('detailUsuarioNombres').textContent = nombres;
        document.getElementById('detailUsuarioApellidos').textContent = apellidos;
        document.getElementById('detailUsuarioEmail').textContent = email;
        document.getElementById('detailUsuarioTelefono').textContent = telefono;
        document.getElementById('detailUsuarioRol').textContent = rol;
        document.getElementById('detailUsuarioEstado').textContent = estado;

        document.getElementById('detailUsuarioModal').classList.remove('hidden');
        document.getElementById('detailUsuarioModal').classList.add('flex');
    }

    function closeDetailUsuarioModal() {
        document.getElementById('detailUsuarioModal').classList.add('hidden');
    }

    function filterUsuariosTable() {
        const term = document.getElementById('searchUsuariosInput').value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach((row) => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    }

    document.getElementById('searchUsuariosInput').addEventListener('input', filterUsuariosTable);

    document.querySelectorAll('.js-edit-usuario-btn').forEach((button) => {
        button.addEventListener('click', function () {
            openEditModal(
                this.dataset.id,
                this.dataset.nombres,
                this.dataset.apellidos,
                this.dataset.email,
                this.dataset.telefono,
                this.dataset.rol,
                this.dataset.estado
            );
        });
    });

    function confirmDeleteUsuario(id) {
        if (!window.appAlerts) {
            if (confirm('¿Deseas eliminar este usuario?')) {
                document.getElementById('deleteUsuarioId').value = id;
                document.getElementById('deleteUsuarioForm').submit();
            }
            return;
        }

        window.appAlerts.confirm({
            title: 'Eliminar usuario',
            text: '¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc2626',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteUsuarioId').value = id;
                document.getElementById('deleteUsuarioForm').submit();
            }
        });
    }
</script>
