<?php
    function __autoload($class_name){
        require_once $class_name . '.php';
    }

    session::start();
    if(session::user_is_logged())
        header("Location: ./home.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>RSS Aggregator</title>
        <link rel = "stylesheet" type = "text/css" href = "./css/generic.css">
        <link rel = "stylesheet" type = "text/css" href = "./css/index.css">
        <link rel = "stylesheet" type = "text/css" href = "./css/popup.css">
        <!--[if lt IE 7]>
                <style type="text/css">
                        #wrapper { height:100%; }
                </style>
        <![endif]-->
        
        <script type="text/javascript" src="./lib/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="./js/popup.js"></script>
        <script type="text/javascript" src="./js/ajax.js"></script>
        
    </head>
    <body>
        <div id="wrapper">
            <div id="header" align="center">
                <img src="img/utils/index_header.jpg"/>
            </div>
            <div id="content" align="center">
                <button id="login" value="#login-box">Accedi</button>&nbsp;&nbsp;
                <button id="register" value="#register-box">Registrati</button>
                <?php
                    require("./login_popup.php");
                    require("./register_popup.php");
                ?>   
            </div>
            <?php
                require("./footer.php");
            ?>
        </div>
    </body>
</html>