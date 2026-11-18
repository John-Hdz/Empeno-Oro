<h2>Clientes</h2>

    <div class="centralizar more_space">
        <a href="admin_cliente.php?accion=crear" class="btn dark">+ Nuevo Cliente</a>
    </div>

<?php foreach($clientes as $cliente): ?>

<div class="box-container">
    <div class="box-group centralizar">
    <label>Primer Apellido</label>
        <div class="borde_negro">
            <?php echo e($cliente['primer_apellido']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Segundo Apellido</label>
        <div class="borde_negro">
            <?php echo e($cliente['segundo_apellido']); ?>
        </div>
    </div>
    <div class="box-group centralizar">
    <label>Nombre</label>
        <div class="borde_negro">
            <?php echo e($cliente['nombre']); ?>
        </div>
    </div>

    <div class="box-group-large centralizar">
    <label>Correo</label>
        <div class="borde_negro">
            <?php echo e($cliente['correo']); ?>
        </div>
    </div>

    <div class="box-group-large centralizar">
    <label>CURP</label>
        <div class="borde_negro">
            <?php echo e($cliente['curp']); ?>
        </div>
    </div>

    <div class="box-group centralizar">
    <label>telefono</label>
        <div class="borde_negro">
            <?php echo e($cliente['telefono']); ?>
        </div>
    </div>

    <div class="box-group centralizar">
    <label>Dirección</label>
        <div class="borde_negro">
            <?php echo e($cliente['direccion']); ?>
        </div>
    </div>

    <div class="box-group centralizar">
    <label>Codigo Postal</label>
        <div class="borde_negro">
            <?php echo e($cliente['codigo_postal']); ?>
        </div>
    </div>
</div>

<div class="box-container">
    <div class="box-group centralizar">
    <label>Opciones</label>
        <div class="buttons centralizar">
            <a href="admin_cliente.php?accion=modificar&id=<?php echo e($cliente['cliente_unico']); ?>" class="btn light">Modificar</a>
            <form action="admin_cliente.php?accion=eliminar&id=<?php echo e($cliente['cliente_unico']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn dark">Eliminar</button>
            </form>
        </div>
    </div>
</div>

<div class="more_space"></div>
<?php endforeach; ?>
