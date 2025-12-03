<?php
require_once __DIR__ . '/../vendor/autoload.php';

use TECWEB\MYAPI\DataBase;

class StatsDownloads extends DataBase {
    private $data;

    public function __construct($db, $user='root', $pass='') {
        $this->data = [];
        parent::__construct($db, $user, $pass);
    }

    public function build() {
        $this->data = [
            "por_dia_semana" => $this->porDiaSemana(),
            "por_hora"       => $this->porHora(),
            "por_marca"      => $this->porMarca()
        ];
    }

    private function porDiaSemana() {
        $resultados = [];
        $sql = "
            SELECT DAYNAME(fecha_hora) AS dia, COUNT(*) AS total
            FROM bitacora_descargas
            GROUP BY dia
            ORDER BY total DESC
        ";
        if ($res = $this->conexion->query($sql)) {
            while ($row = $res->fetch_assoc()) {
                $resultados[] = $row;
            }
            $res->free();
        }
        return $resultados;
    }

    private function porHora() {
        $resultados = [];
        $sql = "
            SELECT HOUR(fecha_hora) AS hora, COUNT(*) AS total
            FROM bitacora_descargas
            GROUP BY hora
            ORDER BY hora
        ";
        if ($res = $this->conexion->query($sql)) {
            while ($row = $res->fetch_assoc()) {
                $resultados[] = $row;
            }
            $res->free();
        }
        return $resultados;
    }

    private function porMarca() {
        $resultados = [];
        $sql = "
            SELECT p.marca AS marca, COUNT(*) AS total
            FROM bitacora_descargas b
            JOIN productos p ON b.producto_id = p.id
            GROUP BY p.marca
            ORDER BY total DESC
        ";
        if ($res = $this->conexion->query($sql)) {
            while ($row = $res->fetch_assoc()) {
                $resultados[] = $row;
            }
            $res->free();
        }
        return $resultados;
    }

    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}

header('Content-Type: application/json; charset=utf-8');

$stats = new StatsDownloads('marketzone');
$stats->build();
echo $stats->getData();
