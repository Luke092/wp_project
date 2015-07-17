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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title>Logout</title>
        <link rel = "stylesheet" type = "text/css" href = "./css/generic.css">
        <script type="text/javascript">
            function waitLogout(){
                setTimeout(function(){location.href = "./index.php";}, 2500);
            }
        </script>
    </head>
    <body onload="waitLogout()" class="logout-body">
        <?php
            session::start();
            $text = "";
            if(session::user_is_logged())
                $text = div(p("Logout effettuato con successo."), ["class", "logout-ok"]);
            else
                $text = div(p("Logout non effettuato: non eri autenticato!"), ["class", "logout-error"]);
            $text .= "Verrai reindirizzato a breve alla pagina iniziale...";
            $index_link = a("qui", ["href", "./index.php"]);
            $text .= br(2).span("Se non vieni reindirizzato, clicca " . $index_link, ["class", "logout-redirect"]);
            echo div($text, ["class", "logout-text"]);
            $_SESSION = array();
            session::delete_cookie();
            session::destroy();
        ?>
    </body>
</html>