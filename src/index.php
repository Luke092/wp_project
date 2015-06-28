<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Home Page</title>
        <link rel = "stylesheet" type = "text/css" href = "./css/generic.css">
        <link rel = "stylesheet" type = "text/css" href = "./css/index.css">
        <link rel = "stylesheet" type = "text/css" href = "./css/login.css">
        <!--[if lt IE 7]>
                <style type="text/css">
                        #wrapper { height:100%; }
                </style>
        <![endif]-->
        
        <script type="text/javascript" src="lib/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="js/login.js"></script>
        
    </head>
    <body>
        <div id="wrapper">
            <div id="header" align="center">
                <img src="img/index_header.jpg"/>
            </div>
            <div id="content" align="center">
                <button id="login" value="#login-box">Accedi</button>&nbsp;&nbsp;
                <button id="register" value="#register-box">Registrati</button>
                
                <div id="login-box" class="login-popup">
                    <a href="#" class="close"><img src="img/close_pop.png" class="btn_close" title="Chiudi" alt="Close" /></a>
                    <form method="post" class="signin" action="#">
                        <fieldset class="textbox">
                            <label class="username">
                                <span>Email</span>
                                <input id="email" name="email" value="" type="text" autocomplete="on" placeholder="Email">
                            </label>
                            <label class="password">
                                <span>Password</span>
                                <input id="password" name="password" value="" type="password" placeholder="Password">
                            </label>
                            <button class="submit button" type="button">Accedi</button>
<!--                            <p>
                                <a class="forgot" href="#">Hai dimenticato la tua password?</a>
                            </p>        -->
                        </fieldset>
                    </form>
                </div>
                
                <div id="register-box" class="login-popup">
                    <a href="#" class="close"><img src="img/close_pop.png" class="btn_close" title="Chiudi" alt="Close" /></a>
                    <form method="post" class="signin" action="#">
                        <fieldset class="textbox">
                            <label class="username">
                                <span>Email</span>
                                <input id="email" name="email" value="" type="text" autocomplete="on" placeholder="Email">
                            </label>
                            <label class="password">
                                <span>Password</span>
                                <input id="password" name="password" value="" type="password" placeholder="Password">
                            </label>
                            <label class="password">
                                <span>Ripeti password</span>
                                <input id="re-password" name="password" value="" type="password" placeholder="Ripeti password">
                            </label>
                            <button class="submit button" type="button">Registrati</button>
                        </fieldset>
                    </form>
                </div>
                
            </div>
            <div id="footer">
                &copy; 2015 Programmazione Web | Autori: Michele Masciale, Luca Bettinelli e Matteo Mario
            </div>
        </div>        
    </body>
</html>