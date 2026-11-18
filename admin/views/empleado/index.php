<h2>Empleados</h2>

    <div class="centralizar more_space">
        <a href="admin_empleado.php?accion=crear" class="btn dark">+ Nuevo Empleado</a>
    </div>

<?php foreach($empleados as $empleado): ?>

<div class="box-container ">
    <div class="box-group-large centralizar">
    <label>Primer Apellido</label>
        <div class="borde_negro">
            <?php echo e($empleado['primer_apellido']); ?>
        </div>
    </div>
    <div class="box-group-large centralizar">
    <label>Segundo Apellido</label>
        <div class="borde_negro">
            <?php echo e($empleado['segundo_apellido']); ?>
        </div>
    </div>
    <div class="box-group-large centralizar">
    <label>Nombre</label>
        <div class="borde_negro">
            <?php echo e($empleado['nombre']); ?>
        </div>
    </div>
</div>

<div class="box-container">
    <div class="box-group-large centralizar">
    <label>Correo</label>
        <div class="borde_negro">
            <?php echo e($empleado['correo']); ?>
        </div>
    </div>
</div>

<div class="box-container">
    <div class="box-group centralizar">
        <label>RFC</label>
        <div class="borde_negro">
            <?php echo e($empleado['rfc']); ?>
        </div>
    </div>
    <div class="box-group-large centralizar">
        <label>CURP</label>
        <div class="borde_negro">
            <?php echo e($empleado['curp']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
        <label for="sucursal">ID Sucursal</label>
        <div class="borde_negro">
            <?php echo e($empleado['id_sucursal']); ?>
        </div>

    </div>

</div>

<div class="box-container">
    <div class="box-group centralizar">
    <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_empleado.php?accion=modificar&id=<?php echo e($empleado['id_empleado']); ?>" class="btn light">Modificar</a>
            <form action="admin_empleado.php?accion=eliminar&id=<?php echo e($empleado['id_empleado']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este empleado?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn dark">Eliminar</button>
            </form>
        </div>
    </div>
</div>

<?php endforeach; ?>
