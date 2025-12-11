 <!-- Agregar barra de usuario logueado -->  
<div class="user-header-bar">  
  <div class="user-info-section">  
    <div class="user-avatar">  
      <i class="bi bi-person-circle"></i>  
    </div>  
    <div class="user-details">  
      <span class="welcome-text">Bienvenido/a,</span>  
      <div class="user-name">  
        <?php echo $_SESSION['usuario_nombre'] . ' ' . $_SESSION['usuario_apellido']; ?>  
      </div>  
      <span class="badge role-badge"><?php echo $_SESSION['usuario_rol']; ?></span>  
    </div>  
  </div>  
  <a href="CRUD/Login/cerrarSesion.php" class="btn btn-logout">  
    <i class="bi bi-box-arrow-right"></i>  
    <span>Cerrar Sesión</span>  
  </a>  
</div>