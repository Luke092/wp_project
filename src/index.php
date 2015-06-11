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
        
        <script type="text/javascript" src="library/jquery-2.1.4.js"></script>
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
                    <a href="#" class="close"><img src="http://www.alessioatzeni.com/wp-content/tutorials/jquery/login-box-modal-dialog-window/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
                    <form method="post" class="signin" action="#">
                        <fieldset class="textbox">
                            <label class="username">
                                <span>Email</span>
                                <input id="email" name="username" value="" type="text" autocomplete="on" placeholder="Username">
                            </label>
                            <label class="password">
                                <span>Password</span>
                                <input id="password" name="password" value="" type="password" placeholder="Password">
                            </label>
                            <button class="submit button" type="button">Sign in</button>
                            <p>
                                <a class="forgot" href="#">Forgot your password?</a>
                            </p>        
                        </fieldset>
                    </form>
                </div>
                
                <div id="register-box" class="login-popup">
                    <a href="#" class="close"><img src="http://www.alessioatzeni.com/wp-content/tutorials/jquery/login-box-modal-dialog-window/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
                    <form method="post" class="signin" action="#">
                        <fieldset class="textbox">
                            <label class="username">
                                <span>Email</span>
                                <input id="email" name="username" value="" type="text" autocomplete="on" placeholder="Username">
                            </label>
                            <label class="password">
                                <span>Password</span>
                                <input id="password" name="password" value="" type="password" placeholder="Password">
                            </label>
                            <label class="password">
                                <span>Insert Password Again</span>
                                <input id="re-password" name="password" value="" type="password" placeholder="Repeate Password">
                            </label>
                            <button class="submit button" type="button">Register</button>
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