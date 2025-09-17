<?php
include("src/funciones.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Práctica 6</title>
</head>
<body>
    <h1>Práctica 6 – Funciones, Arreglos y Ciclos en PHP</h1>

    <!-- Ejercicio 1 -->
    <h2>Ejercicio 1: Comprobar múltiplo de 5 y 7</h2>
    <form method="get">
        Número: <input type="number" name="numero">
        <input type="submit" value="Comprobar">
    </form>
    <?php
        if(isset($_GET['numero'])) {
            $n = $_GET['numero'];
            echo esMultiplo5y7($n) ? "✅ $n es múltiplo de 5 y 7" : "❌ $n NO es múltiplo de 5 y 7";
        }
    ?>

    <hr>

    <!-- Ejercicio 2 -->
    <h2>Ejercicio 2: Generar secuencia impar-par-impar</h2>
    <?php
        $resultado = generarSecuencia();
        echo "<pre>";
        print_r($resultado["matriz"]);
        echo "</pre>";
        echo "Se generaron ".($resultado["iteraciones"]*3)." números en ".$resultado["iteraciones"]." iteraciones.";
    ?>

    <hr>

    <!-- Ejercicio 3 -->
    <h2>Ejercicio 3: Buscar múltiplo con while y do-while</h2>
    <form method="get">
        Número divisor: <input type="number" name="divisor">
        <input type="submit" value="Buscar">
    </form>
    <?php
        if(isset($_GET['divisor'])) {
            $d = $_GET['divisor'];
            echo "🔹 Con while: ".encontrarMultiploWhile($d)."<br>";
            echo "🔹 Con do-while: ".encontrarMultiploDoWhile($d)."<br>";
        }
    ?>

    <hr>

    <!-- Ejercicio 4 -->
    <h2>Ejercicio 4: Arreglo ASCII (97-122)</h2>
    <table border="1" cellpadding="5">
        <tr><th>Código ASCII</th><th>Letra</th></tr>
        <?php
            foreach(arregloAscii() as $k => $v) {
                echo "<tr><td>$k</td><td>$v</td></tr>";
            }
        ?>
    </table>

    <hr>

    <!-- Ejercicio 5 -->
    <h2>Ejercicio 5: Bienvenida según edad y sexo</h2>
    <form method="post">
        Edad: <input type="number" name="edad"><br><br>
        Sexo:
        <select name="sexo">
            <option value="f">Femenino</option>
            <option value="m">Masculino</option>
        </select><br><br>
        <input type="submit" value="Enviar">
    </form>
    <?php
        if(isset($_POST["edad"]) && isset($_POST["sexo"])) {
            $edad = $_POST["edad"];
            $sexo = $_POST["sexo"];
            echo verificarBienvenida($edad, $sexo);
        }
    ?>

    <hr>

    <!-- Ejercicio 6 -->
    <h2>Ejercicio 6: Registro vehicular</h2>
    <form method="get">
        Matrícula: <input type="text" name="matricula">
        <input type="submit" value="Consultar">
    </form>
    <?php
        $autos = registrarAutos();

        if(isset($_GET['matricula']) && $_GET['matricula'] != "") {
            $mat = strtoupper($_GET['matricula']); // convierte a mayúsculas
            $resultado = buscarAuto($mat);
            if($resultado) {
                echo "<h3>Información del auto con matrícula $mat:</h3>";
                echo "<pre>";
                print_r($resultado);
                echo "</pre>";
            } else {
                echo "❌ No se encontró esa matrícula.";
            }
        } else {
            echo "<h3>Todos los autos registrados:</h3><pre>";
            print_r($autos);
            echo "</pre>";
        }
    ?>
</body>
</html>
