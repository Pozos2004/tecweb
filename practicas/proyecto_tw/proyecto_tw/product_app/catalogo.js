// catalogo.js
$(function () {

  function cargar() {
    $.get('./backend/product-list.php', function (resp) {
      let recursos = (typeof resp === 'string') ? JSON.parse(resp) : resp;
      let html = '';

      recursos.forEach(r => {
        html += `
          <div class="col-md-4 mb-3">
            <div class="card recurso-card h-100">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">${r.titulo}</h5>
                <p><strong>Lenguaje:</strong> ${r.lenguaje}</p>
                <p><strong>Tipo:</strong> ${r.tipo_recurso}</p>

                <button class="btn btn-warning mt-auto descargar"
                        data-id="${r.id}">
                  Descargar
                </button>
              </div>
            </div>
          </div>
        `;
      });

      $('#listaRecursos').html(html);
    });
  }

  cargar();

  //  DESCARGA REAL SIN AJAX
  $(document).on('click', '.descargar', function () {
    const id = $(this).data('id');

    // Redirige al backend -> descarga real
    window.location.href = "./backend/product-download.php?id=" + id;
  });

});

// DESCARGA DIRECTA REAL
$(document).on('click', '.descargar', function (e) {
    e.preventDefault();
    const id = $(this).data('id');

    // Abrimos la URL que genera la descarga
    window.location.href = './backend/product-download.php?id=' + id;
});
