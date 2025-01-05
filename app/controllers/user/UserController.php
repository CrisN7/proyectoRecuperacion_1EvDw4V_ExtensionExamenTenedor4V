<?php
require_once(dirname(__FILE__) . '/../../../persistence/DAO/UserDAO.php');

require_once(dirname(__FILE__) . '/../../../app/models/User.php');
require_once(dirname(__FILE__) . '/../../../app/models/Gestor.php');
require_once(dirname(__FILE__) . '/../../../app/models/Admin.php');

require_once(dirname(__FILE__) . '/../../models/validations/ValidationsRules.php');
require_once(dirname(__FILE__) . '/../../../utils/SessionUtils.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if($_POST["accion"] == "crearUser"){
        //Llamo a la función en cuanto se redirige el action a esta página mediante metodo POST
        createAction();
    } else if ($_POST["accion"] == "iniciarSesionUser") {
        checkAction();
    }
    
}

// Función encargada de crear nuevos usuarios
function createAction() {
    // Obtención de los valores del formulario y validación.
    $email = ValidationsRules::test_input($_POST["email"]);
    $pass = ValidationsRules::test_input($_POST["password"]);
    $type = ValidationsRules::testUserType($_POST["type"]);
    
   
    // Creación de objeto auxiliar 
    $user = new User();
    if ($type  == "Gestor") {
        $user = new Gestor();
    }
    if ($type == "Admin") {
        $user = new Admin();
    }
    $user->setEmail($email);
    $user->setPassword($pass);
    
    //Creamos un objeto UserDAO para hacer las llamadas a la BD
    $userDAO = new UserDAO();
    $userDAO->insert($user);
    $user = $userDAO->getUserInformation($user);
    
    // Establecemos la sesión
    SessionUtils::startSessionIfNotStarted();
    SessionUtils::setSession($user->getEmail(), $user->getType(), $user->getUserid());
       
    header('Location: ../../private/views/index.php');//Location me deja en la carpeta que contiene este fichero terminando en /   
}


function checkAction() {

    $user = new User();
    $user->setEmail($_POST["userEmail"]);
    $user->setPassword($_POST["pass"]);

    //Creamos un objeto UserDAO para hacer las llamadas a la BD
    $userDAO = new UserDAO();
    $user = $userDAO->getUserInformation($user);
    if($user != null)
    {
        // Establecemos la sesión
        SessionUtils::startSessionIfNotStarted();
        SessionUtils::setSession($user->getEmail(), $user->getType(), $user->getUserid());
    
        header('Location: ../../private/views/index.php');
        exit();
    }
    else
    {
        header('Location: ../../public/views/index.php?error=ErrorLogin');
        exit();
    }
        
}