<?php

class SessionUtils {

    static function startSessionIfNotStarted() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => 86400,
            ]);
        }
    }
    /*
    session_status(): Devuelve el estado actual de la sesión:
    PHP_SESSION_DISABLED: Las sesiones están deshabilitadas en el servidor.
    PHP_SESSION_NONE: No hay ninguna sesión activa en este momento.
    PHP_SESSION_ACTIVE: Hay una sesión activa.     
     */

    static function destroySession() {
        $_SESSION = array();

        if (session_id() != "" || isset($_COOKIE[session_name()]))
            setcookie(session_name(), '', time() - 2592000, '/');

        session_destroy();
    }

    static function setSession($userEmail, $userType, $idUser) {
        $_SESSION['user'] = $userEmail;
        $_SESSION['user_type'] = $userType;
        $_SESSION['id_user'] = $idUser;
        
        if (!isset($_SESSION['CREATED'])) {
            $_SESSION['CREATED'] = time();
        } else if (time() - $_SESSION['CREATED'] > 1800) {
            // session started more than 30 minutes ago
            session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
            $_SESSION['CREATED'] = time();  // update creation time
        }
    }

    static function loggedIn() {
        session_start([
            'cookie_lifetime' => 86400,
        ]);
        
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {//time() devuelve la hora actual en segundos desde el 1 de enero de 1970. $_SESSION['LAST_ACTIVITY']: Este valor almacena la marca de tiempo de la última actividad del usuario. Si han pasado más de 1800 segundos (30 minutos) desde la última actividad, se considera que la sesión ha expirado.
        
            // last request was more than 30 minutes ago
            session_unset();//unset $_SESSION variable for the run-time. Borra todas las variables de sesión.
            session_destroy();//destroy session data in storage
        }
        
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
        if (isset($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
    }
    
    static function getIdUser() {
        session_start();
        return $_SESSION['id_user'];
    }

}

?>
