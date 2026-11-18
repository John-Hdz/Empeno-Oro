<h1>Empeños</h1>
<?php if($web->checar_permiso('Empeñar')): ?>

    <div class="actions-empenio-container">
        <div class="actions-empenio">
            <a class="btn light" href="admin_empenios.php?accion=reporte">Reporte</a>
            <a class="btn dark" href="#" onclick="seleccionarCliente(); return false;">Registrar Empeño</a>
                <script>
                    function seleccionarCliente()
                    {
                        showModal("Registrar para Cliente", "¿Deseas seleccionar un cliente existente o registrar uno nuevo?",
                        [
                            { texto: "Seleccionar Cliente existente", clase: "btn-dark", accion: () =>
                                {
                                    window.location.href = `admin_empenios.php?accion=seleccionar_cliente`;
                                }
                            },
                            { texto: "Registrar un nuevo Cliente", clase: "btn-primary", accion: () =>
                                {
                                    window.location.href = `admin_cliente.php?accion=crear`;
                                }
                            }
                        ]);
                    }
                </script>
        <div class="searchBy">
            <form action="admin_empenios.php" method="get" class="search-form">
                <input type="hidden" name="accion" value="buscarEmpeños">

                <input type="text" name="valorBusqueda" placeholder="Buscar..." required>

                <select name="tipoBusqueda" id="tipoBusqueda">
                <option value="cliente">Nombre del Cliente</option>
                <option value="numero_credito">Numero de Credito</option>
                <option value="cliente_unico">Cliente Único</option>
                </select>

                <button type="submit" class="btn light">Buscar</button>
        </form>
</div>

    </div>
    </div>

<?php endif; ?>

<?php foreach ($empenios as $empenio): ?>
    <div class="accordion-item">
        <div class="accordion-header">
            <span class="number"><?php echo e($empenio['numero_credito']); ?></span>
                Boleta
            <button class="toggle-btn">+</button>
        </div>
        <div class="accordion-content">
            <?php require(__DIR__ . '/info.php'); ?>
            <div class="box-container centralizar">
                <div class="buttons">
                    <a class="btn btn-primary" href="admin_empenios.php?accion=imprimirBoleta&id_boleta=<?php echo e($empenio['id_boleta']); ?>">Imprimir</a>
                    <?php if($web->checar('Cliente')): ?>
                        <a class="btn light" href="admin_empenios.php?accion=pago_refrendo&id_boleta=<?php echo e($empenio['id_boleta']); ?>">Pagar Refrendo</a>
                        <a class="btn light" href="admin_empenios.php?accion=pago_liquidar&id_boleta=<?php echo e($empenio['id_boleta']); ?>">Liquidar</a>
                    <?php endif; ?>
                    <?php if($web->checar_permiso('Empeñar')): ?>
                        <a class="btn dark" href="admin_empenios.php?accion=modificar&id_boleta=<?php echo e($empenio['id_boleta']); ?>">Modificar</a>
                        <form action="admin_empenios.php?accion=eliminar&id_boleta=<?php echo e($empenio['id_boleta']); ?>" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este empeño?');">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
