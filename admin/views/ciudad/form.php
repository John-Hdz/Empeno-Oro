<h2><?php echo isset($ciudad) ? 'Modificar Ciudad' : 'Crear Ciudad'; ?></h2>

<form action="admin_ciudad.php?accion=<?php echo isset($ciudad) ? 'modificar&id=' . e($ciudad['id_ciudad']) : 'crear'; ?>" method="post">
    <?php echo csrf_field(); ?>

    <div class="box-container more_space">
        <div class="box-group centralizar">
            <label for="ciudad">ciudad</label>
            <input type="text" id="ciudad" name="ciudad" value="<?php echo isset($ciudad) ? e($ciudad['ciudad']) : ''; ?>" required>
        </div>
        <div class="box-group centralizar">
            <label for="id_estado">Estado</label>
            <select id="id_estado" name="id_estado" required>
                <option value="">Seleccione un estado</option>
                <?php foreach ($estados as $estado): ?>
                    <option value="<?php echo e($estado['id_estado']); ?>"
                        <?php echo isset($ciudad) && $ciudad['id_estado'] == $estado['id_estado'] ? 'selected' : ''; ?>>
                        <?php echo e($estado['estado']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="box-container">
        <div class="box-group centralizar">
            <input type="submit" value="Guardar" class="btn light">
        </div>
    </div>
</form>
