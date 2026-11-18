<div class="login-container">
<form class="login-form" method="post" action="admin_login.php?accion=nueva">
<?php echo csrf_field(); ?>
<h1 class="login-text">RESTABLECER CONTRASEÑA</h1>
  <h2 class="login-text">Crea una nueva contraseña</h2>

  <!-- Password input -->
    <input type="password" name="datos[contrasenia]" placeholder="Contraseña" required/>

    <!-- Password confirm -->
    <input type="password" name="datos[confirmar_contrasenia]" placeholder="Confirmar Contraseña" required/>

  <!-- Submit button -->
  <input class="" name="enviar" type="submit" value="Ingresar"/>

  <input type="hidden" name="datos[correo]" value="<?php echo e($datos['correo'] ?? ''); ?>"/> <br>
    <input type="hidden" name="datos[token]" value="<?php echo e($datos['token'] ?? ''); ?>"/>
</form>
</div>
