<?php



class ValidationsRules {

    
    /*
    Esta función test_input está diseñada para procesar y sanitizar una entrada de usuario antes de utilizarla en una aplicación, como al trabajar con formularios o bases de datos.
    */
    public static function test_input($datoAValidar) {
        
        //Removes whitespace and other predefined characters from both sides of a string.
        $datoAValidar = trim($datoAValidar);
        //This PHP function returns a string with backslashes in front of each character that needs to be quoted in a database query.
        $datoAValidar = addslashes($datoAValidar);
        
        //The htmlspecialchars() function converts some predefined characters to HTML entities. Convierte caracteres especiales(ej: &; <, >) en su equivalente como entidades HTML(ej: &amp; &lt; &gt;), evitando que se interpreten como código HTML o JavaScript. Esto protege contra ataques de inyección de scripts (XSS).
        $datoAValidar = htmlspecialchars($datoAValidar);
        return $datoAValidar;
    }
    

    
    public static function test_inputURL($datoAValidar) {
        
            if (filter_var($datoAValidar, FILTER_VALIDATE_URL)) {
                return true;
            } else {
                return "La URL no es válida. Por favor, ingresa una URL correcta.";
            }
    }

    public static function test_priceInput($datoAValidar){

        // Validamos el formato del rango con decimales
        if (preg_match('/^\d+\.\d{1,}-\d+\.\d{1,}$/', $datoAValidar)) {

            // Separamos los números
            list($start, $end) = explode('-', $datoAValidar);

            //Verificamos que el rango sea válido (el primer número es menor que el segundo)
            if ($start < $end) { // El rango es válido
                $priceResult = [$start, $end];
                return $priceResult;
            } else {
                return "El rango de precio no es válido. El primer número debe ser menor que el segundo.";
            }
        } else {
            return "Formato de precio no válido. Por favor ingrese el rango en el formato correcto con al menos un decimal para ambos precios (por ejemplo, 30.9-89.50).";
        }

    }
    
    
    public static function testUserType($datoAValidar) {
        if ($datoAValidar == "Gestor" || $datoAValidar == "Admin"){
            return $datoAValidar;
        }
        else{
            return "Gestor";//TODO calculo que esto sera como una validacion extra para establecer el valor por defecto de un usuario que intente registrarse. Lo que no entiendo es como se puede llegar a este caso si en el form de registrarse el profe le da solo dos opciones al usuario
        }
    }
    
    
    public static function testDateTime($fechaReserva, $horaReserva){
        
        $error = "";
        
        // Creamos un objeto de fecha/hora a partir de los parámetros recibidos
        $fechayhoraReserva = DateTime::createFromFormat('Y-m-d H:i', "$fechaReserva $horaReserva");//Creamos un objeto DateTime usando el formato Y-m-d H:i(año-mes-día horas:minutos). Si no se puede crear (por ejemplo, si el formato no coincide), devuelve false.
        //En PHP se pueden pasar variables dentro de comillas utilizando la interpolación de variables. Esto significa que cuando usas comillas dobles " " para definir una cadena, PHP automáticamente reemplazará las variables dentro de la cadena con su valor correspondiente. La interpolación de variables solo funciona dentro de comillas dobles (" "). Si usas comillas simples (' '), las variables no serán evaluadas, y se tratarán como texto literal.

        // Verificamos si la fecha y la hora son válidas
        if (!$fechayhoraReserva) {
            $error .= urlencode("La fecha u hora no son válidas.");
            return $error;
        }

        // Obtenemos la fecha y hora actuales
        $fechahoraActual = new DateTime();

        //Validamos si la fecha es posterior al día de hoy. Compara si la FECHA Y HORA de reserva es mayor que la fecha y hora actual.
        if ($fechayhoraReserva <= $fechahoraActual) {
            $error .= urlencode("La fecha debe ser posterior al día de hoy.");
            return $error;
        }

        //Validamos si la hora de la reserva es válida (14:00 o 21:00)
        $horasValidas = ['14:00', '21:00'];
        if (!in_array($horaReserva, $horasValidas)) {//in_array() verifica si el valor de $horaReserva está presente en el array $horasValidas
            $error = urlencode("La hora debe ser 14:00 o 21:00.");
            return $error;
        }

        return $fechayhoraReserva;
    }
    
    
    public static function testNumComensales($datoAValidar) {
        // Elimina espacios innecesarios
        $datoAValidar = trim($datoAValidar);

        // Verificamos si el dato es un número entero válido
        //FILTER_VALIDATE_INT: Es una constante de filtro que verifica si el valor es un número entero. Si no lo es, devuelve false.
        $esNumeroValido = filter_var($datoAValidar, FILTER_VALIDATE_INT, [
        'options' => [//array asociativo con reglas específicas para validar que el número entero este entre 1 y 10 inclusives
            'min_range' => 1,
            'max_range' => 10]
        ]);
        
        if ($esNumeroValido != false) {
            return $datoAValidar;//Devolvemos el número si es válido
        } else {
            return urlencode("Ingresá un numero de comensales válido, el máximo puede ser 10.");//Devuelve un mensaje de error si no es un entero válido
        }
    }
}


?>
