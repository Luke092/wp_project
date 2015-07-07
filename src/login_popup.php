<script type="text/javascript" src="./js/login.js"></script>
<div id="login-box" class="access-popup">
    <div class="popup-title popup-header">Inserisci le tue credenziali per accedere alla tua home page</div><br>
    <div id="login-error" class="error popup-header"></div>
    <a href="#" class="close"><img src="img/utils/close_pop.png" class="btn_close" title="Chiudi" alt="Close" /></a>
    <form method="post" class="signin" action="">
        <fieldset class="textbox">
            <label class="username">
                <span>Email</span>
                <input id="login-email" name="email" value="" type="text" autocomplete="on" placeholder="Email">
            </label>
            <label class="password">
                <span>Password</span>
                <input id="login-password" name="password" value="" type="password" placeholder="Password">
            </label>
            <button class="submit button" type="button">Accedi</button>     
        </fieldset>
    </form>
</div>