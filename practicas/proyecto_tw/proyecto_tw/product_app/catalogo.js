$(function () {

  function cargar() {
    $.get('./backend/product-list.php', function (resp) {
      try {
        let productos = JSON.parse(resp);
        let html = '';

        productos.forEach(p => {
          html += `
            <div class="col-md-4 mb-3">
              <div class="card h-100">
                <img src="${p.imagen}" class="card-img-top" style="height:160px;object-fit:cover" alt="">
                <div class="card-body">
                  <h5>${p.nombre}</h5>
                  <p class="mb-1">Marca: ${p.marca}</p>
                  <p class="mb-1">Modelo: ${p.modelo}</p>
                  <p class="mb-1">Precio: $${p.precio}</p>
                  <a href="#" class="btn btn-primary descargar" data-id="${p.id}">Descargar</a>
                </div>
              </div>
            </div>
          `;
        });

        $('#listaRecursos').html(html);
      } catch (e) {
        console.error('Respuesta no JSON', resp);
        $('#listaRecursos').html('<div class="col-12">Error al cargar productos.</div>');
      }
    });
  }

  cargar();

  $(document).on('click', '.descargar', function (e) {
    e.preventDefault();
    const id = $(this).data('id');

    $.post('./backend/product-download.php', { producto_id: id }, function (resp) {
      console.log('Respuesta descarga:', resp);
      try {
        let r = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        alert(r.message);
      } catch (e) {
        console.error('Error parseando JSON', resp);
        alert('Error al registrar descarga');
      }
    });
  });

});
