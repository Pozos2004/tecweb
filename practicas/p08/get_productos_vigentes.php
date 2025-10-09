<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<?php
    $tope = null;

    if (isset($_GET['tope']))
        $tope = $_GET['tope'];

    if (!empty($tope)) {
        /** Conexión a la BD */
        @$link = new mysqli('localhost', 'root', '', 'marketzone');
        if ($link->connect_errno) {
            die('Falló la conexión: '.$link->connect_error.'<br/>');
        }

        /** Consulta: solo productos no eliminados */
        $query = "SELECT * FROM productos WHERE unidades <= $tope AND eliminado = 0";
        if ($result = $link->query($query)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
        }
        $link->close();
    }
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Productos Vigentes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
</head>
<body class="container">
    <h3 class="mt-4">PRODUCTOS VIGENTES (Unidades ≤ <?= htmlspecialchars($tope) ?>)</h3>
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
                            <?php if (!empty($row['imagen']) && file_exists($row['imagen'])): ?>
                                <img src="<?= $row['imagen'] ?>" width="80" alt="<?= $row['nombre'] ?>">
                            <?php else: ?>
                                <span class="text-muted">Sin imagen</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (!empty($tope)) : ?>
        <div class="alert alert-warning">No hay productos vigentes con ese número de unidades</div>
    <?php endif; ?>
</body>
</html>