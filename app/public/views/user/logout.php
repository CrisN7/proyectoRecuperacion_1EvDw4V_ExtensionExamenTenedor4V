<?php
// Gestión de sesión
require_once(dirname(__FILE__) . '/../../../../utils/SessionUtils.php');
SessionUtils::destroySession();
// redirección a la pantalla principal
header('Location: ../index.php');
?>