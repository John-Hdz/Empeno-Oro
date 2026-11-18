<div class="login-container">
<form class="login-form" method="post" action="admin_login.php?accion=cambiar">
  <?php echo csrf_field(); ?>
  <h2 class="login-text">Recuperar contraseña</h2>
  <p class="login-text">Por favor ingresa tu correo electrónico para recibir un enlace de restablecimiento de contraseña.</p>
  <p class="login-text">Si no recibes el correo, revisa tu carpeta de spam o intenta nuevamente.</p>
  <!-- Email input -->
    <input type="email" name="datos[correo]" placeholder="Correo" required/>

  <!-- Submit button -->
  <input class="" name="enviar" type="submit" value="Enviar"/>

  <!-- Register buttons -->
    <p class="login-text">¿No tienes cuenta? <a href="" class="login-ref">Registrar</a></p>
</form>
</div>
