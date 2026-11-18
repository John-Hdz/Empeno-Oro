<h2><?php echo isset($estado) ? 'Modificar estado' : 'Crear estado'; ?></h2>

<form action="admin_estado.php?accion=<?php echo isset($estado) ? 'modificar&id=' . e($estado['id_estado']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>
    <div class="box-container">
        <div class="box-group centralizar">
            <label for="estado">estado</label>
            <input type="text" id="estado" name="estado" value="<?php echo isset($estado) ? e($estado['estado']) : ''; ?>" required>
        </div>
    </div>
    <div class="box-container">
        <div class="box-group centralizar">
            <input type="submit" value="Guardar" class="btn light">
        </div>
    </div>
</form>
