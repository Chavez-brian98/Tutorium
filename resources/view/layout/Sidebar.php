<?php
// Requiere que $rol y $users estén disponibles (desde sesión o pasados por el controlador)
// $rol: 'admin', 'tutor', 'alumno'

$rol      = $_SESSION['rol']      ?? 'alumno';
$nombres  = $_SESSION['nombres']  ?? 'Usuario';
$apellidos = $_SESSION['apellidos'] ?? '';
$email    = $_SESSION['email']    ?? '';

$rutaActual = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function esActivo($ruta, $rutaActual) {
    return $ruta === $rutaActual ? true : false;
}

// Íconos Premium de FontAwesome 5
$menuAdmin = [
        ['icono' => 'fas fa-chart-pie',      'label' => 'Dashboard',    'ruta' => '/dashboard'],
        ['icono' => 'fas fa-graduation-cap', 'label' => 'Tutorías',     'ruta' => '/tutorias/admin'],
        ['icono' => 'fas fa-clipboard-list', 'label' => 'Evaluaciones', 'ruta' => '/evaluaciones'],
        ['icono' => 'fas fa-book',           'label' => 'Materias',     'ruta' => '/materias'],
        ['icono' => 'fas fa-users',          'label' => 'Usuarios',     'ruta' => '/usuarios'],
];

$menuTutorAlumno = [
//        ['icono' => 'fas fa-chart-pie',      'label' => 'Dashboard',    'ruta' => '/dashboard'],
        ['icono' => 'fas fa-graduation-cap', 'label' => 'Tutorías',     'ruta' => '/tutorias'],
        ['icono' => 'fas fa-clipboard-list', 'label' => 'Evaluaciones', 'ruta' => '/evaluaciones'],
];

$menu = $rol === 'admin' ? $menuAdmin : $menuTutorAlumno;
?>

<aside id="sidebar"
       class="fixed top-0 left-0 h-full z-40 flex flex-col transition-all duration-300 shadow-2xl border-r border-white/5"
       style="width:240px; background: linear-gradient(180deg, #5c1313 0%, #3a0a0a 100%);">

    <div class="relative flex items-center gap-3 px-4 py-5 border-b border-white/10 bg-black/10">
        <img id="sidebar-logo"
             src="/img/logo.png"
             alt="Logo Unicaes"
             class="h-10 w-10 flex-shrink-0 transition-all duration-300 transform hover:scale-110 hover:rotate-3 filter drop-shadow-md cursor-pointer">
        <span class="sidebar-label text-white font-bold text-sm tracking-wide leading-tight transition-opacity duration-300"
              style="font-family:'Playfair Display', serif;">
            UNICAES<br>
            <span class="font-normal text-xs" style="color: #c8922a;">Sistema de Tutoría</span>
        </span>
        <button onclick="toggleSidebar()"
                class="absolute right-3 text-white/60 hover:text-white transition-all duration-200 bg-white/5 hover:bg-amber-500/20 w-8 h-8 rounded-lg flex items-center justify-center border border-white/10 hover:border-amber-500/30 shadow-sm active:scale-95"
                title="Colapsar menú" style="margin-right: 4px;">
            <i id="toggle-icon" class="fas fa-chevron-left text-xs transition-transform duration-300"></i>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 flex flex-col gap-2 px-3">
        <?php foreach ($menu as $item): ?>
            <?php $activo = esActivo($item['ruta'], $rutaActual); ?>
            <a href="<?= $item['ruta'] ?>"
               class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-300 ease-out group relative overflow-hidden
                      <?= $activo
                       ? 'bg-gradient-to-r from-amber-500/20 to-amber-600/30 text-white font-bold border-l-4 border-amber-500 shadow-md shadow-black/20'
                       : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1.5 border-l-4 border-transparent hover:border-amber-500/40' ?>">

                <i class="<?= $item['icono'] ?> text-base flex-shrink-0 w-5 text-center transition-all duration-300 transform group-hover:scale-125 <?= $activo ? 'text-amber-400' : 'text-white/60 group-hover:text-amber-400' ?>"></i>

                <span class="sidebar-label text-sm tracking-medium transition-opacity duration-300">
                    <?= $item['label'] ?>
                </span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="border-t border-white/10 p-3 bg-black/10">
        <a href="/perfil"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/5 hover:text-white border border-transparent hover:border-white/10 shadow-inner transition-all duration-300 group">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-amber-500 to-amber-600 flex items-center justify-center flex-shrink-0 shadow-md transition-transform duration-300 group-hover:scale-105 group-hover:rotate-6">
                <i class="fas fa-user text-white text-sm"></i>
            </div>
            <div class="sidebar-label overflow-hidden">
                <p class="text-white text-sm font-bold truncate leading-tight group-hover:text-amber-400 transition-colors duration-200">
                    <?= htmlspecialchars(trim($nombres . ' ' . $apellidos)) ?>
                </p>
                <p class="text-white/40 text-xs truncate mt-0.5">
                    <?= htmlspecialchars($email) ?>
                </p>
            </div>
        </a>

        <a href="/logout"
           class="flex items-center gap-3 px-4 py-2.5 mt-2 rounded-xl text-white/50 hover:bg-red-500/10 hover:text-red-400 transition-all duration-300 font-medium group hover:translate-x-1">
            <i class="fas fa-sign-out-alt text-base flex-shrink-0 w-5 text-center text-white/40 group-hover:text-red-400 transition-all duration-300 transform group-hover:-translate-x-1 group-hover:scale-110"></i>
            <span class="sidebar-label text-sm">Cerrar sesión</span>
        </a>
    </div>
</aside>

<div id="sidebar-overlay"
     class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-30 transition-opacity duration-300"
     onclick="toggleSidebar()">
</div>

<div id="sidebar-spacer" style="width:240px; flex-shrink:0; transition: width 0.3s;"></div>

<style>
    #sidebar.collapsed { width: 72px !important; }
    #sidebar.collapsed .sidebar-label { opacity: 0; width: 0; overflow: hidden; white-space: nowrap; }
    #sidebar.collapsed #sidebar-logo  { transform: scale(0.9); }
    #sidebar.collapsed #toggle-icon   { transform: rotate(180deg); }
    #sidebar-spacer.collapsed          { width: 72px !important; }

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