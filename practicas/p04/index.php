<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Práctica 4</title>
</head>
<body>
    <h2>Ejercicio 1</h2>
    <p>Determina cuál de las siguientes variables son válidas y explica por qué:</p>
    <p><code>$_myvar, $_7var, myvar, $myvar, $var7, $_element1, $house*5</code></p>
    <h4>Respuesta:</h4>
    <ul>
        <li><?php echo "\$_myvar es válida porque inicia con guión bajo."; ?></li>
        <li><?php echo "\$_7var es válida porque inicia con guión bajo."; ?></li>
        <li><?php echo "myvar es inválida porque no tiene el signo de dólar (\$)."; ?></li>
        <li><?php echo "\$myvar es válida porque inicia con una letra."; ?></li>
        <li><?php echo "\$var7 es válida porque inicia con una letra."; ?></li>
        <li><?php echo "\$_element1 es válida porque inicia con guión bajo."; ?></li>
        <li><?php echo "\$house*5 es inválida porque el símbolo * no está permitido."; ?></li>
    </ul>

    <h2>Ejercicio 2</h2>
    <?php
    $a = "ManejadorSQL";
    $b = "MySQL";
    $c = "ManejadorSQL";

    // reasignaciones
    $a = $b = $c = "PHP server";
    ?>
    <p><b>Valores después de asignación:</b></p>
    <p><?php echo "a = $a <br> b = $b <br> c = $c"; ?></p>

    <h2>Ejercicio 3</h2>
    <?php
    $a = "PHP5";
    $z[0] = $a;
    $b = "5a versión de PHP";
    $warning = "Warning: A non-numeric value encountered en C:\\xampp\\htdocs\\tecweb\\practicas\\p04\\index.php en la línea 67";
    $c = 50;
    $a = 5050;
    $b = 25502500;
    $z[0] = "MySQL";
    ?>
    <p><?php echo "a = PHP5 <br> z[0] = PHP5 <br> b = 5a versión de PHP"; ?></p>
    <p><b><?php echo $warning; ?></b></p>
    <p><?php echo "c = $c <br> a = $a <br> b = $b <br> z[0] = $z[0]"; ?></p>

    <h2>Ejercicio 4</h2>
    <?php
    $a = $b = $c = "MySQL";
    echo "<p>a = $a <br> b = $b <br> c = $c</p>";
    ?>

    <h2>Ejercicio 5</h2>
    <?php
    $a = $b = $c = 9000;
    echo "<p>a = $a <br> b = $b <br> c = $c</p>";
    ?>

    <h2>Ejercicio 6</h2>
    <p><b>var_dump de cada variable:</b></p>
    <?php
    $vars = [false, false, false, false, false, false];
    foreach ($vars as $v) {
        var_dump($v);
        echo "<br>";
    }
    ?>
    <p>Para mostrar valores booleanos en texto se puede usar la función 
        <code>var_export()</code> o <code>json_encode()</code>:</p>
    <?php
    $c = false;
    $e = false;
    echo "c (echo) = " . var_export($c, true) . "<br>";
    echo "e (echo) = " . var_export($e, true) . "<br>";
    ?>

    <h2>Ejercicio 7</h2>
    <?php
    echo "<p>Versión de Apache/PHP: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
    echo "Sistema operativo del servidor: " . PHP_OS . "<br>";
    echo "Idioma del navegador (cliente): " . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "</p>";
    ?>

    <!-- Badges de validación -->
    <footer style="margin-top:20px;">
        <p>
            <a href="https://validator.w3.org/check?uri=referer">
                <img src="https://www.w3.org/html/logo/downloads/HTML5_Badge_128.png" 
                     alt="Valid HTML5" width="64" height="64">
            </a>
            <a href="https://jigsaw.w3.org/css-validator/check/referer">
                <img src="https://jigsaw.w3.org/css-validator/images/vcss-blue" 
                     alt="Valid CSS!" style="border:0;width:88px;height:31px">
            </a>
        </p>
    </footer>
</body>
</html>
