<?php
    use RSSAggregator\model\session;
    
    function __autoload($class) {

	// convert namespace to full file path
	$class = 'classes/' . str_replace('\\', '/', $class) . '.php';
	require_once($class);
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
        <title>Feedbook</title>
        <link rel = "stylesheet" type = "text/css" href = "./css/generic.css">
        <link rel = "stylesheet" type = "text/css" href = "./css/index.css">
        <link rel = "stylesheet" type = "text/css" href = "./css/popup.css">
        <link rel="icon" href="./img/utils/favicon.png" type="image/png"/>
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
        <div class="wrapper">
            <div class="header" align="center">
                <img src="img/utils/index_header.png"/>
            </div>
            <div class="content" align="center">
                <div class="description">
                    Benvenuto su Feedbook, il portale che ti far&agrave; rimanere aggiornato
                    sulle notizie da tutto il mondo in tempo reale.
                </div>
                <noscript>
                    <br>
                    <h1 style="background-color: white;">Non puoi usare Feedbook perchè il tuo browser non supporta JavaScript</h1>
                </noscript>
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