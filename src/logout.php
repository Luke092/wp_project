<?php
    use RSSAggregator\model\session;
    
    function __autoload($class) {

    // convert namespace to full file path
    if (strpos($class, 'SimplePie') !== 0) {
        $class = 'classes/' . str_replace('\\', '/', $class) . '.php';
    } else {
        $class = 'php/library/' . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
    }
    require_once($class);
}

    require_once("./utils.php");
    require_once("./config.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title>Logout</title>
        <link rel = "stylesheet" type = "text/css" href = "./css/generic.css">
        <link rel="icon" href="./img/utils/favicon.png" type="image/png"/>
        <script type="text/javascript">
            function waitLogout(){
                setTimeout(function(){location.href = "./index.php";}, 2500);
            }
        </script>
    </head>
    <body onload="waitLogout()" class="logout-body">
        <?php
            session::start();
        ?>
            <div class="logout-text">
            <?php
                if(session::user_is_logged()){
            ?>
                    <div class="logout-ok">
                        <p><?php echo $USER_MESSAGES[11] ?></p>
                    </div>
            <?php
                }
                else{
            ?>
                    <div class="logout-error">
                        <p><?php echo $USER_MESSAGES[12] ?></p>
                    </div>
            <?php
                }
                echo $USER_MESSAGES[13];
            ?>
                <br><br>
                <span class="logout-redirect">
                    <?php echo $USER_MESSAGES[14] ?>
                    <a href="./index.php"><?php echo $USER_MESSAGES[15] ?></a>
                </span>
            </div>
        <?php
            $_SESSION = array();
            session::delete_cookie();
            session::destroy();
        ?>
    </body>
</html>