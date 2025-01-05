<?php
require_once(dirname(__FILE__) . '/../../controllers/restaurant/RestaurantController.php');

$RestaurantController = new RestaurantController();

if(isset($_GET["tipoRestaurante"])){
    $restaurantes = $RestaurantController->filterCategoryAction();//../private/views/index.php?error=TipoRestntNoEncontrado
} else {
    $restaurantes = $RestaurantController->readAction();
}

if (isset($_GET["error"]) && $_GET["error"] == "TipoRestntNoEncontrado"){
    $error = "El tipo de restaurante que ingresaste no existe.";
} else if (isset($_GET["error"]) && $_GET["error"] == "ErrorLogin"){
    $errorLogin = "Credenciales Incorrectas";
}

$projectFullPath = dirname(__FILE__);
$projectRelativePath = str_replace('C:\\xampp\\htdocs\\', 'http://localhost/', $projectFullPath);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>El Tenedor 4V</title>
    <!-- Bootstrap Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?php echo $projectRelativePath. "/index.php"?>">El Tenedor 4V</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="ms-auto d-flex" id="form-login"><!-- TODO este div creo que lo puedo borrar-->
            <span>
                <?php if (isset($errorLogin)) {echo $errorLogin;} ?>
            </span>
            <form class="form-inline d-flex" role="form" method="POST" action="../../controllers/user/UserController.php">
                <input type="hidden" name="accion" value="iniciarSesionUser">
                <input class="form-control" type="text" name="userEmail" placeholder="Email" aria-label="User" id="input-login">
                <input class="form-control" type="password" name="pass" placeholder="Password" aria-label="Password" id="input-pass">
                <button class="btn btn-outline-success d-flex align-items-center" type="submit" id="btn-login"><i class="bi bi-door-open px-1"></i> Acceder</button>
            </form> 
            <a href="<?php echo $projectRelativePath. "/user/signup.php";?>" class="mx-3 text-secondary" >Registrarse</a>
        </div>
      </div>
    </div>
  </nav>

<!-- Page Content -->
<div class="container-fluid bg-info mb-5">
    <div class="row py-2">
        <div class="col-md-3">
            <img class="img-fluid rounded" src="../../../assets/img/logo.png" alt="">
        </div>
        <div class="col">
            <h1 class="display-3">Descubra y reserva el mejor restaurante</h1>
            <p class="lead">una aplicación de 4Vientos.</p>
            <form class="input-group" method="POST" action="../../controllers/restaurant/RestaurantController.php">
                <input type="hidden" name="accion" value="buscarCategoria"/>
                <input type="hidden" name="indexDeOrigen" value="public"/>
                <input type="text" name="inputBuscador" class="form-control" />
                <button class="btn btn-primary" type="submit" >Buscar</button>
            </form>
            <p class="fw-bold text-danger my-1"><?php if(isset($error)){echo $error;} ?></p>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="container mtop-5">
    <div class="row">
        
        <?php
            for($i = 0; $i < sizeof($restaurantes); $i++){
                echo $restaurantes[$i]->publicRestauranteHTML();
            }
        ?>
        
    </div>
</div>

<footer class="footer">
    <div class="">
        <span class=""> Cuatrovientos </span>
    </div>
</footer>
<!-- JS Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</body>

</html>


