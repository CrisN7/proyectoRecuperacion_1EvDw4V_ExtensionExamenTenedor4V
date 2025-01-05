<?php

require_once(dirname(__FILE__) . '/../conf/PersistentManager.php');
require_once(dirname(__FILE__) . '/../../app/models/Restaurant.php');


class RestauranteDAO {
    
    
    //Se define una constante con el nombre de la tabla
    const TABLE = "restaurant";
    
    //Se define una constante con el nombre de la tabla de categoria
    const CATEGORYTABLE = "category";
    
    //Se define una constante con el nombre de la tabla de reservations
    const RESERVATIONSTABLE = "reservations";
    
    //Variable donde guardamos la conexion a la base de datos
    private $connection = null;
    
    //Constructor de la clase
    public function __construct() {
        $this->connection = PersistentManager::getInstance()->get_connection();//VER ESTO:
        /*CRIS:
         * 
         * 
        PersistentManager::getInstance():
        - PersistentManager es una clase que probablemente esté implementando el patrón Singleton.
        - getInstance() es un método estático de la clase PersistentManager que devuelve una instancia única de la misma, según el patrón Singleton.
        -  ->get_connection(): Una vez que se obtiene la instancia de PersistentManager a través de getInstance(), se llama al método get_connection() de esa instancia.
         ----------
        Explicación Completa:
        La clase PersistentManager tiene un método estático getInstance() que devuelve una única instancia de la clase, probablemente para mantener una conexión persistente a la base de datos o a un recurso compartido.
        La llamada a get_connection() se hace sobre la instancia devuelta, y este método devuelve una conexión (por ejemplo, un objeto de conexión a una base de datos).
        El resultado de get_connection() se asigna a la propiedad $connection de la instancia actual de la clase donde se está ejecutando este código.
        */
    }

    
    public function selectAll(){
        $query = "SELECT * FROM " . RestauranteDAO::TABLE;//NO TENER DOS ARCHIVOS CON EL NOMBRE DE LA MISMA CLASE
        $result = mysqli_query($this->connection, $query);//EJECUTAMOS LA CONSULTA A LA BD. CREO QUE SIEMPRE SE USA EL $ PARA USAR LAS VARIABLES. mysqli_query(): Ejecuta consultas SQL directamente, sin precompilación ni separación de parámetros (a direfencia del _prepare()).
        $restaurantes = array();//tambien podriamos hacer esto " = []; "
        while($restauranteBD = mysqli_fetch_array($result)){
            
            $restaurante = new Restaurant();
            
            $restaurante->setImage($restauranteBD["image"]);
            $restaurante->setId($restauranteBD["id"]);
            $restaurante->setMinorPrice($restauranteBD["minorprice"]);
            $restaurante->setMayorPrice($restauranteBD["mayorprice"]);
            $restaurante->setName($restauranteBD["name"]);
            $restaurante->setMenu($restauranteBD["menu"]);
            
            array_push($restaurantes, $restaurante);
        }
        
        return $restaurantes;
    }
    
    
    public function insert($restaurant){
        $query = "INSERT INTO " . RestauranteDAO::TABLE . " (name, image, menu, minorprice, mayorprice, idCategory) VALUES(?, ?, ?, ?, ?, ?)";//creamos una CONSULTA PARAMETRIZADA
        $statement = mysqli_prepare($this->connection, $query);
        
        //Creamos las variables que vamos a usar para inicializar los parametros de la consulta parametrizada
        $name = $restaurant->getName();
        $image = $restaurant->getImage();
        $menu = $restaurant->getMenu();
        $minorPrice = $restaurant->getMinorPrice();
        $mayorPrice = $restaurant->getMayorPrice();
        $idCategory = $restaurant->getIdCategory();
        
        mysqli_stmt_bind_param($statement, 'sssddi', $name, $image, $menu, $minorPrice, $mayorPrice, $idCategory);
        return $statement->execute();//Ver que tipo de dato devuelve esto
    }
    
    
    
    
    public function selectById($id) {
        $query = "SELECT * FROM " . RestauranteDAO::TABLE . " WHERE id=?";
        $stmt = mysqli_prepare($this->connection, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $idR, $name, $image, $menu, $minorPrice, $mayorPrice, $idCategory);
        
        $restaurant = new Restaurant();
        
        if (mysqli_stmt_fetch($stmt)) {
            $restaurant->setId($idR);
            $restaurant->setName($name);
            $restaurant->setImage($image);
            $restaurant->setMenu($menu);
            $restaurant->setMinorPrice($minorPrice);
            $restaurant->setMayorPrice($mayorPrice);
            $restaurant->setIdCategory($idCategory);
        }
        return $restaurant;
    }
    
    
    public function filterRestaurantByCategory($category){
        $query = "SELECT r.id, r.name, r.image, r.menu, r.minorprice, r.mayorprice FROM " .RestauranteDAO::TABLE. " r INNER JOIN " .RestauranteDAO::CATEGORYTABLE. " c ON r.idCategory = c.id WHERE c.name = ?";
        $stmt = mysqli_prepare($this->connection, $query);
        mysqli_stmt_bind_param($stmt, 's', $category);
        mysqli_stmt_execute($stmt);
        
        $filteredRestaurants = [];
        mysqli_stmt_bind_result($stmt, $idR, $name, $image, $menu, $minorPrice, $mayorPrice);//TODO  VER ESTO $idCategory
        
        $restaurant = new Restaurant();
        while(mysqli_stmt_fetch($stmt)){
            $restaurant->setId($idR);
            $restaurant->setName($name);
            $restaurant->setImage($image);
            $restaurant->setMenu($menu);
            $restaurant->setMinorPrice($minorPrice);
            $restaurant->setMayorPrice($mayorPrice);
            //$restaurant->setIdCategory($idCategory);
            
            array_push($filteredRestaurants, $restaurant);
        }

        if(sizeof($filteredRestaurants) > 0){
            return $filteredRestaurants;
        } else {
            return null;
        }
    }
    

    public function update($restaurant) {
        $query = "UPDATE " . RestauranteDAO::TABLE .
                " SET name=?, image=?, menu=?, minorprice=?, mayorprice=?, idCategory=?"
                . " WHERE id=?";
        $stmt = mysqli_prepare($this->connection, $query);
        
        $id = $restaurant->getId();
        $name = $restaurant->getName();
        $image = $restaurant->getImage();
        $menu = $restaurant->getMenu();
        $minorPrice = $restaurant->getMinorPrice();
        $mayorPrice = $restaurant->getMayorPrice();
        $idCategory = $restaurant->getIdCategory();
  
        mysqli_stmt_bind_param($stmt, 'sssddii', $name, $image, $menu, $minorPrice, $mayorPrice, $idCategory, $id);
        return $stmt->execute();
    }
    
    
    public function delete($id){
        $query = "DELETE FROM " . RestauranteDAO::TABLE . " WHERE id=?";
        $statement = mysqli_prepare($this->connection, $query);
        mysqli_stmt_bind_param($statement, "i", $id);
        
        return $statement->execute();
    }
    
    
    public function getAllRestaurantCategories(){
        $query = "SELECT * FROM " . RestauranteDAO::CATEGORYTABLE;
        $result = mysqli_query($this->connection, $query);
        $categories = array();//también podríamos hacer esto " = []; "
        
        while($eachCategory = mysqli_fetch_assoc($result)){
            array_push($categories, $eachCategory);
        }
        
        return $categories;
    }
    
    public function insertReservation($idRestaurant, $datetimeReserva, $numeroComensales, $ipPeticion){
        $query = "INSERT INTO " .RestauranteDAO::RESERVATIONSTABLE. " (id_restaurant, date, persons, IP) VALUES(?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->connection, $query);
        mysqli_stmt_bind_param($stmt, "isis", $idRestaurant, $datetimeReserva, $numeroComensales, $ipPeticion);
        $stmt->execute();
    }
}

?>
