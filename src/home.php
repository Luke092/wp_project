<?php

use RSSAggregator\model\session;
use RSSAggregator\model\user;
use RSSAggregator\model\categories;
use RSSAggregator\model\category;
use RSSAggregator\model\feed;

require_once("./config.php");
require_once("./utils.php");
require_once("./visualize.php");

function __autoload($class) {

    // convert namespace to full file path
    if (strpos($class, 'SimplePie') !== 0) {
        $class = 'classes/' . str_replace('\\', '/', $class) . '.php';
    } else {
        $class = 'php/library/' . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
    }
    require_once($class);
}

session::start();
if (!session::user_is_logged())
    header("Location: ./index.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta charset="ISO-8859-1">
        <link rel="stylesheet" type="text/css" href="./css/generic.css">
        <link rel="stylesheet" type="text/css" href="./css/home.css">
        <link rel="stylesheet" type="text/css" href="./css/organize.css">
        <link rel="icon" href="./img/utils/favicon.png" type="image/png"/>

        <script type="text/javascript" src="lib/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="js/ajax.js"></script>
        <script type="text/javascript" src="js/sidebar.js"></script>
        <script type="text/javascript" src="js/top-menu.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                var xhr = myGetXmlHttpRequest();
                pageRequest(xhr, "./user_home.php", "POST");
                waitResponse();
            });
        </script>

        <title>Home page</title>
        <!--[if lt IE 7]>
                <style type="text/css">
                        #wrapper { height:100%; }
                </style>
        <![endif]-->
    </head>
    <body> <!-- da modificare-->
        <div class="wrapper">
            <div class="header">
                <!-- Top menu for account name, link to profile and manage button -->
                <div class="top-menu">
                    <div id="home" class="menu-entry selected-menu-entry">
                        <div>
                            <img src="./img/utils/menu_icons/home.png">
                        </div>
                        <div class="text-entry">
                            <span>Home</span>
                        </div>
                    </div>
                    <div id="add_content" class="menu-entry">
                        <div>
                            <img src="./img/utils/menu_icons/add.png">
                        </div>
                        <div class="text-entry">
                            <span>Aggiungi Feed</span>
                        </div>
                    </div>
                    <div id="organize" class="menu-entry">
                        <div>
                            <img src="./img/utils/menu_icons/organize.png">
                        </div>
                        <div class="text-entry">
                            <span>Organizza Feed</span>
                        </div>
                    </div>
                    <div id="logout" class="menu-entry">
                        <div>
                            <img src="./img/utils/menu_icons/logout.png">
                        </div>
                        <div class="text-entry">
                            <span>Logout</span>
                        </div>
                    </div>
                    <div id="profile" class="menu-entry">
                        <div>
                            <img src="./img/utils/menu_icons/profile.png">
                        </div>
                        <div class="text-entry">
                            <span>Profilo</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div id="sidebar">
                    <script type="text/javascript">
                        sidebar_reload();
                    </script>
                </div>
                <div id="page">
                </div>
            </div>
            <div class="push"></div>
        <?php
            require("./footer.php");
        ?>
        </div>
    </body>
</html>
