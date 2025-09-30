<?php
    $tope = null;
    $rows = [];

    if (isset($_GET['tope'])) {
        $tope = $_GET['tope'];
    }

    if (!empty($tope)) {
        /** Conexión a la BD */
        @$link = new mysqli('localhost:3306', 'root', '', 'marketzone');
        if ($link->connect_errno) {
            die('Falló la conexión: '.$link->connect_error.'<br/>');
        }

        /** Consulta - MANEJAR 'N' COMO VALOR ESPECIAL */
        if ($tope === 'N' || $tope === 'n') {
            // Si tope es 'N', mostrar todos los productos
            $sql = "SELECT * FROM productos";
        } else {
            // Validar que sea número para otros casos
            if (!is_numeric($tope)) {
                die('Error: El parámetro "tope" debe ser un número válido o "N"');
            }
            $tope = (int)$tope;
            $sql = "SELECT * FROM productos WHERE unidades <= $tope";
        }

        if ($result = $link->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            
            // FUNCIÓN PARA DETECTAR LA EXTENSIÓN CORRECTA
            function getImageWithExtension($baseName) {
                $extensions = ['.jpg', '.jpeg', '.png', '.gif'];
                foreach ($extensions as $ext) {
                    if (file_exists($baseName . $ext)) {
                        return $baseName . $ext;
                    }
                }
                return $baseName . '.jpg'; // Por defecto
            }
            
            // ASIGNAR IMÁGENES CON EXTENSIÓN CORRECTA
            foreach ($rows as &$row) {
                $nombre = $row['nombre'];
                
                // Mapeo de nombres
                if (strpos($nombre, 'Laptop') !== false || strpos($nombre, 'Inspiron') !== false) {
                    $row['imagen'] = getImageWithExtension('img/dell_inspiron');
                } elseif (strpos($nombre, 'Smartphone') !== false || strpos($nombre, 'Galaxy') !== false) {
                    $row['imagen'] = getImageWithExtension('img/galaxy_a32');
                } elseif (strpos($nombre, 'Teclado') !== false || strpos($nombre, 'Mecánico') !== false) {
                    $row['imagen'] = getImageWithExtension('img/teclado_g413');
                } elseif (strpos($nombre, 'Monitor') !== false || strpos($nombre, 'LED') !== false) {
                    $row['imagen'] = getImageWithExtension('img/lg_monitor');
                } elseif (strpos($nombre, 'Impresora') !== false || strpos($nombre, 'Multifuncional') !== false) {
                    $row['imagen'] = getImageWithExtension('img/hp_deskjet');
                } elseif (strpos($nombre, 'TERMO') !== false || strpos($nombre, 'DIGITAL') !== false) {
                    $row['imagen'] = getImageWithExtension('img/termo');
                } elseif (strpos($nombre, 'Pantuflas') !== false || strpos($nombre, 'Bob Esponja') !== false) {
                    $row['imagen'] = getImageWithExtension('img/pantuflas');
                } elseif (strpos($nombre, 'Lámpara') !== false || strpos($nombre, 'Luna') !== false) {
                    $row['imagen'] = getImageWithExtension('img/lampara');
                } else {
                    $row['imagen'] = getImageWithExtension('img/default');
                }
            }
            unset($row);
            
            $result->free();
        }
        $link->close();
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Productos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
</head>
<body class="container mt-4">
    <?php if (empty($tope)) : ?>
        <!-- Formulario cuando no se ha enviado tope -->
        <h3>Consultar productos por límite de unidades</h3>
        <form method="get" action="">
            <div class="form-group">
                <label for="tope">Número máximo de unidades (o 'N' para todos):</label>
                <input type="text" class="form-control" name="tope" id="tope" required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
    <?php else: ?>
        <!-- Título -->
        <?php if ($tope === 'N' || $tope === 'n') : ?>
            <h3>TODOS LOS PRODUCTOS</h3>
        <?php else: ?>
            <h3>PRODUCTOS (Unidades ≤ <?= htmlspecialchars($tope) ?>)</h3>
        <?php endif; ?>
        <br/>

        <?php if (!empty($rows)) : ?>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Precio</th>
                        <th>Unidades</th>
                        <th>Detalles</th>
                        <th>Imagen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <th scope="row"><?= $row['id'] ?></th>
                            <td><?= $row['nombre'] ?></td>
                            <td><?= $row['marca'] ?></td>
                            <td><?= $row['modelo'] ?></td>
                            <td>$<?= number_format($row['precio'], 2) ?></td>
                            <td><?= $row['unidades'] ?></td>
                            <td><?= $row['detalles'] ?></td>
                            <td>
                                <img src="<?= $row['imagen'] ?>" alt="<?= $row['nombre'] ?>" width="80" height="80" style="object-fit: cover;"/>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div class="alert alert-warning">
                No hay productos que mostrar
            </div>
        <?php endif; ?>
        
        <a href="?" class="btn btn-secondary">Nueva búsqueda</a>
    <?php endif; ?>
</body>
</html>