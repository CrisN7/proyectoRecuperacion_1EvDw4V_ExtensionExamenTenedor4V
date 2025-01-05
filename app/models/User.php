<?php

class User {

    private $userId;
    private $email;
    private $password;
    private $type;
    
    
    public function __construct($type = null) {//type = null: significa que el constructor recibe un argumento llamado $type. El valor predeterminado es null, por lo que si no se proporciona un argumento al crear el objeto, la propiedad $type será inicializada como null
        $this->type = $type;
    }
  
    
    public function getUserid() {
        return $this->userId;
    }

    public function setUserid($userid) {
        $this->userId = $userid;
    }
    

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
    
    
    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }
}

?>
