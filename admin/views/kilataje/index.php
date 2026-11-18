<h2>kilatajes Disponibles</h2>

    <div class="centralizar more_space">
        <a href="admin_kilataje.php?accion=crear" class="btn dark">+ Nuevo Kilataje</a>
    </div>

<?php foreach($kilatajes as $kilataje): ?>

<div class="box-container">
    <div class="box-group centralizar">
    <label>Precio por gramo</label>
        <div class="borde_negro">
            $<?php echo e($kilataje['precio_gramo']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Kilataje</label>
        <div class="borde_negro">
            <?php echo e($kilataje['kilataje']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_kilataje.php?accion=modificar&id=<?php echo e($kilataje['id_kilataje']); ?>" class="btn light">Modificar</a>

        <a href="#" class="btn dark" onclick="confirmarEliminacion(<?php echo (int) $kilataje['id_kilataje']; ?>); return false;">Eliminar</a>
        </div>
    </div>

</div>

<?php endforeach; ?>

<script>
    function confirmarEliminacion(id) {
        showModal("Confirmar eliminación", "¿Estás seguro de que deseas eliminar este kilataje?", [
            { texto: "Cancelar", clase: "btn-light" },
            { texto: "Eliminar", clase: "btn-danger", accion: () => {
                submitPostAction(`admin_kilataje.php?accion=eliminar&id=${id}`);
            }}
        ]);
    }
</script>
