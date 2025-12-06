<?php
// IMPORTANTE: Nada de espacios ni líneas en blanco antes de <?php

// Desactivar excepciones automáticas de mysqli para que no truene con Fatal
mysqli_report(MYSQLI_REPORT_OFF);

// 1) Conexión a la base de datos
$cnx = @mysqli_connect('localhost', 'root', '', 'marketzone');  // AJUSTA user/pass SI LOS CAMBIASTE

if (!$cnx) {
    echo json_encode([
        "status"  => "error",
        "message" => "No se pudo conectar a la base de datos: " . mysqli_connect_error()
    ]);
    exit;
}

// 2) Leer datos del formulario
$titulo       = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
$descripcion  = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
$lenguaje     = isset($_POST['lenguaje']) ? trim($_POST['lenguaje']) : '';
$tipoRecurso  = isset($_POST['tipo_recurso']) ? trim($_POST['tipo_recurso']) : '';
$idRecurso    = isset($_POST['id']) ? trim($_POST['id']) : '';  // vacío = nuevo

if ($titulo === '') {
    echo json_encode([
        "status"  => "error",
        "message" => "El título del recurso es obligatorio"
    ]);
    exit;
}

$archivoSubido = false;
$tipoArchivo   = null;
$rutaRelativa  = null;

// 3) Manejo del archivo (OBLIGATORIO para alta nueva)
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
    $archivoSubido  = true;
    $nombreOriginal = $_FILES['archivo']['name'];
    $tmpPath        = $_FILES['archivo']['tmp_name'];

    $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
    $tipoArchivo = $ext;

    // Carpeta destino (junto a backend/)
    $destDir = __DIR__ . '/../uploads_recursos/';
    if (!is_dir($destDir)) {
        mkdir($destDir, 0777, true);
    }

    $nombreSeguro = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $nombreOriginal);
    $destPath     = $destDir . $nombreSeguro;

    if (!move_uploaded_file($tmpPath, $destPath)) {
        echo json_encode([
            "status"  => "error",
            "message" => "No se pudo guardar el archivo en el servidor"
        ]);
        exit;
    }

    // Ruta relativa para guardar en BD
    $rutaRelativa = 'uploads_recursos/' . $nombreSeguro;
} else {
    // Si es alta nueva y no viene archivo => error
    if ($idRecurso === '') {
        echo json_encode([
            "status"  => "error",
            "message" => "Debes seleccionar un archivo para subir"
        ]);
        exit;
    }
}

// Escapar para evitar inyección
$tituloEsc      = mysqli_real_escape_string($cnx, $titulo);
$descripcionEsc = mysqli_real_escape_string($cnx, $descripcion);
$lenguajeEsc    = mysqli_real_escape_string($cnx, $lenguaje);
$tipoRecEsc     = mysqli_real_escape_string($cnx, $tipoRecurso);

// 4) ¿Actualización o alta nueva?
if ($idRecurso !== '') {
    // --------- EDICIÓN ---------
    $id = (int)$idRecurso;

    if ($archivoSubido) {
        $tipoArchEsc = mysqli_real_escape_string($cnx, $tipoArchivo);
        $rutaEsc     = mysqli_real_escape_string($cnx, $rutaRelativa);

        $sql = "
            UPDATE archivos
            SET titulo='$tituloEsc',
                descripcion='$descripcionEsc',
                lenguaje='$lenguajeEsc',
                tipo_recurso='$tipoRecEsc',
                tipo_archivo='$tipoArchEsc',
                ruta_archivo='$rutaEsc'
            WHERE id=$id
        ";
    } else {
        $sql = "
            UPDATE archivos
            SET titulo='$tituloEsc',
                descripcion='$descripcionEsc',
                lenguaje='$lenguajeEsc',
                tipo_recurso='$tipoRecEsc'
            WHERE id=$id
        ";
    }

    if (mysqli_query($cnx, $sql)) {
        echo json_encode([
            "status"  => "success",
            "message" => "Recurso actualizado correctamente"
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Error al actualizar recurso: " . mysqli_error($cnx)
        ]);
    }

    mysqli_close($cnx);
    exit;
}

// --------- ALTA NUEVA ---------
$tipoArchEsc = mysqli_real_escape_string($cnx, $tipoArchivo);
$rutaEsc     = mysqli_real_escape_string($cnx, $rutaRelativa);

$sql = "
    INSERT INTO archivos (titulo, descripcion, lenguaje, tipo_recurso, tipo_archivo, ruta_archivo)
    VALUES ('$tituloEsc', '$descripcionEsc', '$lenguajeEsc', '$tipoRecEsc', '$tipoArchEsc', '$rutaEsc')
";

if (mysqli_query($cnx, $sql)) {
    echo json_encode([
        "status"  => "success",
        "message" => "Recurso digital agregado correctamente"
    ]);
} else {
    echo json_encode([
        "status"  => "error",
        "message" => "Error al guardar recurso: " . mysqli_error($cnx)
    ]);
}

mysqli_close($cnx);
