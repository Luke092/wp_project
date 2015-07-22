<?php

    use RSSAggregator\model\session;
    use RSSAggregator\model\user;
    use RSSAggregator\model\categories;
    use RSSAggregator\model\category;
    use RSSAggregator\model\feed;

    function __autoload($class) {

        // convert namespace to full file path
        if (strpos($class, 'SimplePie') !== 0) {
            $class = 'classes/' . str_replace('\\', '/', $class) . '.php';
        } else {
            $class = 'php/library/' . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        }
        require_once($class);
    }
?>
<script type="text/javascript" src="./lib/jquery-ui.min.js"></script>
<script type="text/javascript" src="./lib/json2.js"></script>
<script type="text/javascript" src="./js/organize.js"></script>

<div id="mainArea">
<?php
    session::start();
    if(!session::user_is_logged()){
?>
        <script>
            alert("Sessione scaduta! Effettuare nuovamente l'accesso.");
            location.href = "./index.php";
        </script>
<?php
    }else{
        $email = session::get_info("email");
        $categories = user::getCategories($email)->get_array();
        foreach ($categories as $category) {
            $feeds = $category->get_array();
            if (count($feeds) > 0) {
    ?>
                <div id="<?php echo 'category_'.$category->getName().'_contents' ?>" class="itemContentsHolder">
                    <h2 class="categoryHeader">
                        <div class="categoryName">
                            <?php echo $category->getName() ?>
                        </div>
                        <div class="modCanc">
                            <img src="./img/utils/icon-edit.png" class="editCat"/>
                            <img src="./img/utils/icon-bury.png" class="removeCat"/>
                        </div>
                    </h2>
                    <div class="subscriptionContentsHolder">
                    <?php
                        foreach ($feeds as $feed){
                    ?>
                        <div class="subscriptionContents">
                            <img src="<?php echo $feed->getIconURL() ?>" class="feedIcon" />
                            <div class="feedName">
                                <?php echo trim($feed->getName()) ?>
                            </div>
                            <div class="modCanc">
                                <img src="./img/utils/icon-bury.png" class="removeFeed" />
                            </div>
                            <div class="feedId">
                                <?php echo 'a'.$feed->getId().'a' ?>
                            </div>
                        </div>
                    <?php
                        }
                    ?>
                    </div>
                </div>
    <?php
            }
        }
    ?>
        <div class='itemContentsHolder'>
            <h2 class="categoryHeader">
                <div class="categoryName">Nuova categoria</div>
            </h2>
            <div class="newCategory">Trascina un feed qui per creare una nuova categoria</div>
        </div>
    </div>
<?php
    }
?>