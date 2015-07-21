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

if (isset($_POST['newPasswd']) && isset($_POST['oldPasswd'])) {
    session::start();
    $email = session::get_info("email");
    $newPasswd = $_POST['newPasswd'];
    $oldPassword = $_POST['oldPasswd'];
    if (hash(HASHING_ALGORITHM, $oldPassword) == user::getPassword($email)) {
        user::modifyPassword($email, hash(HASHING_ALGORITHM, $newPasswd));
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $(".profile-modify").hide();
                $(".errors").hide();
                alert("Password cambiata con successo!");
            });
        </script>
        <?php
    } else {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $(".profile-modify").show();
                $(".errors").show();
                $(".errors").html("<span>Password errata!</span>");
                $("#oldPassword").css("borderColor", "red");
            });
        </script>
        <?php
    }
} else {
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".profile-modify").hide();
            $(".errors").hide();
        });
    </script>  
    <?php
}
?>

<link rel = "stylesheet" type = "text/css" href = "./css/c3.min.css">
<script type = "text/javascript" src = "./lib/jquery-2.1.4.js"></script>
<script src = "./lib/d3.min.js"></script>
<script src = "./lib/d3.layout.cloud.js"></script>
<script src = "./lib/c3.min.js"></script>
<script src = "./lib/wordfreq.js"></script>
<script src = "./lib/wordfreq.worker.js"></script>
<script src = "./lib/wordcloud2.js"></script>
<script src = "./lib/flip.js"></script>
<script type="text/javascript" src="./js/ajax.js"></script>
<script type="text/javascript" src="./js/user_statistic.js"></script>

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

    <div class='card'>
        <div class='front'>
            <div id='select_period'
                 <p>Visualizzazione dei dati relativi al periodo
                    <select id='from'>
                        <option value="week">ultima settimana</option>
                        <option value="month">ultimo mese</option>
                        <option value="registration">dalla registrazione a oggi</option>
                    </select>
                </p>
            </div>
            <div id="graph">
            </div>
        </div>
        <div class='back'>
            <div id="wordCloud">
                <canvas id='canvas_cloud'></canvas>
            </div>
        </div> 
    </div>
</div>