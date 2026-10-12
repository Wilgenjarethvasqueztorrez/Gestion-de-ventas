<!-- Sidebar Navegacion -->
<!-- Botón para abrir el menú -->
<button class="menu-toggle" id="menuToggle">
  <i class="bi bi-list"></i> Menú
</button>

<!-- Overlay oscuro -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <h3><i class="bi bi-cash-stack"></i> Sistema de Ventas</h3>
  </div>

  <ul class="sidebar-menu">
    <li>
      <a href="<?php echo BASE_URL; ?>index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <li>
      <a href="<?php echo BASE_URL; ?>pages/cliente.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'clientes.php' ? 'active' : ''; ?>">
        <i class="bi bi-people"></i>
        <span>Clientes</span>
      </a>
    </li>

    <li>
      <a href="<?php echo BASE_URL; ?>pages/producto.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'productos.php' ? 'active' : ''; ?>">
        <i class="bi bi-box"></i>
        <span>Productos</span>
      </a>
    </li>

    <li>
      <a href="<?php echo BASE_URL; ?>pages/venta.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'ventas.php' ? 'active' : ''; ?>">
        <i class="bi bi-cart-check"></i>
        <span>Ventas</span>
      </a>
    </li>
  </ul>
</nav>

<script>
  // Toggle sidebar    
  const menuToggle = document.getElementById('menuToggle');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');

  menuToggle.addEventListener('click', function() {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
  });

  overlay.addEventListener('click', function() {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
  });

  // Cerrar sidebar al hacer clic en un enlace (en móviles)    
  const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
  sidebarLinks.forEach(link => {
    link.addEventListener('click', function() {
      if (window.innerWidth <= 768) {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
      }
    });
  });
</script>