<h2><?php echo isset($usuario) ? 'Modificar Usuario' : 'Crear Usuario'; ?></h2>

<?php if(isset($usuario)):?>
    <div class="box-container">
        <div class="box-group">
            <label for="">Cambiar la contraseña del usuario</label>
            <?php $token = $app->cambiar_contraseña($usuario['correo']); ?>
            <a href="admin_login.php?accion=restablecer&correo=<?php echo urlencode($usuario['correo']); ?>&token=<?php echo urlencode((string) $token); ?>" class="btn light">Ir</a>
        </div>
</div>
<?php endif; ?>

<form action="admin_usuario.php?accion=<?php echo isset($usuario) ? 'modificar&id=' . e($usuario['id_usuario']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>

    <div class="box-container more_space">
        <div class="box-group centralizar">
            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" value="<?php echo isset($usuario) ? e($usuario['correo']) : ''; ?>" required>
        </div>
        <?php if(!isset($usuario)): ?>
            <div class="box-group centralizar">
            <label for="contrasenia">Contraseña</label>
            <input type="password" id="contrasenia" name="contrasenia" required>
        </div>
        <div class="box-group centralizar">
            <label for="contrasenia2">Repetir Contraseña</label>
            <input type="password" id="contrasenia2" name="contrasenia2" required>
        </div>
        <?php endif;?>

    </div>

    <h2>Roles Del Usuario</h2>

    <div class="box-container more_space">
        <?php foreach ($roles as $rol): ?>
            <div class="box-group centralizar">
            <label for="<?php echo e($rol['rol']); ?>"><?php echo e($rol['rol']); ?></label>
            <input type="checkbox" id="<?php echo e($rol['rol']); ?>" name="roles[]"
                    value="<?php echo e($rol['rol']); ?>"
                   <?php echo (isset($roles_usuario) && in_array($rol['rol'], $roles_usuario, true)) ? 'checked' : ''; ?>>
            </div>
        <?php endforeach; ?>

    </div>

    <div class="centralizar more_space">
            <input type="submit" value="Guardar Cambios" class="btn light">
        </div>
</form>
