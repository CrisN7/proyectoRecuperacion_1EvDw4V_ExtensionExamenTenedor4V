<?php

class Restaurant {
    
    private $id;
    private $name;
    private $image;
    private $menu;
    private $minorPrice;
    private $mayorPrice;
    private $idCategory;
    
    //ENTENDER PORQUE solo definimos un constructor vacio, y solamente creamos los getters y setters ------------
    public function __construct() {
        
    }
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getImage() {
        return $this->image;
    }

    public function getMenu() {
        return $this->menu;
    }

    public function getMinorPrice() {
        return $this->minorPrice;
    }

    public function getMayorPrice() {
        return $this->mayorPrice;
    }
    
    public function getIdCategory() {
        return $this->idCategory;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setImage($image){
        $this->image = $image;
    }

    public function setMenu($menu){
        $this->menu = $menu;
    }

    public function setMinorPrice($minorPrice){
        $this->minorPrice = $minorPrice;
    }

    public function setMayorPrice($mayorPrice){
        $this->mayorPrice = $mayorPrice;
    }

    public function setIdCategory($idCategory){
        $this->idCategory = $idCategory;
    }
    
    
    public function privateRestauranteHTML($user_type) {
        
        $projectFullPath = dirname(__FILE__);
        $projectRelativePath = str_replace('C:\\xampp\\htdocs\\', 'http://localhost/', $projectFullPath);
        
        $result = '<div class="col-12 col-md-6 col-lg-4 mb-4">';
        $result .= '<div class="card h-100">';
        $result .= '<img class="card-img-top" src="'. $this->getImage() .'" alt="Card image cap">';
        $result .= '<div class="card-body">';
        $result .= '<span class="badge bg-primary">'. $this->getMinorPrice() .'-' . $this->getMayorPrice() . '€</span>'; 
        $result .= '<h4 class="card-title">' . $this->getName() . '</h4>';
        $result .= '<p class="card-text">'. $this->getMenu() .'</p></div>'; 
        $result .= '<div class="card-footer d-flex justify-content-around">';
        
        if($user_type == "Gestor"){
            $result .= '<a href="' .$projectRelativePath. '/../private/views/restaurant/edit.php?idParaModificar=' .$this->getId(). '" class="btn btn-warning">Editar</a>';
        } else if($user_type == "Admin"){
            $result .= '<a href="' .$projectRelativePath. '/../private/views/restaurant/edit.php?idParaModificar=' .$this->getId(). '" class="btn btn-warning">Editar</a>';
            $result .= '<a href="' .$projectRelativePath. '/../controllers/restaurant/RestaurantController.php?idParaBorrar=' .$this->getId(). '" class="btn btn-danger">Borrar</a>';  
        }
        
        $result .=  '</div></div></div>';
        return $result;
    }

    public function publicRestauranteHTML() {
        
        $projectFullPath = dirname(__FILE__);
        $projectRelativePath = str_replace('C:\\xampp\\htdocs\\', 'http://localhost/', $projectFullPath);
        
        $result = '<div class="col-12 col-md-6 col-lg-4 mb-4">';
        $result .= '<div class="card h-100">';
        $result .= '<img class="card-img-top" src="'. $this->getImage() .'" alt="Card image cap">';
        $result .= '<div class="card-body">';
        $result .= '<span class="badge bg-primary">'. $this->getMinorPrice() .'-' . $this->getMayorPrice() . '€</span>'; 
        $result .= '<h4 class="card-title">' . $this->getName() . '</h4>';
        $result .= '<p class="card-text">'. $this->getMenu() .'</p></div>';
        
        $result .= '<div class="card-footer d-flex justify-content-around">';
        //$result .= '<a href="' .$projectRelativePath. '/../controllers/RestaurantController.php" class="btn btn-warning">Reservar</a>';
        $result .= '<a href="' .$projectRelativePath. '/../public/views/restaurant/reservationForm.php?id=' .$this->getId(). '" class="btn btn-warning">Reservar</a>';
        $result .=  '</div></div></div>';
        return $result;
    }
    
    
    
    
}

?>
