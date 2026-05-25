<?php
// Requiere que $rol y $usuario estén disponibles (desde sesión o pasados por el controlador)
// $rol: 'admin', 'tutor', 'alumno'
// $usuario: array con 'nombres', 'apellidos', 'email'

$rol     = $_SESSION['rol']     ?? 'alumno';
$usuario = $_SESSION['usuario'] ?? ['nombres' => 'Usuario', 'apellidos' => '', 'email' => ''];

$rutaActual = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function esActivo($ruta, $rutaActual) {
    return $ruta === $rutaActual ? true : false;
}

$menuAdmin = [
    ['icono' => 'bi-speedometer2',   'label' => 'Dashboard',    'ruta' => '/dashboard'],
    ['icono' => 'bi-mortarboard',    'label' => 'Tutorías',     'ruta' => '/tutorias/admin'],
    ['icono' => 'bi-clipboard-check','label' => 'Evaluaciones', 'ruta' => '/evaluaciones'],
   // ['icono' => 'bi-book',           'label' => 'Materias',     'ruta' => '/materias'],
];

$menuTutorAlumno = [
    ['icono' => 'bi-speedometer2',   'label' => 'Dashboard',    'ruta' => '/dashboard'],
    ['icono' => 'bi-mortarboard',    'label' => 'Tutorías',     'ruta' => '/tutorias'],
    ['icono' => 'bi-clipboard-check','label' => 'Evaluaciones', 'ruta' => '/evaluaciones'],
];

$menu = $rol === 'admin' ? $menuAdmin : $menuTutorAlumno;
?>

<!-- Sidebar -->
<aside id="sidebar"
       class="fixed top-0 left-0 h-full z-40 flex flex-col transition-all duration-300 shadow-lg"
       style="width:240px; background:var(--granate-800, #6b0f0f);">

    <!-- Header del sidebar: logo + botón colapsar -->
    <!-- Header del sidebar -->
    <div class="relative flex items-center gap-3 px-4 py-4 border-b border-white/10">
    <img id="sidebar-logo"
         src="/img/logo.png"
         alt="Logo Unicaes"
         class="h-10 w-10 flex-shrink-0 transition-opacity duration-300">
    <span class="sidebar-label text-white font-bold text-sm tracking-wide leading-tight"
          style="font-family:'Playfair Display',serif;">
        UNICAES<br>
        <span class="text-white/60 font-normal text-xs">Sistema de Tutoría</span>
    </span>
    <button onclick="toggleSidebar()"
            class="absolute right-3 text-white/70 hover:text-white transition-colors focus:outline-none"
            title="Colapsar menú">
        <i class="bi bi-layout-sidebar-reverse text-xl"></i>
    </button>
</div>

    <!-- Menú principal -->
    <nav class="flex-1 overflow-y-auto py-4 flex flex-col gap-1 px-2">
        <?php foreach ($menu as $item): ?>
            <?php $activo = esActivo($item['ruta'], $rutaActual); ?>
            <a href="<?= $item['ruta'] ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                      <?= $activo
                          ? 'bg-white/20 text-white font-semibold'
                          : 'text-white/70 hover:bg-white/10 hover:text-white' ?>">
                <i class="bi <?= $item['icono'] ?> text-lg flex-shrink-0"></i>
                <span class="sidebar-label text-sm transition-opacity duration-300">
                    <?= $item['label'] ?>
                </span>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Footer: perfil del usuario -->
    <div class="border-t border-white/10 px-3 py-3">
        <a href="/perfil"
           class="flex items-center gap-3 px-2 py-2 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200">
            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                <i class="bi bi-person-fill text-white text-sm"></i>
            </div>
            <div class="sidebar-label overflow-hidden">
                <p class="text-white text-sm font-semibold truncate leading-tight">
                    <?= htmlspecialchars($usuario['nombres'] . ' ' . $usuario['apellidos']) ?>
                </p>
                <p class="text-white/50 text-xs truncate">
                    <?= htmlspecialchars($usuario['email']) ?>
                </p>
            </div>
        </a>
        <a href="./auth/login"
           class="flex items-center gap-3 px-3 py-2 mt-1 rounded-xl text-white/60 hover:bg-white/10 hover:text-red-300 transition-all duration-200">
            <i class="bi bi-box-arrow-left text-lg flex-shrink-0"></i>
            <span class="sidebar-label text-sm">Cerrar sesión</span>
        </a>
    </div>
</aside>

<!-- Overlay para móvil -->
<div id="sidebar-overlay"
     class="hidden fixed inset-0 bg-black/40 z-30"
     onclick="toggleSidebar()">
</div>

<!-- Espaciador para que el contenido no quede debajo del sidebar -->
<div id="sidebar-spacer" style="width:240px; flex-shrink:0; transition: width 0.3s;"></div>

<style>
    #sidebar.collapsed { width: 68px !important; }
    #sidebar.collapsed .sidebar-label { opacity: 0; width: 0; overflow: hidden; }
    #sidebar.collapsed #sidebar-logo  { opacity: 0; width: 0; overflow: hidden; }
    #sidebar-spacer.collapsed          { width: 68px !important; }

    @media (max-width: 768px) {
        #sidebar          { transform: translateX(-100%); }
        #sidebar.mobile-open { transform: translateX(0); }
        #sidebar-spacer   { width: 0 !important; }
    }
</style>

<script>
    const sidebar        = document.getElementById('sidebar');
    const spacer         = document.getElementById('sidebar-spacer');
    const overlay        = document.getElementById('sidebar-overlay');
    const isMobile       = () => window.innerWidth < 768;
    const STORAGE_KEY    = 'sidebar_collapsed';

    // Restaurar estado guardado
    if (!isMobile() && localStorage.getItem(STORAGE_KEY) === '1') {
        sidebar.classList.add('collapsed');
        spacer.classList.add('collapsed');
    }

    function toggleSidebar() {
        if (isMobile()) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('hidden');
        } else {
            const collapsed = sidebar.classList.toggle('collapsed');
            spacer.classList.toggle('collapsed');
            localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
        }
    }
</script>