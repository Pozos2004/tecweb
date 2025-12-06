// admin.js
$(function () {

  let listaRecursos = [];

  // ================================
  // Cargar recursos de backend
  // ================================
  function cargarArchivos() {
    $.get('./backend/product-list.php', function (resp) {
      try {
        listaRecursos = (typeof resp === 'string') ? JSON.parse(resp) : resp;
      } catch (e) {
        console.error("Error parseando:", resp);
        listaRecursos = [];
      }

      const f = $('#search').val();
      renderTabla(f);
    });
  }

  // ================================
  // Pintar tabla con búsqueda
  // ================================
  function renderTabla(filtro) {
    const term = (filtro || "").toLowerCase();
    let html = "";

    const filtrados = listaRecursos.filter(r => {
      if (!term) return true;
      return (
        String(r.id).includes(term) ||
        (r.titulo || "").toLowerCase().includes(term) ||
        (r.descripcion || "").toLowerCase().includes(term) ||
        (r.lenguaje || "").toLowerCase().includes(term) ||
        (r.tipo_recurso || "").toLowerCase().includes(term)
      );
    });

    filtrados.forEach(r => {
      html += `
        <tr>
          <td>${r.id}</td>

          <!-- 👇 TÍTULO EDITABLE (link) -->
          <td><a href="#" class="link-editar" data-id="${r.id}">${r.titulo}</a></td>

          <td>${r.lenguaje || ''}</td>
          <td>${r.tipo_recurso || ''}</td>
          <td>${r.tipo_archivo || ''}</td>

          <td>
            <button class="btn btn-sm btn-danger eliminar" data-id="${r.id}">
              Eliminar
            </button>
          </td>
        </tr>
      `;
    });

    if (html === "") {
      html = `<tr><td colspan="6">No hay resultados</td></tr>`;
    }

    $("#products").html(html);
  }

  // ================================
  // Buscador en vivo
  // ================================
  $("#search").on("keyup", function () {
    renderTabla($(this).val());
  });

  // ================================
  // Guardar recurso (nuevo / editar)
  // ================================
  $("#product-form").on("submit", function (e) {
    e.preventDefault();

    let form = document.getElementById("product-form");
    let fd = new FormData(form);

    $.ajax({
      url: "./backend/product-add.php",
      method: "POST",
      data: fd,
      processData: false,
      contentType: false,

      success: function (resp) {
        let r;
        try {
          r = JSON.parse(resp);
        } catch {
          $("#msg-recurso").text("Error en servidor");
          return;
        }

        $("#msg-recurso").text(r.message);

        if (r.status === "success") {
          form.reset();
          $("#productId").val("");
          cargarArchivos();
        }
      }
    });
  });

  // ================================
  // EDITAR haciendo clic en el TÍTULO
  // ================================
  $(document).on("click", ".link-editar", function (e) {
    e.preventDefault();
    const id = $(this).data("id");

    const r = listaRecursos.find(x => x.id == id);
    if (!r) return;

    $("#productId").val(r.id);
    $("#titulo").val(r.titulo);
    $("#descripcion").val(r.descripcion);
    $("#lenguaje").val(r.lenguaje);
    $("#tipo_recurso").val(r.tipo_recurso);

    $("#msg-recurso").text("Editando recurso ID " + r.id);

    $("html, body").animate({
      scrollTop: $("#product-form").offset().top - 80
    }, 400);
  });

  // ================================
  // ELIMINAR
  // ================================
  $(document).on("click", ".eliminar", function () {
    const id = $(this).data("id");

    if (!confirm("¿Eliminar recurso?")) return;

    $.post("./backend/product-delete.php", { id }, function (resp) {
      let r;
      try {
        r = JSON.parse(resp);
      } catch {
        alert("Error en servidor");
        return;
      }

      alert(r.message);

      if (r.status === "success") cargarArchivos();
    });
  });

  // Carga inicial
  cargarArchivos();

});
