// estadisticas.js
$(function () {
  $.get('./backend/stats-downloads.php', function (resp) {
    console.log('Stats resp:', resp);

    let data;
    try {
      data = (typeof resp === 'string') ? JSON.parse(resp) : resp;
    } catch (e) {
      console.error('Error parseando JSON de estadísticas:', resp, e);
      return;
    }

    if (data.status !== 'success') {
      console.error('Error en stats:', data.message);
      return;
    }

    // ---------- Gráfica 1: día ----------
    const ctxDia = document.getElementById('chartDiaSemana');
    if (ctxDia) {
      new Chart(ctxDia, {
        type: 'bar',
        data: {
          labels: data.por_dia.labels,
          datasets: [{
            label: 'Descargas',
            data: data.por_dia.data
          }]
        }
      });
    }

    // ---------- Gráfica 2: hora ----------
    const ctxHora = document.getElementById('chartHora');
    if (ctxHora) {
      new Chart(ctxHora, {
        type: 'line',
        data: {
          labels: data.por_hora.labels,
          datasets: [{
            label: 'Descargas',
            data: data.por_hora.data
          }]
        }
      });
    }

    // ---------- Gráfica 3: tipo ----------
    const ctxTipo = document.getElementById('chartMarca');
    if (ctxTipo) {
      new Chart(ctxTipo, {
        type: 'pie',
        data: {
          labels: data.por_tipo.labels,
          datasets: [{
            label: 'Descargas',
            data: data.por_tipo.data
          }]
        }
      });
    }

  });
});
