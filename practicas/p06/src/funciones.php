<?php
// Ejercicio 1: múltiplo de 5 y 7
function esMultiplo5y7($num) {
    return ($num % 5 == 0 && $num % 7 == 0);
}

// Ejercicio 2: generar números hasta obtener impar-par-impar
function generarSecuencia() {
    $matriz = [];
    $iteraciones = 0;

    do {
        $fila = [rand(1, 999), rand(1, 999), rand(1, 999)];
        $matriz[] = $fila;
        $iteraciones++;
    } while(!($fila[0] % 2 != 0 && $fila[1] % 2 == 0 && $fila[2] % 2 != 0));

    return ["matriz" => $matriz, "iteraciones" => $iteraciones];
}

// Ejercicio 3: while y do-while para múltiplo de un número dado
function encontrarMultiploWhile($num) {
    $rand = rand(1, 999);
    while($rand % $num != 0) {
        $rand = rand(1, 999);
    }
    return $rand;
}

function encontrarMultiploDoWhile($num) {
    do {
        $rand = rand(1, 999);
    } while($rand % $num != 0);
    return $rand;
}

// Ejercicio 4: arreglo de ASCII (97 → 122 = letras a-z)
function arregloAscii() {
    $arr = [];
    for ($i = 97; $i <= 122; $i++) {
        $arr[$i] = chr($i);
    }
    return $arr;
}

// Ejercicio 5: verificar edad y sexo
function verificarBienvenida($edad, $sexo) {
    if($sexo == "f" && $edad >= 18 && $edad <= 35) {
        return "Bienvenida, usted está en el rango permitido.";
    } else {
        return "No cumple con las condiciones.";
    }
}

// Ejercicio 6: parque vehicular
function registrarAutos() {
    $autos = [
        "ABC1234" => [
            "Auto" => ["marca"=>"HONDA","modelo"=>2020,"tipo"=>"camioneta"],
            "Propietario" => ["nombre"=>"Ana Pérez","ciudad"=>"Puebla","direccion"=>"Av Reforma 123"]
        ],
        "XYZ5678" => [
            "Auto" => ["marca"=>"MAZDA","modelo"=>2019,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"Luis Gómez","ciudad"=>"CDMX","direccion"=>"Insurgentes Sur"]
        ],
        "JKL2468" => [
            "Auto" => ["marca"=>"TOYOTA","modelo"=>2018,"tipo"=>"hachback"],
            "Propietario" => ["nombre"=>"María López","ciudad"=>"Guadalajara","direccion"=>"Av Patria 321"]
        ],
        "MNO1357" => [
            "Auto" => ["marca"=>"NISSAN","modelo"=>2021,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"Carlos Ramírez","ciudad"=>"Monterrey","direccion"=>"Col. Centro"]
        ],
        "PQR9876" => [
            "Auto" => ["marca"=>"FORD","modelo"=>2020,"tipo"=>"camioneta"],
            "Propietario" => ["nombre"=>"Elena Torres","ciudad"=>"Puebla","direccion"=>"Av Juárez 55"]
        ],
        "STU5432" => [
            "Auto" => ["marca"=>"CHEVROLET","modelo"=>2017,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"Pedro Sánchez","ciudad"=>"CDMX","direccion"=>"Polanco"]
        ],
        "VWX1111" => [
            "Auto" => ["marca"=>"KIA","modelo"=>2019,"tipo"=>"camioneta"],
            "Propietario" => ["nombre"=>"Sofía Hernández","ciudad"=>"Querétaro","direccion"=>"Centro Histórico"]
        ],
        "YZA2222" => [
            "Auto" => ["marca"=>"BMW","modelo"=>2022,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"Miguel Ángel","ciudad"=>"Toluca","direccion"=>"Av Las Torres"]
        ],
        "BCD3333" => [
            "Auto" => ["marca"=>"AUDI","modelo"=>2021,"tipo"=>"hachback"],
            "Propietario" => ["nombre"=>"Lucía Fernández","ciudad"=>"León","direccion"=>"Blvd López Mateos"]
        ],
        "EFG4444" => [
            "Auto" => ["marca"=>"MERCEDES","modelo"=>2020,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"Fernando Ruiz","ciudad"=>"CDMX","direccion"=>"Santa Fe"]
        ],
        "HIJ5555" => [
            "Auto" => ["marca"=>"VOLKSWAGEN","modelo"=>2018,"tipo"=>"camioneta"],
            "Propietario" => ["nombre"=>"Paola Castro","ciudad"=>"Puebla","direccion"=>"San Manuel"]
        ],
        "KLM6666" => [
            "Auto" => ["marca"=>"HYUNDAI","modelo"=>2020,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"Ricardo Gómez","ciudad"=>"Mérida","direccion"=>"Av Colón"]
        ],
        "NOP7777" => [
            "Auto" => ["marca"=>"TESLA","modelo"=>2022,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"Valeria Díaz","ciudad"=>"Cancún","direccion"=>"Zona Hotelera"]
        ],
        "QRS8888" => [
            "Auto" => ["marca"=>"PEUGEOT","modelo"=>2019,"tipo"=>"hachback"],
            "Propietario" => ["nombre"=>"Andrés Morales","ciudad"=>"CDMX","direccion"=>"Coyoacán"]
        ],
        "TUV9999" => [
            "Auto" => ["marca"=>"SEAT","modelo"=>2021,"tipo"=>"sedan"],
            "Propietario" => ["nombre"=>"Daniela Jiménez","ciudad"=>"Puebla","direccion"=>"Angelópolis"]
        ]
    ];
    return $autos;
}

function buscarAuto($matricula) {
    $autos = registrarAutos();
    if(isset($autos[$matricula])) {
        return $autos[$matricula];
    } else {
        return null;
    }
}
?>
