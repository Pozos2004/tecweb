<?php
class Tabla {
    private $matriz = array();
    private $numFilas;
    private $numColumnas;
    private $estilo;

    public function __construct($rows, $cols, $style) {
        $this->numFilas = $rows;
        $this->numColumnas = $cols;
        $this->estilo = $style;
        
        
        for($i = 0; $i < $rows; $i++) {
            for($j = 0; $j < $cols; $j++) {
                $this->matriz[$i][$j] = '';
            }
        }
    }

    public function cargar($row, $col, $val) {
        if($row >= 0 && $row < $this->numFilas && $col >= 0 && $col < $this->numColumnas) {
            $this->matriz[$row][$col] = $val;
            return true;
        }
        return false;
    }

    private function inicio_table() {
        echo '<table style="'.$this->estilo.'">';
    }

    private function inicio_fila() {
        echo '<tr>';
    }

    private function mostrar_dato($row, $col) {
        echo '<td style="'.$this->estilo.'">';
        echo $this->matriz[$row][$col];
        echo '</td>';
    }

    private function fin_fila() {
        echo '</tr>';
    }

    private function fin_tabla() {
        echo '</table>';
    }

    public function graficar() {
        $this->inicio_table();
        for($i = 0; $i < $this->numFilas; $i++) {
            $this->inicio_fila();
            for($j = 0; $j < $this->numColumnas; $j++) {
                $this->mostrar_dato($i, $j);
            }
            $this->fin_fila();
        }
        $this->fin_tabla();
    }
}