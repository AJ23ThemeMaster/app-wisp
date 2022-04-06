<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item <?php if($page=='A'){ echo 'active';} ?>">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-tachometer-alt menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item <?php if($page=='B'){ echo 'active';} ?>">
            <a class="nav-link" href="gestion-plan.php">
                <i class="fas fa-chart-line menu-icon"></i>
                <span class="menu-title">Gestiona tu Plan</span>
            </a>
        </li>
        <li class="nav-item <?php if($page=='C'){ echo 'active';} ?>">
            <a class="nav-link" href="gestion-factura.php">
                <i class="fas fa-file-invoice-dollar menu-icon"></i>
                <span class="menu-title">Gestiona tu Factura</span>
            </a>
        </li>
        <li class="nav-item <?php if($page=='D'){ echo 'active';} ?>">
            <a class="nav-link" href="gestion-red.php">
                <i class="fas fa-network-wired menu-icon"></i>
                <span class="menu-title">Gestiona tu Red</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="logout.php">
                <i class="fas fa-power-off menu-icon"></i>
                <span class="menu-title">Cerrar Sesión</span>
            </a>
        </li>
    </ul>
</nav>