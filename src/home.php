<?php
    
    use RSSAggregator\model\session;
    
    function __autoload($class) {

	// convert namespace to full file path
	$class = 'classes/' . str_replace('\\', '/', $class) . '.php';
	require_once($class);
    }

    session::start();
    if(!session::user_is_logged())
        header("Location: ./index.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta charset="ISO-8859-1">
        <link rel="stylesheet" type="text/css" href="./css/generic.css">
        <link rel="stylesheet" type="text/css" href="./css/home.css">
        
        <script type="text/javascript" src="lib/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="js/ajax.js"></script>
        <script type="text/javascript" src="lib/jquery.zrssfeed.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function(){
                var xhr = myGetXmlHttpRequest();
                pageRequest(xhr, "./user_home.php", "POST");
            });
        </script>
        
        <title>Home page</title>
        <!--[if lt IE 7]>
		<style type="text/css">
			#wrapper { height:100%; }
		</style>
	<![endif]-->
    </head>
    <body> <!-- da modificare-->
        <div class="wrapper">
            <div class="header">
                <!-- Top menu for account name, link to profile and manage button -->
            </div>
            <div class="content">
                <div id="sidebar">
                    <p>paragraph</p>
                </div>
                <div id="page">
                    
                </div>
            </div>
            <div class="push"></div>
        </div>
        <?php
            require("./footer.php");
        ?>
    </body>
</html>
