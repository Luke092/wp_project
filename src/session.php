<?php
class session{
    public static function start(){
        session_start();
    }
    
    public static function destroy(){
        session_destroy();
    }
    
    public static function get_info($key){
       return $_SESSION[$key];
    }

    public static function set_info($key, $value){
       $_SESSION[$key] = $value;
    }

    public static function user_is_logged(){
       return (isset($_SESSION["login"]) && $_SESSION["login"] == true);
    }   
}