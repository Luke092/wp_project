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
        if (strpos($class, 'SimplePie') !== 0)
        {
                $class = 'classes/' . str_replace('\\', '/', $class) . '.php';
        }
        else{
            $class = 'php/library/' . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        }
        require_once($class);
    }

    session::start();
    if(!session::user_is_logged())
        header("Location: ./index.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta charset="ISO-8859-1">
        <link rel="stylesheet" type="text/css" href="./css/generic.css">
        <link rel="stylesheet" type="text/css" href="./css/home.css">
        <link rel="icon" href="./img/utils/feed_icon.png" type="image/png"/>
        
        <script type="text/javascript" src="lib/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="js/ajax.js"></script>
        <script type="text/javascript" src="js/sidebar.js"></script>
        <script type="text/javascript" src="js/top-menu.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function(){
                var xhr = myGetXmlHttpRequest();
                pageRequest(xhr, "./user_home.php", "POST");
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
                    <div id="home" class="menu-entry">
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
                    <?php
                        $email = session::get_info("email");
                        $user_cats = user::getCategories($email);
                        foreach ($user_cats->get_array() as $cat){
                    ?>
                    <div class="category">
                        <div class="navigation_arrow">
                            <img class="navigation" src="img/utils/down_arrow.png" />
                        </div>
                        <div  id="<?php echo $cat->getName() ?>" class="cName">
                            <span style="font-weight: bold;"><?php echo $cat->getName();?></span>
                        </div>
                        <?php
                            foreach ($cat->get_array() as $feed){
                                $rss = new SimplePie();
                                $rss->set_feed_url($feed->getURL());
                                $rss->init();
                        ?>
                            <div id="<?php echo $feed->getId(); ?>" class="feed">
                                <div style="clear:both; width: 100%">
                                    <div style="float:left; width: 20%">
                                        <img class="icon" alt="<?php echo $feed->getName() ?> icon" src="<?php echo get_feed_icon_url($rss) ?>"/>
                                    </div>
                                    <div style="float:left; margin-top: 3px; margin-left: 3px;width: 75%;line-height: 15px">
                                        <span><?php echo $feed->getName();?></span>
                                    </div>
                                </div>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                    <?php
                        }
                    ?>
                </div>
                <div id="page">
                    
                </div>
            </div>
            <div class="push"></div>
        </div>
        <?php
            require("./footer.php");
        ?>
    </body>
</html>
