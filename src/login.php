<?php
    use RSSAggregator\model;
    require_once("./utils.php");
    require_once("./config.php");
    
    function __autoload($class) {

	// convert namespace to full file path
	$class = 'classes/' . str_replace('\\', '/', $class) . '.php';
	require_once($class);
    }
    
    session::start();
    $errors = array();
    
    if($_SERVER["REQUEST_METHOD"] == "GET")
        header("Location: ./index.php");
    else{
        // get data by form
        $email = $_POST["email"];
        $password = $_POST["password"];
        
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
        
        // if no errors occured, then authenticate user
        if(empty($errors)){
            $password = hash(HASHING_ALGORITHM, $password);
            // check if password is correct
            if(user::getPassword($email) == null)
                $errors[] = $ERROR_MESSAGES[9];
            else if($password !== user::getPassword($email))
                $errors[] = $ERROR_MESSAGES[6];
            else{
                session::set_info("login", true);
                session::set_info("email", $email);
            }
        }
        show_form_errors($errors);
    }