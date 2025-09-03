<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Práctica 4</title>
</head>
<body>
    <h2>Ejercicio 1</h2>
    <p>Determina cuál de las siguientes variables son válidas y explica por qué:</p>
    <p>$_myvar,  $_7var,  myvar,  $myvar,  $var7,  $_element1, $house*5</p>
    <?php
        //AQUI VA MI CÓDIGO PHP
        $_myvar;
        $_7var;
        //myvar;       // Inválida
        $myvar;
        $var7;
        $_element1;
        //$house*5;     // Invalida
        
        echo '<h4>Respuesta:</h4>';   
    
        echo '<ul>';
        echo '<li>$_myvar es válida porque inicia con guión bajo.</li>';
        echo '<li>$_7var es válida porque inicia con guión bajo.</li>';
        echo '<li>myvar es inválida porque no tiene el signo de dolar ($).</li>';
        echo '<li>$myvar es válida porque inicia con una letra.</li>';
        echo '<li>$var7 es válida porque inicia con una letra.</li>';
        echo '<li>$_element1 es válida porque inicia con guión bajo.</li>';
        echo '<li>$house*5 es inválida porque el símbolo * no está permitido.</li>';
        echo '</ul>';
    ?>
    <h2>Ejercicio 2</h2>
    <?php
        // Definir variables
        $a = "ManejadorSQL";
        $b = 'MySQL';
        $c = &$a;

        echo "<p><b>Valores iniciales:</b></p>";
        echo "a = $a <br/>";
        echo "b = $b <br/>";
        echo "c = $c <br/>";

        // Nuevas asignaciones
        $a = "PHP server";
        $b = &$a;

        echo "<p><b>Después de nuevas asignaciones:</b></p>";
        echo "a = $a <br/>";
        echo "b = $b <br/>";
        echo "c = $c <br/>";

        echo "<p><i>Descripción:</i> En el segundo bloque, se reasigna <code>\$a</code> y <code>\$b</code> 
        para que apunten a la misma referencia. Como <code>\$c</code> también estaba referenciado a 
        <code>\$a</code>, los tres comparten el mismo valor.</p>";
    ?>

    <h2>Ejercicio 3</h2>
    <?php
        $a = "PHP5";
        echo "a = $a <br/>";
        $z[] = &$a;
        echo "z[0] = ".$z[0]."<br/>";
        $b = "5a version de PHP";
        echo "b = $b <br/>";
        $c = $b * 10; // al ser string, se intenta convertir a número => 0
        echo "c = $c <br/>";
        $a .= $b;
        echo "a = $a <br/>";
        $b *= $c;
        echo "b = $b <br/>";
        $z[0] = "MySQL";
        echo "z[0] = ".$z[0]."<br/>";
    ?>

    <h2>Ejercicio 4</h2>
    <?php
        echo "a = ".$GLOBALS['a']."<br/>";
        echo "b = ".$GLOBALS['b']."<br/>";
        echo "c = ".$GLOBALS['c']."<br/>";
    ?>

    <h2>Ejercicio 5</h2>
    <?php
        $a = "7 personas";
        $b = (integer) $a; 
        $a = "9E3";
        $c = (double) $a; 

        echo "a = $a <br/>";
        echo "b = $b <br/>";
        echo "c = $c <br/>";
    ?>

    <h2>Ejercicio 6</h2>
    <?php
        $a = "0";
        $b = "TRUE";
        $c = FALSE;
        $d = ($a OR $b);
        $e = ($a AND $c);
        $f = ($a XOR $b);

        echo "<p><b>var_dump de cada variable:</b></p>";
        var_dump($a); echo "<br/>";
        var_dump($b); echo "<br/>";
        var_dump($c); echo "<br/>";
        var_dump($d); echo "<br/>";
        var_dump($e); echo "<br/>";
        var_dump($f); echo "<br/>";

        echo "<p>Para mostrar valores booleanos en texto se puede usar la función <code>var_export()</code> o 
        <code>json_encode()</code>:</p>";
        echo "c (echo) = ".json_encode($c)."<br/>";
        echo "e (echo) = ".json_encode($e)."<br/>";
    ?>

    <h2>Ejercicio 7</h2>
    <?php
        echo "Versión de Apache/PHP: ".$_SERVER['SERVER_SOFTWARE']."<br/>";
        echo "Sistema operativo del servidor: ".PHP_OS."<br/>";
        echo "Idioma del navegador (cliente): ".$_SERVER['HTTP_ACCEPT_LANGUAGE']."<br/>";
    ?>
</body>
</html>