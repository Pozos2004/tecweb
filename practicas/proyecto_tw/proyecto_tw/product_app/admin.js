$(document).ready(function(){
    let edit = false;

    // JSON BASE A MOSTRAR EN FORMULARIO
    var baseJSON = {
        "precio": 0.0,
        "unidades": 1,
        "modelo": "XX-000",
        "marca": "NA",
        "detalles": "NA",
        "imagen": "img/default.png"
    };

    let JsonString = JSON.stringify(baseJSON,null,2);
    $('#description').val(JsonString);
    $('#product-result').hide();
    listarProductos();

    //VALIDACIONES
    function validarNombre() {
    const nombre = $('#name').val().trim();
    return (nombre !== '' && nombre.length <= 100);
    }

    function validarMarca() {
    return ($('#marca').val() !== '');
    }

    function validarModelo() {
    const modelo = $('#modelo').val().trim();
    return /^[A-Za-z0-9\-]{1,25}$/.test(modelo);
    }

    function validarPrecio() {
    return ($('#precio').val() !== '' && parseFloat($('#precio').val()) > 99.99);
    }

    function validarDetalles() {
    const detalles = $('#detalles').val().trim();
    return (detalles.length <= 250);
    }

    function validarUnidades() {
    return ($('#unidades').val() !== '' && parseInt($('#unidades').val()) >= 0);
    }

    function validarImagen() {
    return true; 
    }

    function mostrarEstado(valido, ok, error) {
    $('#product-result').show();
    $('#container').html(`<li>${valido ? ok : error}</li>`);
    }

    $('#name').focus(() => mostrarEstado(validarNombre(), 'Nombre válido', 'Nombre incorrecto (requerido, máx 100 caracteres)'));
    $('#marca').focus(() => mostrarEstado(validarMarca(), 'Marca seleccionada', 'Debe seleccionar una marca'));
    $('#modelo').focus(() => mostrarEstado(validarModelo(), 'Modelo válido', 'Modelo alfanumérico, máx 25 caracteres'));
    $('#precio').focus(() => mostrarEstado(validarPrecio(), 'Precio válido', 'Debe ser mayor a 99.99'));
    $('#detalles').focus(() => mostrarEstado(validarDetalles(), 'Detalles válidos', 'Máx 250 caracteres'));
    $('#unidades').focus(() => mostrarEstado(validarUnidades(), 'Cantidad válida', 'Debe ser número ≥ 0'));

    function listarProductos() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
                const productos = JSON.parse(response);
            
                // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
                if(Object.keys(productos).length > 0) {
                    // SE CREA UNA PLANTILLA PARA CREAR LAS FILAS A INSERTAR EN EL DOCUMENTO HTML
                    let template = '';

                    productos.forEach(producto => {
                        // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                        let descripcion = '';
                        descripcion += '<li>precio: '+producto.precio+'</li>';
                        descripcion += '<li>unidades: '+producto.unidades+'</li>';
                        descripcion += '<li>modelo: '+producto.modelo+'</li>';
                        descripcion += '<li>marca: '+producto.marca+'</li>';
                        descripcion += '<li>detalles: '+producto.detalles+'</li>';
                    
                        template += `
                            <tr productId="${producto.id}">
                                <td>${producto.id}</td>
                                <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                <td><ul>${descripcion}</ul></td>
                                <td>
                                    <button class="product-delete btn btn-danger">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                    $('#products').html(template);
                }
            }
        });
}

$('#search').keyup(function() {

    if ($('#search').val()) {

        let search = $('#search').val();

        $.ajax({
            url: './backend/product-search.php?name=' + encodeURIComponent(search),
            type: 'POST',
            data: { name: search },
            success: function(response) {

                let productos = JSON.parse(response);

                // normalizar a array
                if (!Array.isArray(productos)) {
                    productos = [productos];
                }

                if (productos.length > 0) {

                    let template = '';
                    let template_bar = '';

                    productos.forEach(producto => {

                        let descripcion = `
                            <li>precio: ${producto.precio}</li>
                            <li>unidades: ${producto.unidades}</li>
                            <li>modelo: ${producto.modelo}</li>
                            <li>marca: ${producto.marca}</li>
                            <li>detalles: ${producto.detalles}</li>
                        `;

                        template += `
                            <tr productId="${producto.id}">
                                <td>${producto.id}</td>
                                <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                <td><ul>${descripcion}</ul></td>
                                <td>
                                    <button class="product-delete btn btn-danger">Eliminar</button>
                                </td>
                            </tr>
                        `;

                        template_bar += `<li>${producto.nombre}</li>`;
                    });

                    $('#product-result').show();
                    $('#container').html(template_bar);
                    $('#products').html(template);
                }
            }
        });

    } else {
        $('#product-result').hide();
    }
});

    $('#product-form').submit(e => {
        $('button.btn-primary').text("Agregar Producto");
        e.preventDefault();

        // SE CONVIERTE EL JSON DE STRING A OBJETO
        let postData = {
        nombre: $('#name').val(),
        marca: $('#marca').val(),
        modelo: $('#modelo').val(),
        precio: parseFloat($('#precio').val()),
        detalles: $('#detalles').val(),
        unidades: parseInt($('#unidades').val()),
        imagen: ($('#imagen').val() === '' ? 'img/default.png' : $('#imagen').val()),
        id: $('#productId').val()
        };

        /**
         * AQUÍ DEBES AGREGAR LAS VALIDACIONES DE LOS DATOS EN EL JSON
         * --> EN CASO DE NO HABER ERRORES, SE ENVIAR EL PRODUCTO A AGREGAR
         **/

        const url = edit === false ? './backend/product-add.php' : './backend/product-edit.php';
        
        if(!(validarNombre() && validarMarca() && validarModelo() && validarPrecio() &&
        validarDetalles() && validarUnidades())) {
        mostrarEstado(false, '', 'Corrige los campos marcados antes de continuar');
        return;
        }

        $.post(url, postData, (response) => {
            //console.log(response);
            // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
            let respuesta = JSON.parse(response);
            // SE CREA UNA PLANTILLA PARA CREAR INFORMACIÓN DE LA BARRA DE ESTADO
            let template_bar = '';
            template_bar += `
                        <li style="list-style: none;">status: ${respuesta.status}</li>
                        <li style="list-style: none;">message: ${respuesta.message}</li>
                    `;
            // SE REINICIA EL FORMULARIO
            $('#product-form')[0].reset();
            $('#productId').val('');
            $('#description').val(JsonString);
            $('button.btn-primary').text("Agregar Producto");

            $('#product-result').show();
            // SE INSERTA LA PLANTILLA PARA LA BARRA DE ESTADO
            $('#container').html(template_bar);
            // SE LISTAN TODOS LOS PRODUCTOS
            listarProductos();
            // SE REGRESA LA BANDERA DE EDICIÓN A false
            edit = false;
        });
    });

    $(document).on('click', '.product-delete', (e) => {
        if(confirm('¿Realmente deseas eliminar el producto?')) {
            const element = $(this)[0].activeElement.parentElement.parentElement;
            const id = $(element).attr('productId');
            $.post('./backend/product-delete.php', {id}, (response) => {
            let respuesta = JSON.parse(response);
            // SE CREA UNA PLANTILLA PARA CREAR INFORMACIÓN DE LA BARRA DE ESTADO
            let template_bar = '';
            template_bar += `
                        <li style="list-style: none;">status: ${respuesta.status}</li>
                        <li style="list-style: none;">message: ${respuesta.message}</li>
                    `;
            // SE REINICIA EL FORMULARIO
            $('#product-form')[0].reset();
            $('#productId').val('');
            $('#description').val(JsonString);
            $('button.btn-primary').text("Agregar Producto");

            $('#product-result').show();
            // SE INSERTA LA PLANTILLA PARA LA BARRA DE ESTADO
            $('#container').html(template_bar);
            // SE LISTAN TODOS LOS PRODUCTOS
            listarProductos();
            // SE REGRESA LA BANDERA DE EDICIÓN A false
            edit = false;
            });
        }
    });

    $(document).on('click', '.product-item', (e) => {
        e.preventDefault();
        $('button.btn-primary').text("Modificar Producto");
        const element = $(this)[0].activeElement.parentElement.parentElement;
        const id = $(element).attr('productId');
        $.post('./backend/product-single.php', {id}, (response) => {
            // SE CONVIERTE A OBJETO EL JSON OBTENIDO
            let product = JSON.parse(response);
            if (Array.isArray(product)) {
                product = product[0];
            }
            // SE INSERTAN LOS DATOS ESPECIALES EN LOS CAMPOS CORRESPONDIENTES
            $('#name').val(product.nombre);
            // EL ID SE INSERTA EN UN CAMPO OCULTO PARA USARLO DESPUÉS PARA LA ACTUALIZACIÓN
            $('#productId').val(product.id);
            $('#marca').val(product.marca);
            $('#modelo').val(product.modelo);
            $('#precio').val(product.precio);
            $('#detalles').val(product.detalles);
            $('#unidades').val(product.unidades);
            $('#imagen').val(product.imagen); 
            $('#productId').val(product.id);
            // SE ELIMINA nombre, eliminado E id PARA PODER MOSTRAR EL JSON EN EL <textarea>
            let mostrarJSON = {...product};
            delete(mostrarJSON.eliminado);
            delete(mostrarJSON.id);
            delete(mostrarJSON.nombre);

            // SE CONVIERTE EL OBJETO JSON EN STRING
            let JsonString = JSON.stringify(product,null,2);
            // SE MUESTRA STRING EN EL <textarea>
            $('#description').val(JsonString);
            
            // SE PONE LA BANDERA DE EDICIÓN EN true
            edit = true;
        });
        e.preventDefault();
    });    
});