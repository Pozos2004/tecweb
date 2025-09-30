<?php
    $tope = null;   // inicializamos
    $rows = [];     // inicializamos arreglo de resultados

    if (isset($_GET['tope'])) {
        $tope = $_GET['tope'];
    }

    if (!empty($tope)) {
        /** Conexión a la BD */
        @$link = new mysqli('localhost:3306', 'root', '', 'marketzone');
        if ($link->connect_errno) {
            die('Falló la conexión: '.$link->connect_error.'<br/>');
        }

        /** Consulta */
        if ($result = $link->query("SELECT * FROM productos WHERE unidades <= $tope")) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
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
                <label for="tope">Número máximo de unidades:</label>
                <input type="number" class="form-control" name="tope" id="tope" required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
    <?php else: ?>
        <!-- Título -->
        <h3>PRODUCTOS (Unidades ≤ <?= htmlspecialchars($tope) ?>)</h3>
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
                            <td><?= $row['precio'] ?></td>
                            <td><?= $row['unidades'] ?></td>
                            <td><?= utf8_encode($row['detalles']) ?></td>
                            <td><img src="<?= $row['imagen'] ?>" width="80"/></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <script>alert('No hay productos con ese número de unidades');</script>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
