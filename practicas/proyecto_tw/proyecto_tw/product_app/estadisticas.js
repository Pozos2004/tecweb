$(function(){
  $.get('./backend/stats-downloads.php', function(resp){
    console.log('Stats resp:', resp);

    let data;
    try {
      data = (typeof resp === 'string') ? JSON.parse(resp) : resp;
    } catch(e) {
      console.error('No es JSON válido', resp);
      return;
    }

    // 1) Por día de la semana
    const dias = data.por_dia_semana.map(r => r.dia);
    const totalDias = data.por_dia_semana.map(r => r.total);

    const ctxDia = document.getElementById('chartDiaSemana').getContext('2d');
    new Chart(ctxDia, {
      type: 'bar',
      data: {
        labels: dias,
        datasets: [{
          label: 'Descargas',
          data: totalDias
        }]
      }
    });

    // 2) Por hora
    const horas = data.por_hora.map(r => r.hora);
    const totalHoras = data.por_hora.map(r => r.total);

    const ctxHora = document.getElementById('chartHora').getContext('2d');
    new Chart(ctxHora, {
      type: 'line',
      data: {
        labels: horas,
        datasets: [{
          label: 'Descargas',
          data: totalHoras
        }]
      }
    });

    // 3) Por marca
    const marcas = data.por_marca.map(r => r.marca);
    const totalMarcas = data.por_marca.map(r => r.total);

    const ctxMarca = document.getElementById('chartMarca').getContext('2d');
    new Chart(ctxMarca, {
      type: 'pie',
      data: {
        labels: marcas,
        datasets: [{
          label: 'Descargas',
          data: totalMarcas
        }]
      }
    });
  });
});
