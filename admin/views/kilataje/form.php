<h2><?php echo isset($kilataje) ? 'Modificar kilataje' : 'Crear kilataje'; ?></h2>

<form action="admin_kilataje.php?accion=<?php echo isset($kilataje) ? 'modificar&id=' . e($kilataje['id_kilataje']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>

    <div class="box-container">
        <div class="box-group centralizar">
            <label for="precio_gramo">Precio por gramo</label>
            <input type="text" id="precio_gramo" name="precio_gramo" value="<?php echo isset($kilataje) ? e($kilataje['precio_gramo']) : ''; ?>" required>
        </div>

        <div class="box-group centralizar">
            <label for="kilataje">Kilataje</label>
            <input type="text" id="kilataje" name="kilataje" value="<?php echo isset($kilataje) ? e($kilataje['kilataje']) : ''; ?>" required>
        </div>
        
        <div class="centralizar">
            <input type="submit" value="Guardar Cambios" class="btn light">
        </div>
    </div>
</form>
