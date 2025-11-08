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
    
    // Eventos para validación cuando se pierde el foco
    $('#name').on('blur', validarNombre);
    $('#precio').on('blur', validarPrecio);
    $('#unidades').on('blur', validarUnidades);
    $('#modelo').on('blur', validarModelo);
    $('#marca').on('blur', validarMarca);
    $('#detalles').on('blur', validarDetalles);
    $('#imagen').on('blur', validarImagen);
    
    // Evento para verificar nombre único
    $('#name').on('input', verificarNombreUnico);
    
    // PUNTO 2: Evento para cambiar texto del botón al hacer clic en un producto
    $(document).on('click', '.product-item', (e) => {
        $('button.btn-primary').text("Modificar Producto");
    });
});

function init() {
    // Llenar formulario con valores por defecto
    $("#precio").val(baseJSON.precio);
    $("#unidades").val(baseJSON.unidades);
    $("#modelo").val(baseJSON.modelo);
    $("#marca").val(baseJSON.marca);
    $("#detalles").val(baseJSON.detalles);
    $("#imagen").val(baseJSON.imagen);

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

                    // AGREGAR CLASE product-item A LA FILA
                    template += `
                        <tr productId="${producto.id}" class="product-item">
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

                    // AGREGAR CLASE product-item A LA FILA
                    template += `
                        <tr productId="${producto.id}" class="product-item">
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
                $("#precio").val(producto.precio);
                $("#unidades").val(producto.unidades);
                $("#modelo").val(producto.modelo);
                $("#marca").val(producto.marca);
                $("#detalles").val(producto.detalles);
                $("#imagen").val(producto.imagen);
                $("#productId").val(producto.id);
                
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
    
    // Restaurar valores por defecto
    $("#precio").val(baseJSON.precio);
    $("#unidades").val(baseJSON.unidades);
    $("#modelo").val(baseJSON.modelo);
    $("#marca").val(baseJSON.marca);
    $("#detalles").val(baseJSON.detalles);
    $("#imagen").val(baseJSON.imagen);
    
    // Limpiar estados de validación
    $('.validation-status').hide();
    
    mostrarMensaje('info', 'Edición cancelada');
}

// FUNCIÓN CALLBACK DE BOTÓN "Agregar/Actualizar Producto"
function agregarProducto(e) {
    e.preventDefault();

    // PUNTO 3: Cambiar texto del botón al enviar el formulario
    $('button.btn-primary').text("Agregar Producto");

    // Crear objeto JSON con los datos del formulario
    var finalJSON = {
        nombre: $("#name").val(),
        precio: parseFloat($("#precio").val()),
        unidades: parseInt($("#unidades").val()),
        modelo: $("#modelo").val(),
        marca: $("#marca").val(),
        detalles: $("#detalles").val(),
        imagen: $("#imagen").val()
    };

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
    }

    $.ajax({
        url: url,
        type: method,
        data: JSON.stringify(finalJSON),
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
                    // Restaurar valores por defecto
                    $("#precio").val(baseJSON.precio);
                    $("#unidades").val(baseJSON.unidades);
                    $("#modelo").val(baseJSON.modelo);
                    $("#marca").val(baseJSON.marca);
                    $("#detalles").val(baseJSON.detalles);
                    $("#imagen").val(baseJSON.imagen);
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
    // Validar nombre
    if (!producto.nombre || producto.nombre.trim() === '') {
        mostrarEstadoValidacion('name', false, 'El nombre del producto es requerido');
        return false;
    }
    
    // Validar precio
    if (isNaN(producto.precio) || producto.precio < 0) {
        mostrarEstadoValidacion('precio', false, 'El precio debe ser un número válido mayor o igual a 0');
        return false;
    }
    
    // Validar unidades
    if (isNaN(producto.unidades) || producto.unidades < 0 || !Number.isInteger(Number(producto.unidades))) {
        mostrarEstadoValidacion('unidades', false, 'Las unidades deben ser un número entero válido mayor o igual a 0');
        return false;
    }
    
    // Validar modelo
    if (!producto.modelo || producto.modelo.trim() === '') {
        mostrarEstadoValidacion('modelo', false, 'El modelo es requerido');
        return false;
    }
    
    // Validar marca
    if (!producto.marca || producto.marca.trim() === '') {
        mostrarEstadoValidacion('marca', false, 'La marca es requerida');
        return false;
    }
    
    // Validar detalles
    if (!producto.detalles || producto.detalles.trim() === '') {
        mostrarEstadoValidacion('detalles', false, 'Los detalles son requeridos');
        return false;
    }
    
    // Validar imagen
    if (!producto.imagen || producto.imagen.trim() === '') {
        mostrarEstadoValidacion('imagen', false, 'La imagen es requerida');
        return false;
    }
    
    return true;
}

// FUNCIONES DE VALIDACIÓN POR CAMPO
function validarNombre() {
    var nombre = $("#name").val().trim();
    var statusElement = $("#name-status");
    
    if (nombre === '') {
        mostrarEstadoValidacion('name', false, 'El nombre del producto es requerido');
        return false;
    }
    
    mostrarEstadoValidacion('name', true, 'Nombre válido');
    return true;
}

function validarPrecio() {
    var precio = parseFloat($("#precio").val());
    var statusElement = $("#precio-status");
    
    if (isNaN(precio) || precio < 0) {
        mostrarEstadoValidacion('precio', false, 'El precio debe ser un número válido mayor o igual a 0');
        return false;
    }
    
    mostrarEstadoValidacion('precio', true, 'Precio válido');
    return true;
}

function validarUnidades() {
    var unidades = parseInt($("#unidades").val());
    var statusElement = $("#unidades-status");
    
    if (isNaN(unidades) || unidades < 0 || !Number.isInteger(unidades)) {
        mostrarEstadoValidacion('unidades', false, 'Las unidades deben ser un número entero válido mayor o igual a 0');
        return false;
    }
    
    mostrarEstadoValidacion('unidades', true, 'Unidades válidas');
    return true;
}

function validarModelo() {
    var modelo = $("#modelo").val().trim();
    var statusElement = $("#modelo-status");
    
    if (modelo === '') {
        mostrarEstadoValidacion('modelo', false, 'El modelo es requerido');
        return false;
    }
    
    mostrarEstadoValidacion('modelo', true, 'Modelo válido');
    return true;
}

function validarMarca() {
    var marca = $("#marca").val().trim();
    var statusElement = $("#marca-status");
    
    if (marca === '') {
        mostrarEstadoValidacion('marca', false, 'La marca es requerida');
        return false;
    }
    
    mostrarEstadoValidacion('marca', true, 'Marca válida');
    return true;
}

function validarDetalles() {
    var detalles = $("#detalles").val().trim();
    var statusElement = $("#detalles-status");
    
    if (detalles === '') {
        mostrarEstadoValidacion('detalles', false, 'Los detalles son requeridos');
        return false;
    }
    
    mostrarEstadoValidacion('detalles', true, 'Detalles válidos');
    return true;
}

function validarImagen() {
    var imagen = $("#imagen").val().trim();
    var statusElement = $("#imagen-status");
    
    if (imagen === '') {
        mostrarEstadoValidacion('imagen', false, 'La imagen es requerida');
        return false;
    }
    
    mostrarEstadoValidacion('imagen', true, 'Imagen válida');
    return true;
}

// FUNCIÓN PARA VERIFICAR NOMBRE ÚNICO
function verificarNombreUnico() {
    var nombre = $("#name").val().trim();
    var statusElement = $("#name-status");
    
    if (nombre === '') {
        return;
    }
    
    // Si estamos en modo edición, no verificar el nombre único
    if (editMode) {
        return;
    }
    
    mostrarEstadoValidacion('name', 'checking', 'Verificando disponibilidad del nombre...');
    
    $.ajax({
        url: './backend/product-search.php',
        type: 'GET',
        data: { search: nombre },
        dataType: 'json',
        success: function(productos) {
            if (productos.length > 0) {
                mostrarEstadoValidacion('name', false, 'Ya existe un producto con este nombre');
            } else {
                mostrarEstadoValidacion('name', true, 'Nombre disponible');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al verificar nombre:', error);
            mostrarEstadoValidacion('name', false, 'Error al verificar disponibilidad del nombre');
        }
    });
}

// FUNCIÓN PARA MOSTRAR ESTADO DE VALIDACIÓN
function mostrarEstadoValidacion(campo, estado, mensaje) {
    var statusElement = $("#" + campo + "-status");
    
    statusElement.text(mensaje).show();
    statusElement.removeClass('status-valid status-invalid status-checking');
    
    if (estado === true) {
        statusElement.addClass('status-valid');
    } else if (estado === false) {
        statusElement.addClass('status-invalid');
    } else if (estado === 'checking') {
        statusElement.addClass('status-checking');
    }
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