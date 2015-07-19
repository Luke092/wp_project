<?php

use RSSAggregator\model\session;
use RSSAggregator\model\user;

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
    $oldPassword = $_POST['newPasswd'];
    if ($oldPassword == user::getPassword($email)) {
        user::modifyPassword($email, $newPasswd);
    } else {
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
?>

<link rel = "stylesheet" type = "text/css" href = "./css/c3.min.css">
<link rel = "stylesheet" type = "text/css" href = "./css/user_profile.css">
<script type = "text/javascript" src = "./lib/jquery-2.1.4.js"></script>
<script src = "./lib/d3.min.js"></script>
<script src = "./lib/d3.layout.cloud.js"></script>
<script src = "./lib/c3.min.js"></script>
<script src = "./lib/wordfreq.js"></script>
<script src = "./lib/wordcloud2.js"></script>
<script type="text/javascript" src="./js/ajax.js"></script>
<!--<script type="text/javascript" src="./js/user_profile.js"></script>-->
<script type="text/javascript" src="./js/user_statistic.js"></script>

<div class="profile">
    <div class="info">
        <div class="profile-image">
            <img src="./img/utils/menu_icons/profile.png" />

        </div>
        <div class="profile-modify">
            <div class="errors">
            </div>
            <form>
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
                        <!--<input type="submit" value="Ok"/>-->
                        <button value="Ok" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class='stats'>
        <div id='barChartContainer'>
<!--            <img src='./img/utils/arrow_left_2.png' id='arrowLeft'></img>
            <img src='./img/utils/arrow_right_2.png' id='arrowRight'></img>-->
            <div id='barchart'></div>
            <div id='wordCloud'></div>
        </div>
    </div>
</div>
