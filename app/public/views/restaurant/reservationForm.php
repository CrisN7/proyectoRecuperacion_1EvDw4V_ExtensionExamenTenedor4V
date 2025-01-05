<?php
//require_once(dirname(__FILE__) . '/../../../controllers/RestaurantController.php');

//$RestaurantController = new RestaurantController();

    
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else
    
    if (isset($_GET["error"])){
        $error = $_GET["error"];
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
      <a class="navbar-brand" href="<?php echo $projectRelativePath. "/../index.php"?>">El Tenedor 4V</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="restaurant/insert.php" id="nuevo_restaurante">Nuevo Restaurante</a>
            </li>
        </ul>
      </div>
    </div>
  </nav>

<!-- FORMULARIO DE RESERVAS -->
<form class="container" method="POST" action="../../../controllers/restaurant/RestaurantController.php">
    <input type="hidden" name="accion" value="reservar">
    <div class="row p-3">
      <label for="name" class="col-3 col-form-label">Selecciona la fecha y hora de reserva:</label>
      <div class="col-9">
        <input type="datetime-local" name="dateTimeReservation">
      </div>
    </div>
    <div class="row p-3">
      <label for="picture" class="col-3 col-form-label">Selecciona el número de comensales:</label>
      <div class="col-9">
        <select name="numeroComensales">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select>
      </div>
    </div>
    <input type="hidden" name="ipPeticion" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
    <input type="hidden" name="idRestaurante" value="<?php if(isset($id)) {echo $id;}?>">
    <p class="fw-bold text-danger my-1"><?php if(isset($error)){echo $error;} ?></p>
    <div class="row p-3">
      <div class="col-12 text-center">
        <button type="submit" class="btn btn-success">Reservar</button>
      </div>
    </div>
</form>

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


