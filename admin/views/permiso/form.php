<h2><?php echo isset($permiso) ? 'Modificar permiso' : 'Crear permiso'; ?></h2>

<form action="admin_permiso.php?accion=<?php echo isset($permiso) ? 'modificar&id=' . e($permiso['id_permiso']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>

    <div class="box-container">

        <div class="box-group centralizar">
            <label for="permiso">Permiso</label>
            <input type="text" id="permiso" name="permiso" value="<?php echo isset($permiso) ? e($permiso['permiso']) : ''; ?>" required>
        </div>
        
        <div class="centralizar">
            <input type="submit" value="Guardar Cambios" class="btn light">
        </div>
    </div>
</form>
