<div class="flex min-h-screen">

<?php include __DIR__ . '/../layout/Sidebar.php'; ?>

<div class="flex-1 p-6 md:p-8">
    <div class="max-w-5xl mx-auto">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                <span class="text-[#9e2820]">◆</span> Mi Perfil
            </h1>
            <p class="text-sm text-gray-400 mt-1.5">Información personal y seguridad de tu cuenta</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            <!-- Columna izquierda: Información del usuario -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden sticky top-6">
                    <div class="bg-gradient-to-br from-[#5c1313] to-[#3a0a0a] px-6 py-8 text-center relative overflow-hidden">
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/5 rounded-full"></div>
                        <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-white/5 rounded-full"></div>
                        <div class="w-20 h-20 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-4 ring-4 ring-white/15 shadow-lg">
                            <i class="fas fa-user text-white text-3xl"></i>
                        </div>
                        <h2 class="text-white text-lg font-bold"><?= htmlspecialchars(trim(($usuario['nombres'] ?? '') . ' ' . ($usuario['apellidos'] ?? ''))) ?></h2>
                        <p class="text-white/50 text-xs mt-1 truncate"><?= htmlspecialchars($usuario['email'] ?? '') ?></p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold mt-3 bg-white/10 text-white/80 border border-white/10">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                            <?= htmlspecialchars(ucfirst($usuario['rol'] ?? 'usuario')) ?>
                        </span>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <div class="flex items-center gap-3.5 px-5 py-4">
                            <span class="w-9 h-9 rounded-lg bg-[#9e2820]/5 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-[#9e2820] text-xs"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Nombre</p>
                                <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($usuario['nombres'] ?? '') ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3.5 px-5 py-4">
                            <span class="w-9 h-9 rounded-lg bg-[#9e2820]/5 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-friends text-[#9e2820] text-xs"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Apellidos</p>
                                <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($usuario['apellidos'] ?? '') ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3.5 px-5 py-4">
                            <span class="w-9 h-9 rounded-lg bg-[#9e2820]/5 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-[#9e2820] text-xs"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Correo</p>
                                <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($usuario['email'] ?? '') ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3.5 px-5 py-4">
                            <span class="w-9 h-9 rounded-lg bg-[#9e2820]/5 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-[#9e2820] text-xs"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Teléfono</p>
                                <p class="text-sm font-medium text-gray-800"><?= htmlspecialchars($usuario['telefono'] ?? '—') ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3.5 px-5 py-4">
                            <span class="w-9 h-9 rounded-lg bg-[#9e2820]/5 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-tag text-[#9e2820] text-xs"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Rol</p>
                                <p class="text-sm font-medium text-gray-800 capitalize"><?= htmlspecialchars($usuario['rol'] ?? '') ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3.5 px-5 py-4">
                            <span class="w-9 h-9 rounded-lg bg-[#9e2820]/5 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-hashtag text-[#9e2820] text-xs"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">ID</p>
                                <p class="text-sm font-medium text-gray-800">#<?= htmlspecialchars($usuario['id'] ?? '—') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna derecha: Cambiar credenciales -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                            <i class="fas fa-lock text-amber-600 text-sm"></i>
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Seguridad</h3>
                            <p class="text-xs text-gray-400">Actualiza contraseña de acceso</p>
                        </div>
                    </div>

                    <form method="POST" action="/perfil/actualizar" class="p-6 space-y-5">
                        <?php if (isset($_SESSION['perfil_ok'])): ?>
                            <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-sm text-emerald-700 flex items-center gap-2.5">
                                <i class="fas fa-check-circle text-emerald-500"></i>
                                <?= $_SESSION['perfil_ok'] ?>
                            </div>
                            <?php unset($_SESSION['perfil_ok']); ?>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['perfil_error'])): ?>
                            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700 flex items-center gap-2.5">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                                <?= $_SESSION['perfil_error'] ?>
                            </div>
                            <?php unset($_SESSION['perfil_error']); ?>
                        <?php endif; ?>

                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                                Nueva contraseña
                            </label>
                            <input type="password" name="password" minlength="6" placeholder="••••••••"
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-[#9e2820] focus:ring-2 focus:ring-[#9e2820]/10 transition-all hover:border-gray-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                                Confirmar contraseña
                            </label>
                            <input type="password" name="password_confirm" placeholder="••••••••"
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-[#9e2820] focus:ring-2 focus:ring-[#9e2820]/10 transition-all hover:border-gray-300">
                        </div>

                        <div class="bg-amber-50/50 border border-amber-100 rounded-xl p-4">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                <span class="text-red-400">*</span> Contraseña actual
                            </label>
                            <input type="password" name="current_password" required placeholder="Ingresa tu contraseña actual para confirmar"
                                   class="w-full px-4 py-2.5 text-sm bg-white border border-amber-200 rounded-xl outline-none focus:border-[#9e2820] focus:ring-2 focus:ring-[#9e2820]/10 transition-all hover:border-amber-300 placeholder:text-gray-300">
                            <p class="text-xs text-gray-400 mt-2 flex items-center gap-1.5">
                                <i class="fas fa-info-circle text-amber-400"></i>
                                Necesitamos tu contraseña actual para autorizar cualquier cambio.
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <p class="text-xs text-gray-400">
                                <i class="fas fa-shield-alt text-gray-300 mr-1"></i>
                                Tus datos se transmiten de forma segura
                            </p>
                            <button type="submit"
                                    class="px-6 py-2.5 text-sm font-semibold bg-[#9e2820] text-white rounded-xl hover:bg-[#7a2019] transition-all duration-200 flex items-center gap-2 shadow-sm shadow-[#9e2820]/20 hover:shadow-md hover:shadow-[#9e2820]/30 active:scale-[0.98]">
                                <i class="fas fa-save text-xs"></i>
                                Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
