// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

$(document).ready(function(){
    let edit = false;

    let JsonString = JSON.stringify(baseJSON,null,2);
    $('#description').val(JsonString);
    $('#product-result').hide();
    listarProductos();

    // Función para hacer peticiones al API REST
    function apiRequest(method, endpoint, data = null) {
        return $.ajax({
            url: 'backend/' + endpoint,
            type: method,
            data: data ? JSON.stringify(data) : null,
            contentType: 'application/json',
            dataType: 'json'
        });
    }

    function listarProductos() {
        apiRequest('GET', 'products')
            .then(productos => {
                console.log('Productos obtenidos:', productos);
                
                if(productos && Object.keys(productos).length > 0) {
                    let template = '';

                    productos.forEach(producto => {
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
                    
                    $('#products').html(template);
                } else {
                    $('#products').html('<tr><td colspan="4">No hay productos</td></tr>');
                }
            })
            .catch(error => {
                console.error('Error al listar productos:', error);
                $('#products').html('<tr><td colspan="4">Error al cargar productos</td></tr>');
            });
    }

    $('#search').keyup(function() {
        if($('#search').val()) {
            let search = $('#search').val();
            
            apiRequest('GET', `product/${encodeURIComponent(search)}`)
                .then(productos => {
                    if(productos && Object.keys(productos).length > 0) {
                        let template = '';
                        let template_bar = '';

                        productos.forEach(producto => {
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

                            template_bar += `
                                <li>${producto.nombre}</il>
                            `;
                        });
                        
                        $('#product-result').show();
                        $('#container').html(template_bar);
                        $('#products').html(template);    
                    } else {
                        $('#products').html('<tr><td colspan="4">No se encontraron productos</td></tr>');
                    }
                })
                .catch(error => {
                    console.error('Error en búsqueda:', error);
                    $('#products').html('<tr><td colspan="4">Error en búsqueda</td></tr>');
                });
        } else {
            $('#product-result').hide();
            listarProductos();
        }
    });

    $('#product-form').submit(e => {
        e.preventDefault();

        // SE CONVIERTE EL JSON DE STRING A OBJETO
        let postData = JSON.parse($('#description').val());
        // SE AGREGA AL JSON EL NOMBRE DEL PRODUCTO
        postData['nombre'] = $('#name').val();
        
        if (edit) {
            // Actualizar producto existente
            postData['id'] = $('#productId').val();
            apiRequest('PUT', 'product', postData)
                .then(respuesta => {
                    mostrarRespuesta(respuesta);
                })
                .catch(error => {
                    console.error('Error al actualizar:', error);
                    mostrarRespuesta({status: 'error', message: 'Error al actualizar'});
                });
        } else {
            // Crear nuevo producto
            apiRequest('POST', 'product', postData)
                .then(respuesta => {
                    mostrarRespuesta(respuesta);
                })
                .catch(error => {
                    console.error('Error al crear:', error);
                    mostrarRespuesta({status: 'error', message: 'Error al crear producto'});
                });
        }

        function mostrarRespuesta(respuesta) {
            console.log('Respuesta:', respuesta);
            
            let template_bar = '';
            template_bar += `
                <li style="list-style: none;">status: ${respuesta.status}</li>
                <li style="list-style: none;">message: ${respuesta.message}</li>
            `;
            
            $('#name').val('');
            $('#description').val(JsonString);
            $('#product-result').show();
            $('#container').html(template_bar);
            listarProductos();
            edit = false;
        }
    });

    $(document).on('click', '.product-delete', function(e) {
        if(confirm('¿Realmente deseas eliminar el producto?')) {
            const element = $(this).closest('tr');
            const id = $(element).attr('productId');
            
            apiRequest('DELETE', 'product', {id: id})
                .then(() => {
                    $('#product-result').hide();
                    listarProductos();
                })
                .catch(error => {
                    console.error('Error al eliminar:', error);
                });
        }
    });

    $(document).on('click', '.product-item', function(e) {
        const element = $(this).closest('tr');
        const id = $(element).attr('productId');
        
        apiRequest('GET', `product/${id}`)
            .then(product => {
                $('#name').val(product.nombre);
                $('#productId').val(product.id);
                
                delete(product.nombre);
                delete(product.eliminado);
                delete(product.id);
                
                let JsonString = JSON.stringify(product,null,2);
                $('#description').val(JsonString);
                
                edit = true;
            })
            .catch(error => {
                console.error('Error al obtener producto:', error);
            });
        
        e.preventDefault();
    });    
});