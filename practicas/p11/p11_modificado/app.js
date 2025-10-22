// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

var editMode = false;
var currentEditId = null;

$(document).ready(function() {
    init();
    
    // EVENTOS CON JQUERY
    $('#search-form').on('submit', buscarProducto);
    $('#product-form').on('submit', agregarProducto);
    $('#btn-cancel').on('click', cancelarEdicion);
});

function init() {
    // Convierte el JSON a string para poder mostrarlo
    var JsonString = JSON.stringify(baseJSON, null, 2);
    $("#description").val(JsonString);

    // SE LISTAN TODOS LOS PRODUCTOS
    listarProductos();
}

// FUNCIÓN CALLBACK AL CARGAR LA PÁGINA O AL AGREGAR UN PRODUCTO
function listarProductos() {
    $.ajax({
        url: './backend/product-list.php',
        type: 'GET',
        dataType: 'json',
        success: function(productos) {
            if (productos.length > 0) {
                let template = '';

                productos.forEach(producto => {
                    let descripcion = '';
                    descripcion += '<li>precio: ' + producto.precio + '</li>';
                    descripcion += '<li>unidades: ' + producto.unidades + '</li>';
                    descripcion += '<li>modelo: ' + producto.modelo + '</li>';
                    descripcion += '<li>marca: ' + producto.marca + '</li>';
                    descripcion += '<li>detalles: ' + producto.detalles + '</li>';

                    template += `
                        <tr productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="btn btn-warning btn-sm product-edit" data-id="${producto.id}">
                                    Editar
                                </button>
                                <button class="btn btn-danger btn-sm product-delete" data-id="${producto.id}">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                $("#products").html(template);
                
                // Agregar eventos a los botones dinámicos
                $('.product-edit').on('click', editarProducto);
                $('.product-delete').on('click', eliminarProducto);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al listar productos:', error);
            mostrarMensaje('error', 'Error al cargar los productos');
        }
    });
}

// FUNCIÓN CALLBACK DE BOTÓN "Buscar"
function buscarProducto(e) {
    e.preventDefault();

    var search = $("#search").val();

    $.ajax({
        url: './backend/product-search.php',
        type: 'GET',
        data: { search: search },
        dataType: 'json',
        success: function(productos) {
            if (productos.length > 0) {
                let template = '';
                let template_bar = '';

                productos.forEach(producto => {
                    let descripcion = '';
                    descripcion += '<li>precio: ' + producto.precio + '</li>';
                    descripcion += '<li>unidades: ' + producto.unidades + '</li>';
                    descripcion += '<li>modelo: ' + producto.modelo + '</li>';
                    descripcion += '<li>marca: ' + producto.marca + '</li>';
                    descripcion += '<li>detalles: ' + producto.detalles + '</li>';

                    template += `
                        <tr productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="btn btn-warning btn-sm product-edit" data-id="${producto.id}">
                                    Editar
                                </button>
                                <button class="btn btn-danger btn-sm product-delete" data-id="${producto.id}">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;

                    template_bar += `<li>${producto.nombre}</li>`;
                });

                $("#product-result").removeClass("d-none").addClass("d-block");
                $("#container").html(template_bar);
                $("#products").html(template);
                
                // Agregar eventos a los botones dinámicos
                $('.product-edit').on('click', editarProducto);
                $('.product-delete').on('click', eliminarProducto);
            } else {
                mostrarMensaje('info', 'No se encontraron productos');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al buscar productos:', error);
            mostrarMensaje('error', 'Error al buscar productos');
        }
    });
}

// FUNCIÓN PARA EDITAR PRODUCTO
function editarProducto() {
    var productId = $(this).data('id');
    
    $.ajax({
        url: './backend/product-get.php',
        type: 'GET',
        data: { id: productId },
        dataType: 'json',
        success: function(producto) {
            if (producto) {
                // Activar modo edición
                editMode = true;
                currentEditId = productId;
                
                // Llenar el formulario con los datos del producto
                $("#name").val(producto.nombre);
                $("#productId").val(producto.id);
                
                // Crear JSON para edición
                var productJSON = {
                    precio: producto.precio,
                    unidades: producto.unidades,
                    modelo: producto.modelo,
                    marca: producto.marca,
                    detalles: producto.detalles,
                    imagen: producto.imagen
                };
                
                $("#description").val(JSON.stringify(productJSON, null, 2));
                
                // Cambiar texto del botón
                $("#btn-submit").text('Actualizar Producto').removeClass('btn-primary').addClass('btn-success');
                $("#btn-cancel").removeClass('d-none');
                
                mostrarMensaje('info', 'Modo edición activado. Modifica los datos y haz clic en "Actualizar Producto"');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar producto:', error);
            mostrarMensaje('error', 'Error al cargar el producto para edición');
        }
    });
}

// FUNCIÓN PARA CANCELAR EDICIÓN
function cancelarEdicion() {
    editMode = false;
    currentEditId = null;
    
    // Limpiar formulario
    $("#product-form")[0].reset();
    $("#productId").val('');
    
    // Restaurar botones
    $("#btn-submit").text('Agregar Producto').removeClass('btn-success').addClass('btn-primary');
    $("#btn-cancel").addClass('d-none');
    
    // Restaurar JSON base
    $("#description").val(JSON.stringify(baseJSON, null, 2));
    
    mostrarMensaje('info', 'Edición cancelada');
}

// FUNCIÓN CALLBACK DE BOTÓN "Agregar/Actualizar Producto"
function agregarProducto(e) {
    e.preventDefault();

    var productoJsonString = $("#description").val();
    var finalJSON = JSON.parse(productoJsonString);
    finalJSON['nombre'] = $("#name").val();
    productoJsonString = JSON.stringify(finalJSON, null, 2);

    // VALIDACIONES BÁSICAS
    if (!validarProducto(finalJSON)) {
        return;
    }

    var url = './backend/product-add.php';
    var method = 'POST';
    
    if (editMode) {
        url = './backend/product-update.php';
        method = 'POST';
        finalJSON.id = currentEditId;
        productoJsonString = JSON.stringify(finalJSON, null, 2);
    }

    $.ajax({
        url: url,
        type: method,
        data: productoJsonString,
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        success: function(respuesta) {
            if (respuesta.status === 'success') {
                mostrarMensaje('success', respuesta.message);
                
                if (editMode) {
                    // Salir del modo edición después de actualizar exitosamente
                    cancelarEdicion();
                } else {
                    // Limpiar formulario después de agregar exitosamente
                    $("#product-form")[0].reset();
                    $("#description").val(JSON.stringify(baseJSON, null, 2));
                }
                
                listarProductos();
            } else {
                mostrarMensaje('error', respuesta.message);
                
                // EDICIÓN FALLIDA - Mantener en modo edición
                if (editMode) {
                    mostrarMensaje('error', 'Edición fallida: ' + respuesta.message + '. Puedes corregir los datos e intentar nuevamente.');
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            mostrarMensaje('error', 'Error de conexión: ' + error);
            
            // EDICIÓN FALLIDA - Mantener en modo edición
            if (editMode) {
                mostrarMensaje('error', 'Edición fallida por error de conexión. Puedes intentar nuevamente.');
            }
        }
    });
}

// FUNCIÓN CALLBACK DE BOTÓN "Eliminar"
function eliminarProducto() {
    var productId = $(this).data('id');
    var productRow = $(this).closest('tr');
    
    if (confirm("¿De verdad deseas eliminar el Producto?")) {
        $.ajax({
            url: './backend/product-delete.php',
            type: 'GET',
            data: { id: productId },
            dataType: 'json',
            success: function(respuesta) {
                if (respuesta.status === 'success') {
                    mostrarMensaje('success', respuesta.message);
                    productRow.fadeOut(300, function() {
                        $(this).remove();
                    });
                } else {
                    mostrarMensaje('error', respuesta.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al eliminar:', error);
                mostrarMensaje('error', 'Error al eliminar el producto');
            }
        });
    }
}

// FUNCIÓN PARA VALIDAR PRODUCTO
function validarProducto(producto) {
    if (!producto.nombre || producto.nombre.trim() === '') {
        mostrarMensaje('error', 'El nombre del producto es requerido');
        return false;
    }
    
    if (isNaN(producto.precio) || producto.precio < 0) {
        mostrarMensaje('error', 'El precio debe ser un número válido mayor o igual a 0');
        return false;
    }
    
    if (isNaN(producto.unidades) || producto.unidades < 0 || !Number.isInteger(Number(producto.unidades))) {
        mostrarMensaje('error', 'Las unidades deben ser un número entero válido mayor o igual a 0');
        return false;
    }
    
    return true;
}

// FUNCIÓN PARA MOSTRAR MENSAJES
function mostrarMensaje(tipo, mensaje) {
    let bgColor = '';
    switch(tipo) {
        case 'success':
            bgColor = 'bg-success';
            break;
        case 'error':
            bgColor = 'bg-danger';
            break;
        case 'info':
            bgColor = 'bg-info';
            break;
        default:
            bgColor = 'bg-secondary';
    }
    
    let template_bar = `
        <div class="alert ${bgColor} text-white">
            <strong>${tipo.toUpperCase()}:</strong> ${mensaje}
        </div>
    `;
    
    $("#product-result").removeClass("d-none").addClass("d-block");
    $("#container").html(template_bar);
    
    // Auto-ocultar mensajes después de 5 segundos (excepto errores)
    if (tipo !== 'error') {
        setTimeout(function() {
            $("#product-result").addClass("d-none").removeClass("d-block");
        }, 5000);
    }
}