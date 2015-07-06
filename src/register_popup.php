<script type="text/javascript" src="./js/register.js"></script>
<div id="register-box" class="access-popup">
    <div class="popup-title popup-header">Completa i seguenti campi per procedere con la registrazione</div><br>
    <div id="register-error" class="error popup-header"></div>
    <a href="#" class="close"><img src="img/close_pop.png" class="btn_close" title="Chiudi" alt="Close" /></a>
    <form method="post" class="signin" action="">
        <fieldset class="textbox">
            <label class="username">
                <span>Email</span>
                <input id="register-email" name="email" value="" type="text" autocomplete="on" placeholder="Email">
            </label>
            <label class="password">
                <span>Password</span>
                <input id="register-password" name="password" value="" type="password" placeholder="Password">
            </label>
            <label class="password">
                <span>Ripeti password</span>
                <input id="re-password" name="password" value="" type="password" placeholder="Ripeti password">
            </label>
            <button class="submit button" type="button">Registrati</button>
        </fieldset>
    </form>
</div>