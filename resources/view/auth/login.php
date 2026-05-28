<div class="min-h-screen grid lg:grid-cols-2" style="font-family:'Nunito',sans-serif;">

    <!-- Panel izquierdo -->
    <div class="hidden lg:flex flex-col justify-between p-12 relative overflow-hidden"
         style="background:linear-gradient(145deg,#7b1c1c 0%,#9e2a2a 50%,#5c1313 100%);">
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full" style="background:rgba(200,146,42,0.15);"></div>
        <div class="absolute -bottom-16 -left-10 w-56 h-56 rounded-full"
             style="background:rgba(255,255,255,0.06);"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 rounded-full px-4 py-2 w-fit"
                 style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.25);">
                <span class="w-2 h-2 rounded-full" style="background:#e8b44a;"></span>
                <span class="text-sm font-semibold text-white">Sistema de tutorias UNICAES</span>
            </div>

            <h1 class="mt-8 text-4xl font-bold leading-snug max-w-xs text-white"
                style="font-family:'Playfair Display',Georgia,serif;">
                Accede a tu panel con estilo y simplicidad.
            </h1>
        </div>

        <img src="<?= asset('resources/img/UNICAESlogo.png') ?>" alt="Logo UNICAES"
             class="w-300 h-70 object-contain mb-20 block" style="filter:drop-shadow(0 2px 8px rgba(0,0,0,0.3));">

        <div class="relative z-5 grid grid-cols-3 gap-3 max-w-xs">
            <div class="rounded-2xl p-4 text-center"
                 style="background:rgba(255,255,255,0.13);border:1px solid rgba(255,255,255,0.2);">
                <div class="text-xl font-bold" style="color:#e8b44a;">+50%</div>
                <div class="text-xs mt-1" style="color:rgba(255,255,255,0.75);">aprobacion</div>
            </div>
            <div class="rounded-2xl p-4 text-center"
                 style="background:rgba(255,255,255,0.13);border:1px solid rgba(255,255,255,0.2);">
                <div class="text-xl font-bold" style="color:#e8b44a;">24/7</div>
                <div class="text-xs mt-1" style="color:rgba(255,255,255,0.75);">disponibilidad</div>
            </div>
            <div class="rounded-2xl p-4 text-center"
                 style="background:rgba(255,255,255,0.13);border:1px solid rgba(255,255,255,0.2);">
                <div class="text-xl font-bold" style="color:#e8b44a;">tutorias</div>
                <div class="text-xs mt-1" style="color:rgba(255,255,255,0.75);">personalizadas</div>
            </div>
        </div>
    </div>

    <!-- Panel derecho -->
    <div class="flex items-center justify-center px-6 py-12 sm:px-10" style="background:var(--fondo-card);">
        <div class="w-full max-w-sm">

            <!-- Logo mobile -->
            <div class="lg:hidden mb-8 text-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/8/8e/UNICAES_Logo.png" alt="Logo UNICAES"
                     class="w-16 h-16 object-contain mx-auto mb-3">
                <h1 class="text-2xl font-bold"
                    style="font-family:'Playfair Display',Georgia,serif;color:var(--granate-700);">Sistema de tutorias
                    UNICAES</h1>
                <p class="text-sm mt-1" style="color:var(--texto-secundario);">Inicia sesión para continuar</p>
            </div>

            <div class="rounded-3xl p-8"
                 style="background:#fff;border:1px solid rgba(123,28,28,0.12);box-shadow:0 20px 60px rgba(92,19,19,0.08);">

                <p class="text-xs font-bold tracking-widest uppercase" style="color:var(--dorado-500);">Bienvenido</p>
                <h2 class="mt-2 text-3xl font-bold"
                    style="font-family:'Playfair Display',Georgia,serif;color:var(--granate-700);">Iniciar sesión</h2>
                <p class="mt-2 text-sm leading-relaxed" style="color:var(--texto-secundario);">Ingresa tus credenciales
                    para acceder al sistema.</p>

                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="rounded-xl px-4 py-3 text-sm font-semibold mb-2"
                         style="background:#fdf0f0;color:var(--granate-600);border:1px solid #f5c6c6;">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="/login" method="post" class="mt-8 space-y-5">

                    <div>
                        <label for="email" class="block text-sm font-semibold mb-2"
                               style="color:var(--texto-principal);">Correo electrónico</label>
                        <input
                                id="email" name="email" type="email"
                                value="<?= htmlspecialchars($_COOKIE['remember_email'] ?? '') ?>"
                                placeholder="tu@unicaes.edu.sv"
                                class="w-full rounded-2xl px-4 py-3 text-sm outline-none transition"
                                style="border:1.5px solid #e0d8d0;background:var(--fondo-base);color:var(--texto-principal);font-family:'Nunito',sans-serif;"
                                onfocus="this.style.borderColor='var(--granate-600)';this.style.boxShadow='0 0 0 4px rgba(123,28,28,0.1)';this.style.background='#fff';"
                                onblur="this.style.borderColor='#e0d8d0';this.style.boxShadow='none';this.style.background='var(--fondo-base)';"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold mb-2"
                               style="color:var(--texto-principal);">Contraseña</label>
                        <input
                                id="password" name="password" type="password"
                                placeholder="••••••••"
                                class="w-full rounded-2xl px-4 py-3 text-sm outline-none transition"
                                style="border:1.5px solid #e0d8d0;background:var(--fondo-base);color:var(--texto-principal);font-family:'Nunito',sans-serif;"
                                onfocus="this.style.borderColor='var(--granate-600)';this.style.boxShadow='0 0 0 4px rgba(123,28,28,0.1)';this.style.background='#fff';"
                                onblur="this.style.borderColor='#e0d8d0';this.style.boxShadow='none';this.style.background='var(--fondo-base)';"
                        >
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <label class="flex items-center gap-2 text-sm cursor-pointer"
                               style="color:var(--texto-secundario);">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded"
                                   style="accent-color:var(--granate-600);">
                            Recordarme
                        </label>
                        <a href="#" class="text-sm font-semibold hover:underline" style="color:var(--dorado-500);">¿Olvidaste
                            tu contraseña?</a>
                    </div>

                    <button
                            type="submit"
                            class="w-full rounded-2xl py-3.5 text-sm font-bold text-white transition"
                            style="background:var(--granate-600);box-shadow:0 4px 16px rgba(123,28,28,0.25);font-family:'Nunito',sans-serif;"
                    >
                        Entrar
                    </button>

                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <a href="<?= isset($url) ? $url('/session') : '/session' ?>" class="inline-block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-center font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-100">
                            👁️ Ver Session
                        </a>
                    </div>

                </form>

                <p class="mt-6 text-center text-sm" style="color:var(--texto-secundario);">
                    ¿No tienes una cuenta?
                    <a href="#" class="font-bold hover:underline" style="color:var(--granate-600);">Crear cuenta</a>
                </p>
            </div>
        </div>
    </div>

</div>