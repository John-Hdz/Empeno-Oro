<div class="login-container">
  <form class="login-form" method="POST" action="admin_login.php?accion=login">
    <?php echo csrf_field(); ?>
    <h1 class="login-text">Iniciar Sesión</h1>

    <input type="email" name="datos[correo]" placeholder="Correo" required />
    <input type="password" name="datos[contrasenia]" placeholder="Contraseña" required />

    <a class="login-ref" href="admin_login.php?accion=olvidar">¿Olvidaste tu contraseña?</a>

    <input class="login-button" name="enviar" type="submit" value="Ingresar" />
  </form>
</div>
