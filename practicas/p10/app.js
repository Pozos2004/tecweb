// app.js - Aplicación principal con AJAX

function buscarID() {
    const id = prompt("Ingresa el ID del producto:");
    if (!id) return;
    
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `backend/read.php?id=${id}`, true);
    
    xhr.onload = function() {
        if (this.status === 200) {
            try {
                const producto = JSON.parse(this.responseText);
                mostrarResultadoIndividual(producto);
            } catch (e) {
                document.getElementById('results').innerHTML = 
                    '<div class="alert alert-danger">Error al procesar la respuesta</div>';
            }
        } else {
            document.getElementById('results').innerHTML = 
                '<div class="alert alert-danger">Error en la consulta</div>';
        }
    };
    
    xhr.onerror = function() {
        document.getElementById('results').innerHTML = 
            '<div class="alert alert-danger">Error de conexión</div>';
    };
    
    xhr.send();
}

function buscarProducto() {
    const searchTerm = document.getElementById('searchTerm').value.trim();
    
    if (!searchTerm) {
        alert('Por favor ingresa un término de búsqueda');
        return;
    }
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'backend/search.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (this.status === 200) {
            try {
                const productos = JSON.parse(this.responseText);
                mostrarResultadosBusqueda(productos);
            } catch (e) {
                document.getElementById('results').innerHTML = 
                    '<div class="alert alert-danger">Error al procesar los resultados</div>';
            }
        } else {
            document.getElementById('results').innerHTML = 
                '<div class="alert alert-danger">Error en la búsqueda</div>';
        }
    };
    
    xhr.onerror = function() {
        document.getElementById('results').innerHTML = 
            '<div class="alert alert-danger">Error de conexión</div>';
    };
    
    xhr.send(`searchTerm=${encodeURIComponent(searchTerm)}`);
}

function mostrarResultadoIndividual(producto) {
    if (producto.error) {
        document.getElementById('results').innerHTML = 
            `<div class="alert alert-warning">${producto.error}</div>`;
        return;
    }
    
    const html = `
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">${producto.nombre}</h5>
                <div class="row">
                    <div class="col-md-8">
                        <p><strong>Marca:</strong> ${producto.marca}</p>
                        <p><strong>Modelo:</strong> ${producto.modelo}</p>
                        <p><strong>Precio:</strong> $${parseFloat(producto.precio).toFixed(2)}</p>
                        <p><strong>Unidades:</strong> ${producto.unidades}</p>
                        <p><strong>Detalles:</strong> ${producto.detalles || 'N/A'}</p>
                    </div>
                    <div class="col-md-4">
                        ${producto.imagen && producto.imagen !== 'img/default.png' ? 
                            `<img src="${producto.imagen}" class="img-fluid" alt="${producto.nombre}">` : 
                            '<p class="text-muted">Sin imagen</p>'}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('results').innerHTML = html;
}

function mostrarResultadosBusqueda(productos) {
    if (productos.error) {
        document.getElementById('results').innerHTML = 
            `<div class="alert alert-warning">${productos.error}</div>`;
        return;
    }
    
    if (productos.length === 0) {
        document.getElementById('results').innerHTML = 
            '<div class="alert alert-info">No se encontraron productos</div>';
        return;
    }
    
    let html = `<p class="text-info">Se encontraron ${productos.length} producto(s)</p>`;
    
    productos.forEach(producto => {
        html += `
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">${producto.nombre}</h5>
                    <div class="row">
                        <div class="col-md-8">
                            <p><strong>Marca:</strong> ${producto.marca}</p>
                            <p><strong>Modelo:</strong> ${producto.modelo}</p>
                            <p><strong>Precio:</strong> $${parseFloat(producto.precio).toFixed(2)}</p>
                            <p><strong>Unidades:</strong> ${producto.unidades}</p>
                            <p><strong>Detalles:</strong> ${producto.detalles || 'N/A'}</p>
                        </div>
                        <div class="col-md-4">
                            ${producto.imagen && producto.imagen !== 'img/default.png' ? 
                                `<img src="${producto.imagen}" class="img-fluid" style="max-height: 150px;" alt="${producto.nombre}">` : 
                                '<p class="text-muted">Sin imagen</p>'}
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    document.getElementById('results').innerHTML = html;
}

function crearProducto() {
    // Validaciones del lado del cliente
    if (!validarFormulario()) {
        return;
    }
    
    const formData = {
        nombre: document.getElementById('nombre').value.trim(),
        marca: document.getElementById('marca').value,
        modelo: document.getElementById('modelo').value.trim(),
        precio: document.getElementById('precio').value,
        unidades: document.getElementById('unidades').value,
        detalles: document.getElementById('detalles').value.trim(),
        imagen: document.getElementById('imagen').value.trim() || 'img/default.png'
    };
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'backend/create.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onload = function() {
        if (this.status === 200) {
            try {
                const respuesta = JSON.parse(this.responseText);
                if (respuesta.success) {
                    alert('✓ ' + respuesta.message);
                    document.getElementById('productForm').reset();
                } else {
                    alert('X ' + respuesta.message);
                }
            } catch (e) {
                alert('Error al procesar la respuesta del servidor');
            }
        } else {
            alert('Error en la conexión con el servidor');
        }
    };
    
    xhr.onerror = function() {
        alert('Error de conexión');
    };
    
    xhr.send(JSON.stringify(formData));
}

function validarFormulario() {
    const nombre = document.getElementById('nombre').value.trim();
    const marca = document.getElementById('marca').value;
    const modelo = document.getElementById('modelo').value.trim();
    const precio = parseFloat(document.getElementById('precio').value);
    const unidades = parseInt(document.getElementById('unidades').value);
    const detalles = document.getElementById('detalles').value.trim();
    
    // Validar nombre
    if (nombre === '' || nombre.length > 100) {
        alert('El nombre es requerido y debe tener máximo 100 caracteres');
        return false;
    }
    
    // Validar marca
    if (marca === '') {
        alert('Debe seleccionar una marca');
        return false;
    }
    
    // Validar modelo
    const alfanumerico = /^[A-Za-z0-9\s\-\.]+$/;
    if (modelo === '' || modelo.length > 25 || !alfanumerico.test(modelo)) {
        alert('El modelo es requerido, debe ser alfanumérico y tener máximo 25 caracteres');
        return false;
    }
    
    // Validar precio
    if (isNaN(precio) || precio <= 99.99) {
        alert('El precio debe ser mayor a $99.99');
        return false;
    }
    
    // Validar unidades
    if (isNaN(unidades) || unidades < 0) {
        alert('Las unidades deben ser un número mayor o igual a 0');
        return false;
    }
    
    // Validar detalles
    if (detalles.length > 250) {
        alert('Los detalles no pueden tener más de 250 caracteres');
        return false;
    }
    
    return true;
}

// Event Listeners para mejor UX
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchTerm').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            buscarProducto();
        }
    });
});