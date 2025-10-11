<?php
// formulario_productos_v2.php
$producto = [
    'id' => '',
    'nombre' => '',
    'marca' => '',
    'modelo' => '',
    'precio' => '',
    'unidades' => '',
    'detalles' => '',
    'imagen' => 'img/default.png'
];

$id = isset($_GET['id']) ? $_GET['id'] : '';

// Si hay un ID, cargar datos del producto
if (!empty($id)) {
    @$link = new mysqli('localhost', 'root', '', 'marketzone');
    if (!$link->connect_errno) {
        $query = "SELECT * FROM productos WHERE id = $id";
        if ($result = $link->query($query)) {
            $producto = $result->fetch_assoc();
            $result->free();
        }
        $link->close();
    }
}

$action = empty($id) ? 'set_producto_v2.php' : 'update_producto.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= empty($id) ? 'Registrar' : 'Editar' ?> Producto Electrónico</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .error { color: red; font-size: 0.9em; }
        input:invalid, select:invalid, textarea:invalid { border-color: #dc3545; }
    </style>
</head>
<body class="container mt-5">
    <h3><?= empty($id) ? 'Registro' : 'Edición' ?> de Producto Electrónico</h3>
    
    <form id="formProducto" action="<?= $action ?>" method="post" onsubmit="return validarFormulario()">
        <?php if (!empty($id)): ?>
            <input type="hidden" name="id" value="<?= $id ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Nombre del Producto:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" maxlength="100" 
                   value="<?= htmlspecialchars($producto['nombre']) ?>" required>
            <small class="form-text text-muted">Máximo 100 caracteres</small>
            <div id="errorNombre" class="error"></div>
        </div>

        <div class="form-group">
            <label>Marca:</label>
            <select name="marca" id="marca" class="form-control" required>
                <option value="">Selecciona una marca</option>
                <option value="Dell" <?= $producto['marca'] == 'Dell' ? 'selected' : '' ?>>Dell</option>
                <option value="Samsung" <?= $producto['marca'] == 'Samsung' ? 'selected' : '' ?>>Samsung</option>
                <option value="Logitech" <?= $producto['marca'] == 'Logitech' ? 'selected' : '' ?>>Logitech</option>
                <option value="LG" <?= $producto['marca'] == 'LG' ? 'selected' : '' ?>>LG</option>
                <option value="HP" <?= $producto['marca'] == 'HP' ? 'selected' : '' ?>>HP</option>
                <option value="VAGABOX" <?= $producto['marca'] == 'VAGABOX' ? 'selected' : '' ?>>VAGABOX</option>
                <option value="MindMed" <?= $producto['marca'] == 'MindMed' ? 'selected' : '' ?>>MindMed</option>
                <option value="Dosyu" <?= $producto['marca'] == 'Dosyu' ? 'selected' : '' ?>>Dosyu</option>
                <option value="Asus" <?= $producto['marca'] == 'Asus' ? 'selected' : '' ?>>Asus</option>
                <option value="JBL" <?= $producto['marca'] == 'JBL' ? 'selected' : '' ?>>JBL</option>
                <option value="Hisense" <?= $producto['marca'] == 'Hisense' ? 'selected' : '' ?>>Hisense</option>
                <option value="Xiaomi" <?= $producto['marca'] == 'Xiaomi' ? 'selected' : '' ?>>Xiaomi</option>
                <option value="Amazfit" <?= $producto['marca'] == 'Amazfit' ? 'selected' : '' ?>>Amazfit</option>
                <option value="Otra" <?= $producto['marca'] == 'Otra' ? 'selected' : '' ?>>Otra</option>
            </select>
            <div id="errorMarca" class="error"></div>
        </div>

        <div class="form-group">
            <label>Modelo:</label>
            <input type="text" name="modelo" id="modelo" class="form-control" maxlength="25" 
                   value="<?= htmlspecialchars($producto['modelo']) ?>" required>
            <small class="form-text text-muted">Máximo 25 caracteres alfanuméricos</small>
            <div id="errorModelo" class="error"></div>
        </div>

        <div class="form-group">
            <label>Precio ($):</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" 
                   min="100" value="<?= $producto['precio'] ?>" required>
            <small class="form-text text-muted">Debe ser mayor a $99.99</small>
            <div id="errorPrecio" class="error"></div>
        </div>

        <div class="form-group">
            <label>Unidades en Stock:</label>
            <input type="number" name="unidades" id="unidades" class="form-control" 
                   min="0" value="<?= $producto['unidades'] ?>" required>
            <div id="errorUnidades" class="error"></div>
        </div>

        <div class="form-group">
            <label>Detalles y Especificaciones:</label>
            <textarea name="detalles" id="detalles" class="form-control" rows="3" maxlength="250"><?= htmlspecialchars($producto['detalles']) ?></textarea>
            <small class="form-text text-muted">Máximo 250 caracteres (opcional)</small>
            <div id="errorDetalles" class="error"></div>
        </div>

        <div class="form-group">
            <label>Ruta de Imagen:</label>
            <input type="text" name="imagen" id="imagen" class="form-control" 
                   placeholder="img/nombre_imagen.jpg" value="<?= $producto['imagen'] ?>">
            <small class="form-text text-muted">Opcional - Se usará imagen por defecto si está vacío</small>
        </div>

        <button type="submit" class="btn btn-primary">
            <?= empty($id) ? 'Registrar Producto' : 'Actualizar Producto' ?>
        </button>
        <a href="get_productos_vigentes_v2.php?tope=100" class="btn btn-secondary">Ver Productos</a>
        <a href="get_productos_xhtml_v2.php" class="btn btn-info">Ver Tabla Completa</a>
    </form>

    <script>
        function validarFormulario() {
            let isValid = true;
            
            // Limpiar errores anteriores
            document.querySelectorAll('.error').forEach(error => error.textContent = '');
            
            // Validar nombre
            const nombre = document.getElementById('nombre').value.trim();
            if (nombre === '' || nombre.length > 100) {
                document.getElementById('errorNombre').textContent = 'El nombre es requerido y debe tener máximo 100 caracteres';
                isValid = false;
            }
            
            // Validar marca
            const marca = document.getElementById('marca').value;
            if (marca === '') {
                document.getElementById('errorMarca').textContent = 'Debe seleccionar una marca';
                isValid = false;
            }
            
            // Validar modelo
            const modelo = document.getElementById('modelo').value.trim();
            const alfanumerico = /^[A-Za-z0-9\s\-\.]+$/;
            if (modelo === '' || modelo.length > 25 || !alfanumerico.test(modelo)) {
                document.getElementById('errorModelo').textContent = 'El modelo es requerido, debe ser alfanumérico y tener máximo 25 caracteres';
                isValid = false;
            }
            
            // Validar precio
            const precio = parseFloat(document.getElementById('precio').value);
            if (isNaN(precio) || precio <= 99.99) {
                document.getElementById('errorPrecio').textContent = 'El precio debe ser mayor a $99.99';
                isValid = false;
            }
            
            // Validar unidades
            const unidades = parseInt(document.getElementById('unidades').value);
            if (isNaN(unidades) || unidades < 0) {
                document.getElementById('errorUnidades').textContent = 'Las unidades deben ser un número mayor o igual a 0';
                isValid = false;
            }
            
            // Validar detalles
            const detalles = document.getElementById('detalles').value.trim();
            if (detalles.length > 250) {
                document.getElementById('errorDetalles').textContent = 'Los detalles no pueden tener más de 250 caracteres';
                isValid = false;
            }
            
            // Asignar imagen por defecto si está vacío
            const imagen = document.getElementById('imagen').value.trim();
            if (imagen === '') {
                document.getElementById('imagen').value = 'img/default.png';
            }
            
            if (!isValid) {
                alert('Por favor corrige los errores en el formulario');
            }
            
            return isValid;
        }
        
        // Validación en tiempo real
        document.getElementById('formProducto').addEventListener('input', function(e) {
            const field = e.target;
            const value = field.value.trim();
            
            switch(field.id) {
                case 'nombre':
                    field.style.borderColor = value.length > 100 ? 'red' : '#28a745';
                    break;
                case 'modelo':
                    const alfanumerico = /^[A-Za-z0-9\s\-\.]+$/;
                    field.style.borderColor = (!alfanumerico.test(value) || value.length > 25) ? 'red' : '#28a745';
                    break;
                case 'precio':
                    field.style.borderColor = parseFloat(value) <= 99.99 ? 'red' : '#28a745';
                    break;
                case 'unidades':
                    field.style.borderColor = (parseInt(value) < 0) ? 'red' : '#28a745';
                    break;
                case 'detalles':
                    field.style.borderColor = value.length > 250 ? 'red' : '#28a745';
                    break;
            }
        });
    </script>
</body>
</html>