<h2>Reportes</h2>

<div class="box-container more_space">
    <a class="btn light" href="/Empenio/admin/views/empenios/reporte_excel/reporte_transaccion.php">Reporte de Transacciones</a>
    <a class="btn light" href="/Empenio/admin/views/empenios/reporte_excel/reporte_empenio_empleado.php">Reporte de Empeños por Empleado</a>
    <a class="btn light" href="/Empenio/admin/views/empenios/reporte_excel/reporte_cliente_moroso.php">Reporte de Cliente Moroso</a>
</div>

<div class="chart-container">

  <canvas id="graficoClientes" width="200" height="200"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-colorschemes"></script>

<script>
  const etiquetasSucursal = <?= json_encode($labels) ?>;
  const datosSucursal = <?= json_encode($valores) ?>;
  const colores =  [
      'rgb(255, 99, 133)',
      'rgb(255, 160, 64)',
      'rgb(255, 204, 86)',
      'rgb(75, 192, 192)',
      'rgb(54, 163, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)'
    ];

  const ctx = document.getElementById('graficoClientes').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: etiquetasSucursal,
      datasets: [
        {
          label: 'Clientes Por Sucursal',
          data: datosSucursal,
          borderWidth: 1,
          backgroundColor: colores.slice(0, datosSucursal.length)
        }
      ]
    },
    options: {
      plugins: {
        colorschemes: {legend: {display: false}}
      }
    }
  });
</script>

<canvas id="graficoClientes2" width="200" height="200"></canvas>

<script>
  const etiquetasEstados = <?= json_encode($labels2) ?>;
  const datosEstados = <?= json_encode($valores2) ?>;

  const datos2 = [10, 8, 6];
  const ctx2 = document.getElementById('graficoClientes2').getContext('2d');
  new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: etiquetasEstados,
      datasets: [{
        label: 'Clientes',
        data: datosEstados,
        borderWidth: 3
      }]
    },
options: {
    plugins: {
      colorschemes: {
        scheme: 'brewer.Paired12' // Puedes elegir otro esquema
      }
    }
  }
  });
</script>
</div>

