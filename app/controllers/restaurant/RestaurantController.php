<?php
require_once(dirname(__FILE__) . '/../../../persistence/DAO/RestauranteDAO.php');
require_once(dirname(__FILE__) . '/../../models/validations/ValidationsRules.php');


$RestaurantController = new RestaurantController();

// Enrutamiento de las acciones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["accion"] == "modificar"){
        $RestaurantController->editAction();
    } else if ($_POST["accion"] == "buscarCategoria" && $_POST["indexDeOrigen"] == "public"){
        $inputBuscador = ucfirst(strtolower(trim($_POST["inputBuscador"])));
        header('Location: ../../public/views/index.php?tipoRestaurante=' .$inputBuscador);
        exit();
    } else if ($_POST["accion"] == "buscarCategoria" && $_POST["indexDeOrigen"] == "private"){
        $inputBuscador = ucfirst(strtolower(trim($_POST["inputBuscador"])));
        header('Location: ../../private/views/index.php?tipoRestaurante=' .$inputBuscador);
        exit();
    } else if($_POST["accion"] == "reservar"){
        $RestaurantController->createReservationAction();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["idParaBorrar"])) {
    $RestaurantController->deleteAction();
}


class RestaurantController {
    
    public function __construct() {
    }
    

    
    //Obtención de la lista completa de creatures
    function readAction() {
        $restaurantDAO = new RestauranteDAO();
        $results = $restaurantDAO->selectAll();
        
        return $results;
    }
    
    
    // Función encargada de crear nuevas ofertas
    function editAction() {
        
        // Obtención de los valores del formulario y validación
        $id = $_POST["idRestaurante"];
        $name = ValidationsRules::test_input($_POST["name"]);
        $image = ValidationsRules::test_input($_POST["picture"]);
        $menu = ValidationsRules::test_input($_POST["menu"]);
        $priceRange = ValidationsRules::test_priceInput($_POST["price"]);
        $idCategory = $_POST["categoriesRestaurant"];//Creo que no hace falta que valide este input porque las opciones se las doy yo al usuario, no dejo que me ingrese un valor cualquiera
        
        
        // Creación de objeto auxiliar   
        $restaurant = new Restaurant();
        
        $restaurant->setId($id);
        $restaurant->setName($name);
        $restaurant->setImage($image);
        $restaurant->setMenu($menu);
        $restaurant->setMinorPrice($priceRange[0]);
        $restaurant->setMayorPrice($priceRange[1]);
        $restaurant->setIdCategory($idCategory);
        
        //Creamos un objeto OfferDAO para hacer las llamadas a la BD
        $restaurantDAO = new RestauranteDAO();
        $restaurantDAO->update($restaurant);

        header('Location: ../../private/views/index.php');//Location me deja en la carpeta que contiene este fichero terminando en /
        exit();
    }
    
    
    function deleteAction() {
        $id = $_GET["idParaBorrar"];

        $restaurantDAO = new RestauranteDAO();
        $restaurantDAO->delete($id);

        header('Location: ../../private/views/index.php');//Location me deja en la carpeta que contiene este fichero terminando en /
        exit();
    }
    
    //Como esta funcion va a ser llamada desde el private index que ya va a tener un URL parameter de tipoRestaurante, podemos acceder dicho parameter
    function filterCategoryAction(){
        $nombreCategoria = ValidationsRules::test_input($_GET["tipoRestaurante"]);
        $restaurantDAO = new RestauranteDAO();
        $results = $restaurantDAO->filterRestaurantByCategory($nombreCategoria);
        
        if($results != null){
            return $results;
        } else {//En caso de que la consulta no devuelva elementos, redireccionamos al mismo index pero con otro URL parameter para imprimir el error de tipo de restaurante no encontrado
            header('Location: index.php?error=TipoRestntNoEncontrado');
            exit();
        }
        
    }
    
    function readCategoriesAction(){
        $restaurantDAO = new RestauranteDAO();
        $categoriesRestaurant = $restaurantDAO->getAllRestaurantCategories();
        return $categoriesRestaurant;
    }
    
    function createReservationAction(){
        // Obtención de los valores del formulario y validación
        $idRestaurante = (int)$_POST["idRestaurante"];
        $ipPeticion = $_POST["ipPeticion"];
        
        //Validamos el valor del input dateTimeResevation
        $datetime = $_POST['dateTimeReservation'] ?? '';//Cuando recuperamos el valor de un input type="datetime-local" nos devolverá un string con el siguiente formato: "2025-01-10T14:00" ,año mes y dia
        if (!empty($datetime)) {
            // Separamos fecha y hora usando explode
            [$fechaReserva, $horaReserva] = explode('T', $datetime);
            $resultadoValidacionDateTime = ValidationsRules::testDateTime($fechaReserva, $horaReserva);
            if($resultadoValidacionDateTime instanceof DateTime){
                // Convertimos el DateTime a string con el formato deseado
                $datetime = $resultadoValidacionDateTime->format('Y-m-d H:i') . ":00";
                
            } else {//Si no es datetime significa que hubo un error(en tipo string).
                header("Location: ../../public/views/restaurant/reservationForm.php?id=" .$idRestaurante. "&error=" .$resultadoValidacionDateTime);
                exit();
            }
            
        } else {
            
            header("Location: ../../public/views/restaurant/reservationForm.php?id=" .$idRestaurante. "&error=" . urlencode("Ingresá una fecha y hora."));
            exit();
        }
        
        
        //Validamos el valor del select numeroComensales
        $resultadoValidacionComensales = ValidationsRules::testNumComensales($_POST["numeroComensales"]);
        if(filter_var($resultadoValidacionComensales, FILTER_VALIDATE_INT) !== false){
            $numeroComensales = (int)$resultadoValidacionComensales;
        } else {
            header("Location: ../../public/views/restaurant/reservationForm.php?id=" .$idRestaurante. "&error=" .$resultadoValidacionComensales);
            exit();
        }
        
        
        $restaurantDAO = new RestauranteDAO();
        $restaurantDAO->insertReservation($idRestaurante, $datetime, $numeroComensales, $ipPeticion);
        header("Location: ../../public/views/index.php");
        exit();
    }
}


?>