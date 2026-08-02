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

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 px-5 py-3.5 text-sm text-emerald-700 flex items-center gap-2.5">
            <i class="fas fa-check-circle text-emerald-500"></i>
            <?= $_SESSION['flash_success'] ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-3.5 text-sm text-red-700 flex items-center gap-2.5">
            <i class="fas fa-exclamation-circle text-red-500"></i>
            <?= $_SESSION['flash_error'] ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <article class="rounded-2xl p-6 transition-all hover:shadow-lg hover:-translate-y-1 bg-white"
                 style="border: 1px solid rgba(123, 28, 28, 0.1);">
            <p class="text-sm font-semibold" style="color: #5a5a5a;">Total de usuarios</p>
            <p class="mt-4 text-4xl font-bold" style="color: #5c1313;"><?= $totalUsuarios ?></p>
            <p class="mt-2 text-sm" style="color: #5a5a5a;">Usuarios registrados en el sistema</p>
        </article>

        <article class="rounded-2xl p-6 transition-all hover:shadow-lg hover:-translate-y-1 bg-white"
                 style="border: 1px solid rgba(123, 28, 28, 0.1);">
            <p class="text-sm font-semibold" style="color: #5a5a5a;">Activos</p>
            <p class="mt-4 text-4xl font-bold" style="color: #5c1313;"><?= $usuariosActivos ?></p>
            <p class="mt-2 text-sm" style="color: #5a5a5a;">Usuarios con estado ACTIVO</p>
        </article>

        <article class="rounded-2xl p-6 transition-all hover:shadow-lg hover:-translate-y-1 bg-white"
                 style="border: 1px solid rgba(123, 28, 28, 0.1);">
            <p class="text-sm font-semibold" style="color: #5a5a5a;">Inactivos</p>
            <p class="mt-4 text-4xl font-bold" style="color: #5c1313;"><?= $usuariosInactivos ?></p>
            <p class="mt-2 text-sm" style="color: #5a5a5a;">Usuarios con estado INACTIVO</p>
        </article>
    </div>

    <section class="rounded-[36px] bg-white shadow-lg border border-slate-200 p-6">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-granate">Catalogo de Usuarios</h2>
                <p class="mt-2 text-secundario">Revisa, edita o elimina los usuarios registrados.</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-granate/10 px-4 py-2 text-sm font-semibold text-granate">Total: <?= $totalUsuarios ?></span>
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
                                    <button type="button"
                                            class="btn btn-outline mr-2 js-edit-usuario-btn"
                                            data-id="<?= (int) $usuario['id'] ?>"
                                            data-nombres="<?= htmlspecialchars($usuario['nombres'], ENT_QUOTES) ?>"
                                            data-apellidos="<?= htmlspecialchars($usuario['apellidos'], ENT_QUOTES) ?>"
                                            data-email="<?= htmlspecialchars($usuario['email'], ENT_QUOTES) ?>"
                                            data-telefono="<?= htmlspecialchars($usuario['telefono'] ?? '', ENT_QUOTES) ?>"
                                            data-rol="<?= htmlspecialchars($usuario['rol'] ?? '', ENT_QUOTES) ?>"
                                            data-estado="<?= htmlspecialchars($usuario['estado'] ?? '', ENT_QUOTES) ?>">
                                        Editar
                                    </button>
                                    <button type="button"
                                            onclick="confirmDeleteUsuario(<?= (int) $usuario['id'] ?>)"
                                            class="btn btn-danger">Eliminar</button>
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
                    <input name="telefono" type="tel" maxlength="8" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" placeholder="Ej: 12345678" />
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
                    <input id="editTelefono" name="telefono" type="tel" maxlength="8" class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-granate focus:ring-2 focus:ring-granate/10" />
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
            if (confirm('!Deseas eliminar este usuario?')) {
                document.getElementById('deleteUsuarioId').value = id;
                document.getElementById('deleteUsuarioForm').submit();
            }
            return;
        }

        window.appAlerts.confirm({
            title: 'Eliminar usuario',
            text: '!Estas seguro de eliminar este usuario? Esta acción no se puede deshacer.',
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
