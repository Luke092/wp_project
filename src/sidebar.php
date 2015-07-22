<script type="text/javascript" src="js/sidebar.js"></script>

<?php
use RSSAggregator\model\categories;
use RSSAggregator\model\category;
use RSSAggregator\model\feed;
use RSSAggregator\model\user;
use RSSAggregator\model\session;

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

?>
<?php
    session::start();
    $email = session::get_info("email");
    $user_cats = user::getCategories($email);
    if(count($user_cats->get_array()) > 0){
?>
    <div style="overflow: hidden;" id="tv-commands" class="evidence">
        <div style="float: left;width: 20%;">
            <img src="./img/utils/collapse.png" class="icon"/>
        </div>
        <div style="float: left;">
            <span>Chiudi tutto</span>
        </div>
    </div><br>

    <?php

        foreach ($user_cats->get_array() as $cat){
    ?>
            <div class="category">
                <div class="navigation_arrow">
                    <img class="navigation" src="img/utils/down_arrow.png" />
                </div>
                <div  id="<?php echo $cat->getName() ?>" class="cName evidence" title="<?php echo $cat->getName(); ?>">
                    <span style="font-weight: bold;"><?php echo $cat->getName();?></span>
                </div>
                <?php
                    foreach ($cat->get_array() as $feed){
                ?>
                    <div id="<?php echo $feed->getId(); ?>" class="feed evidence">
                        <div style="clear:both; width: 100%">
                            <div style="float:left; width: 20%">
                                <img class="icon" alt="<?php echo $feed->getName() ?> icon" src="<?php echo $feed->getIconURL(); ?>"/>
                            </div>
                            <div class="fName" style="float:left; margin-top: 3px; margin-left: 3px;width: 75%;line-height: 15px" title="<?php echo $feed->getName(); ?>">
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
    }
    else{
?>
        <div class="sidebar-header">
            In questa sezione saranno elencati i feed organizzati per categoria.<br><br>
            Al momento non sei iscritto ad alcun feed.
        </div>
<?php
    }
?>