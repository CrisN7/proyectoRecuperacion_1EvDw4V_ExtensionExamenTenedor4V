<?php
require_once(dirname(__FILE__) . '/../../../persistence/DAO/RestauranteDAO.php');
require_once(dirname(__FILE__) . '/../../models/Restaurant.php');
require_once(dirname(__FILE__) . '/../../models/validations/ValidationsRules.php');



if($_SERVER["REQUEST_METHOD"] == "POST"){
    createAction();
}

// Función encargada de crear nuevos Restaurant
function createAction(){
    
    if(is_null($_POST["name"]) || trim($_POST["name"]) === ''){
        header("Location: ../../private/views/restaurant/insert.php?error=" . urlencode("El campo Nombre no puede estar vacío."));
        exit();
    } else {
        $name = ValidationsRules::test_input($_POST["name"]);
    }
    
    if(is_null($_POST["picture"]) || trim($_POST["picture"]) === ''){ 
        header("Location: ../../private/views/restaurant/insert.php?error=" . urlencode("El campo URL Imagen no puede estar vacío."));
        exit();
    } else {
        
        $resultadoValidacionURL = ValidationsRules::test_inputURL($_POST["picture"]);
        if($resultadoValidacionURL === true){
            $image = $resultadoValidacionURL;
        } else {//Si no es estrictamente true es porque hubo un error.
            header("Location: ../../private/views/restaurant/insert.php?error=" . urlencode($resultadoValidacionURL));
            exit();
        }
    }
    
    if(is_null($_POST["menu"]) || trim($_POST["menu"]) === ''){
        header("Location: ../../private/views/restaurant/insert.php?error=" . urlencode("El campo Menu no puede estar vacío."));
        exit();
    } else {
        $menu = ValidationsRules::test_input($_POST["menu"]);
    }
    
    if(is_null($_POST["price"]) || trim($_POST["price"]) === ''){
        header("Location: ../../private/views/restaurant/insert.php?error=" . urlencode("El campo Precio no puede estar vacío."));
        exit();
    } else {
        
        $resultadoValidacionPrice = ValidationsRules::test_priceInput($_POST["price"]);
        if(is_array($resultadoValidacionPrice)){
            $priceRange = $resultadoValidacionPrice;
        } else {//Si no es array es porque hubo un error.
            header("Location: ../../private/views/restaurant/insert.php?error=" . urlencode($resultadoValidacionPrice));
            exit();
        }
    }
    
    $idCategory = $_POST["categoriesRestaurant"];//Creo que no hace falta que valide este input porque las opciones disponibles se las doy yo al usuario
    
    $restaurant = new Restaurant();
    $restaurant->setName($name);
    $restaurant->setImage($image);
    $restaurant->setMenu($menu);
    $restaurant->setMinorPrice($priceRange[0]);
    $restaurant->setMayorPrice($priceRange[1]);
    $restaurant->setIdCategory($idCategory);
    
    $restaurantDAO = new RestauranteDAO();
    $restaurantDAO->insert($restaurant);
    
    header('Location: ../../private/views/index.php');
    exit();
}
?>
