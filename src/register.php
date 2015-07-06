<?php
    require_once("./utils.php");
    require_once("./config.php");
    
    function __autoload($class_name){
        require_once $class_name . '.php';
    }
    
    session::start();
    $errors = array();
    
    if($_SERVER["REQUEST_METHOD"] == "GET")
        header("Location: ./index.php");
    else{
        // get data by form
        $email = $_POST["email"];
        $password = $_POST["password"];
        $repassword = $_POST["repassword"];
        
        // check email field
        if(is_empty_field($email))
            $errors[] = $ERROR_MESSAGES[0];
        else if(!is_valid_email($email))
            $errors[] = $ERROR_MESSAGES[1];
        
        // check password fields
        if(is_empty_field($password))
            $errors[] = $ERROR_MESSAGES[3];
        else if(strlen($password) < MIN_LENGTH_PASSWORD)
            $errors[] = $ERROR_MESSAGES[4];
        else if($password != $repassword)
            $errors[] = $ERROR_MESSAGES[5];
        
        // if no errors occured, then register user
        if(empty($errors)){
            if(user::alreadySignedUp($email))
                $errors[] = $ERROR_MESSAGES[2];
            else
                $password = hash(HASHING_ALGORITHM, $password);
                user::insert($email, $password);
                session::set_info("login", true);
                session::set_info("email", $email);
        }
        show_form_errors($errors);
    }