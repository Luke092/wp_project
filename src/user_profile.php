<link rel = "stylesheet" type = "text/css" href = "./css/user_profile.css">
<script type="text/javascript" src="./js/user_profile.js" />
<?php
    use RSSAggregator\model\session;
    use RSSAggregator\model\user;
    
    require_once ("./config.php");

    function __autoload($class) {

        // convert namespace to full file path
        if (strpos($class, 'SimplePie') !== 0) {
            $class = 'classes/' . str_replace('\\', '/', $class) . '.php';
        } else {
            $class = 'php/library/' . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        }
        require_once($class);
    }

    if(isset($_POST['newPasswd']) && isset($_POST['oldPasswd'])){
        session::start();
        $email = session::get_info("email");
        $newPasswd = $_POST['newPasswd'];
        $oldPassword = $_POST['oldPasswd'];
        if(hash(HASHING_ALGORITHM, $oldPassword) == user::getPassword($email)){
            user::modifyPassword($email, hash(HASHING_ALGORITHM,$newPasswd));
            echo '<script>'
                . '$(document).ready(function(){'
                    . '$(".profile-modify").hide();'
                    . '$(".errors").hide();'
                    . 'alert("Password cambiata con successo!")'
                . '})'
                . '</script>';
        }
        else{
            echo '<script>'
                . '$(document).ready(function(){'
                    . '$(".profile-modify").show();'
                    . '$(".errors").show();'
                    . '$(".errors").html("<ul><li>Password errata!</li></ul>");'
                    . '$("#oldPassword").css("borderColor", "red");'
                . '})'
                . '</script>';
        }
    }
    else {
        echo '<script>'
            . '$(document).ready(function(){'
                . '$(".profile-modify").hide();'
                . '$(".errors").hide();'
            . '})'
            . '</script>';
    }
?>

<div class="profile">
    <div class="info">
        <div class="profile-image">
            <img src="./img/utils/menu_icons/profile.png" />
            
        </div>
        <div class="profile-modify">
            <div class="errors">
            </div>
            <form action="" method="post">
                <div class="profile-form-row">
                    <div class="profile-form-label">
                        <label>Vecchia Password</label>
                    </div>
                    <div class="profile-form-input">
                        <input type="password" id="oldPassword" />
                    </div>
                </div>
                <div class="profile-form-row">
                    <div class="profile-form-label">
                        <label>Nuova Password</label>
                    </div>
                    <div class="profile-form-input">
                        <input type="password" id="newPassword" />
                    </div>
                </div>
                <div class="profile-form-row">
                    <div class="profile-form-label">
                        <label>Reinserisci Password</label>
                    </div>
                    <div class="profile-form-input">
                        <input type="password" id="rePassword" />
                    </div>
                </div>
                <div class="profile-form-row">
                    <div class="profile-form-submit">
                        <input type="submit" value="Ok"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="stats">
        <p>
            Qui andranno le statistiche!
        </p>
    </div>
</div>
