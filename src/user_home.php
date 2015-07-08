<?php
    require_once("./config.php");
    function __autoload($class_name){
        require_once $class_name . '.php';
    }
    
    session::start();
    if(!session::user_is_logged())
        header("Location: ./index.php");
    else{
        $email = session::get_info("email");
        $categories = user::getCategories($email);
        if($categories->count() == 0)
            header("Location: ./add_content.php");
        else{
?>
            
<?php
        }
    }
?>