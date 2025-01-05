<?php
require_once(dirname(__FILE__) . '/User.php');

class Gestor extends User {

    public function __construct() {
        parent::__construct("Gestor");//parent: Hace referencia a la clase padre (en este caso, User). __construct("Gestor"): Llama al constructor de la clase padre (User) y le pasa el argumento "Gestor".
    }
}

?>