<?php
    use RSSAggregator\model\session;
    use RSSAggregator\model\user;
    use RSSAggregator\model\categories;
    
    require_once("./config.php");
    function __autoload($class) {

	// convert namespace to full file path
	$class = 'classes/' . str_replace('\\', '/', $class) . '.php';
	require_once($class);
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