<?php
namespace EJEMPLOS\POO;

class Cabecera {
    private $titulo;
    private $ubicacion;

    public function __construct($title, $location) {
        $this->titulo = $title;
        $this->ubicacion = $location;
    }

    public function graficar() {
        $estilo = 'font-size: 40px; text-align: '.$this->ubicacion;
        echo '<div style="'.$estilo.'">';
            echo '<h4>'.$this->titulo.'</h4>';
        echo '</div>';
    }
}

class Cabecera2 {
    private $titulo;
    private $ubicacion;
    private $enlace;        // SE AGREGÓ

    public function __construct($title, $location, $link) { // SE MODIFICÓ
        $this->titulo = $title;
        $this->ubicacion = $location;
        $this->enlace = $link; // SE AGREGÓ
    }

    public function graficar() {
        $estilo = 'font-size: 40px; text-align: '.$this->ubicacion;
        echo '<div style="'.$estilo.'">';
            echo '<h4>';
                echo '<a href="'.$this->enlace.'">'.$this->titulo.'</a>'; //SE AGREGÓ
            echo '</h4>';
        echo '</div>';
    }
}
?>