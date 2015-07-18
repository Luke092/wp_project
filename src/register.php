<?php
    use RSSAggregator\model\session;
    use RSSAggregator\model\user;
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
        $repassword = $_POST["repassword"];
        
        // check email field
        if(is_empty_field($email))
            $errors[] = $USER_MESSAGES[1];
        else if(!is_valid_email($email))
            $errors[] = $USER_MESSAGES[2];
        
        // check password fields
        if(is_empty_field($password))
            $errors[] = $USER_MESSAGES[4];
        else if(strlen($password) < MIN_LENGTH_PASSWORD)
            $errors[] = $USER_MESSAGES[5];
        else if($password != $repassword)
            $errors[] = $USER_MESSAGES[6];
        
        // if no errors occured, then register user
        if(empty($errors)){
            $password = hash(HASHING_ALGORITHM, $password);
            switch(user::insert($email, $password)){
                case user::$ALREADY_PRESENT:
                    $errors[] = $USER_MESSAGES[3];
                    break;
                case user::$ERROR_INSERT:
                    $errors[] = $USER_MESSAGES[8];
                    break;
                case user::$CORRECT_INSERT:
                    session::set_info("login", true);
                    session::set_info("email", $email);
                    break;
            }
        }
        show_form_errors($errors);
    }