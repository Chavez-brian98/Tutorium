<?php
// Vista de login visual בלבד, sin lógica de autenticación
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Tutorium</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
    <div class="min-h-screen grid lg:grid-cols-2">
        <!-- Panel izquierdo -->
        <div class="hidden lg:flex relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-cyan-500">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.18),transparent_40%),radial-gradient(circle_at_bottom_left,rgba(255,255,255,0.12),transparent_35%)]"></div>
            <div class="relative z-10 flex flex-col justify-between p-12 w-full text-white">
                <div>
                    <div class="inline-flex items-center gap-3 rounded-full bg-white/15 px-4 py-2 backdrop-blur-sm border border-white/20">
                        <div class="h-3 w-3 rounded-full bg-white"></div>
                        <span class="text-sm font-medium tracking-wide">Tutorium</span>
                    </div>
                    <h1 class="mt-10 text-5xl font-extrabold leading-tight max-w-xl">
                        Accede a tu panel con estilo y simplicidad.
                    </h1>
                    <p class="mt-6 max-w-lg text-lg text-white/85 leading-relaxed">
                        Una interfaz limpia, elegante y moderna para iniciar sesión en tu sistema.
                        Diseñada para verse bien en cualquier pantalla.
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-4 max-w-lg">
                    <div class="rounded-2xl bg-white/15 p-4 backdrop-blur-sm border border-white/20">
                        <div class="text-2xl font-bold">+24%</div>
                        <div class="text-sm text-white/80 mt-1">Productividad</div>
                    </div>
                    <div class="rounded-2xl bg-white/15 p-4 backdrop-blur-sm border border-white/20">
                        <div class="text-2xl font-bold">24/7</div>
                        <div class="text-sm text-white/80 mt-1">Acceso</div>
                    </div>
                    <div class="rounded-2xl bg-white/15 p-4 backdrop-blur-sm border border-white/20">
                        <div class="text-2xl font-bold">SSL</div>
                        <div class="text-sm text-white/80 mt-1">Seguro</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel derecho -->
        <div class="flex items-center justify-center px-6 py-12 sm:px-10 lg:px-16 bg-white">
            <div class="w-full max-w-md">
                <div class="lg:hidden mb-8 text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-200">
                        <span class="text-xl font-bold">T</span>
                    </div>
                    <h1 class="text-3xl font-extrabold text-slate-900">Tutorium</h1>
                    <p class="mt-2 text-slate-500">Inicia sesión para continuar</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-[0_20px_60px_rgba(15,23,42,0.08)]">
                    <div class="mb-8">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Bienvenido</p>
                        <h2 class="mt-2 text-3xl font-bold text-slate-900">Iniciar sesión</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Ingresa tus credenciales para acceder al sistema.
                        </p>
                    </div>

                    <form action="#" method="post" class="space-y-5">
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Correo electrónico</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                placeholder="tu@email.com"
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            >
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Contraseña</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="••••••••"
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            >
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                Recordarme
                            </label>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline">¿Olvidaste tu contraseña?</a>
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-2xl bg-blue-600 px-4 py-3.5 font-semibold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-200 focus:outline-none focus:ring-4 focus:ring-blue-200"
                        >
                            Entrar
                        </button>
                    </form>

                    <div class="my-8 flex items-center gap-4">
                        <div class="h-px flex-1 bg-slate-200"></div>
                        <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">o continúa con</span>
                        <div class="h-px flex-1 bg-slate-200"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50">
                            Google
                        </button>
                        <button class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50">
                            Microsoft
                        </button>
                    </div>

                    <p class="mt-8 text-center text-sm text-slate-500">
                        ¿No tienes una cuenta?
                        <a href="#" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline">Crear cuenta</a>
                    </p>

                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <a href="<?= isset($url) ? $url('/session') : '/session' ?>" class="inline-block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-center font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-100">
                            👁️ Ver Session
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

