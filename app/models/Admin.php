<?php

require_once(dirname(__FILE__) . '/User.php');
class Admin extends User {

    public function __construct() {
        parent::__construct("Admin");//parent: Hace referencia a la clase padre (en este caso, User). __construct("Admin"): Llama al constructor de la clase padre (User) y le pasa el argumento "Admin".
    }
}
?>