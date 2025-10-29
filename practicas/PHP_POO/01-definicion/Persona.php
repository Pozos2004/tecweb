<?php
class Persona {
    private $nombre;


    public function inicializar($name) {
        $this->nombre = $name;
    }

    public function graficar() {
        echo '<p>'.$this->nombre.'</p>';
    }
}
?>