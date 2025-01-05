<?php
require_once(dirname(__FILE__) . '/../conf/PersistentManager.php');

class UserDAO {

    //Se define una constante con el nombre de la tabla
    const USER_TABLE = 'users';

    //Conexión a BD
    private $connection = null;

    //Constructor de la clase
    public function __construct() {
        $this->connection = PersistentManager::getInstance()->get_connection();
    }

    
    
    public function selectAll() {
        $query = "SELECT * FROM " . UserDAO::USER_TABLE;
        $result = mysqli_query($this->connection, $query);
        
        $users= array();
        while ($userBD = mysqli_fetch_array($result)) {
            $user = new User();
            if ($userBD["type"] == "Gestor"){
                $user = new Gestor();
            }
            if ($userBD["type"] == "Admin"){
                $user = new Admin();
            }
            
            $user->setIdUser($userBD["idUser"]);
            $user->setEmail($userBD["email"]);
            $user->setPassword($userBD["password"]);
            
            array_push($users, $user);
        }
        
        return $users;
    }

    
    public function insert($user) {
        $query = "INSERT INTO " . UserDAO::USER_TABLE .
                " (email, password, type) VALUES(?,?,?)";//Creo una consulta PARAMETRIZADA
        $stmt = mysqli_prepare($this->connection, $query);
        
        //Declaro e inicializo las variables que voy a usar como parametros de la consulta parametrizada
        $email = $user->getEmail();
        $password = $user->getPassword();
        $type = $user->getType();
        
        mysqli_stmt_bind_param($stmt, 'sss', $email, $password, $type);
        return $stmt->execute();
    }

    public function getUserInformation($user) {
        $query = "SELECT idUser, email, password, type FROM " . UserDAO::USER_TABLE . " WHERE email=? AND password=?";
        $stmt = mysqli_prepare($this->connection, $query);
        
        $auxEmail = $user->getEmail();
        $auxPass = $user->getPassword();    
        $stmt->bind_param('ss', $auxEmail, $auxPass);//CREO que seria lo mismo que esto: mysqli_stmt_bind_param($stmt, 'ss', $auxEmail, $auxPass);
        $stmt->execute();//CREO que seria lo mismo que hacer esto: mysqli_stmt_execute($stmt);
        $result = $stmt->get_result();//CREO que seria lo mismo que hacer esto: mysqli_stmt_store_result($stmt)
        //mysqli_stmt_bind_param($stmt, 'ss', $auxEmail, $auxPass);
        //mysqli_stmt_execute($stmt);
        //mysqli_stmt_store_result($stmt); 
        //$rows = mysqli_stmt_num_rows($stmt);//Esta linea estaba sin usar
        
        if($result->num_rows == 1){
            $datosBD = $result->fetch_row();//Obtiene una fila del resultado como un array indexado.
            $user->setUserid($datosBD[0]);//[0] seria el idUser
            $user->setType($datosBD[3]);//[3] seria el type
            
            return $user;
        }         
        else{
            return null;
        }
    }
    
    
    public function selectById($id) {
        $query = "SELECT email, password FROM " . UserDAO::USER_TABLE . " WHERE idUser=?";
        $stmt = mysqli_prepare($this->connection, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $email, $password);

        $user = new User();
        while (mysqli_stmt_fetch($stmt)) {
            $user->setIdUser($id);
            $user->setEmail($email);
            $user->setPassword($password);
       }

        return $user;
    }
    
    
    
    public function update($creature) {
        $query = "UPDATE " . CreatureDAO::TABLE .
                " SET name=?, description=?, avatar=?, attackPower=?, lifeLevel=?, weapon=?"
                . " WHERE id=?";
        
        
        $stmt = mysqli_prepare($this->connection, $query);

        mysqli_stmt_bind_param(
        $stmt, 
        'ssssii',  // Tipos de los parámetros: 's' para string, 'i' para integer
        $creature->getName(), 
        $creature->getDescription(), 
        $creature->getAvatar(), 
        $creature->getAttackPower(), 
        $creature->getLifeLevel(), 
        $creature->getWeapon(), 
        $creature->getId()  // Asumiendo que 'getId()' obtiene el id de la criatura
        );

        // Ejecuta la consulta
        return mysqli_stmt_execute($stmt);
        
        //return $stmt->execute();
    }
    
    public function delete($id) {
        $query = "DELETE FROM " . UserDAO::USER_TABLE . " WHERE idUser =?";
        $stmt = mysqli_prepare($this->connection, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        return $stmt->execute();
    }

        
}

?>
