<?php
use RSSAggregator\model\session;
use RSSAggregator\model\categories;
use RSSAggregator\model\category;
use RSSAggregator\model\feed;
use RSSAggregator\model\user;

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
if(!session::user_is_logged()){
?>
        <script>
            alert("Sessione scaduta! Effettuare nuovamente l'accesso.");
            location.href = "./index.php";
        </script>
<?php
}
?>
<div id="search-bar">
    <form>
        <span>
            <input id="search-input" type="text" class="search" name="search-token" placeholder="Cerca feed per nome o URL">
            <input id="search-input-id" type="hidden" value="-1">
        </span>
    </form>
</div>
<?php
    $default_categories = categories::getCategories(categories::$DEFAULT_CAT);
    $default_categories_array = $default_categories->get_array();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["catName"]) && !isset($_POST["feedId"])){
            $email = session::get_info("email");
            $cat_name = $_POST["catName"];
            visualize_default_feeds($default_categories, $cat_name, $email);
        }
        else if(isset($_POST["feedId"]) && !isset($_POST["catName"])){
            $feed_id = $_POST["feedId"];
            $email = session::get_info("email");
            if($feed_id == -1){
                $feed_url = $_POST['feedUrl'];
                $rss = new SimplePie();
                $rss->set_feed_url($feed_url);
                if(!$rss->init()){
?>
                    <h2 id="search-error"><?php echo $USER_MESSAGES[10] ?></h2>
<?php
                    return;
                }
                $f_name = $rss->get_title();                
                $res = feed::insert($f_name, $feed_url, "NULL");
                if($res != feed::$ERROR_INSERT){
                    $feed = new feed(feed::fetch_by_name_url($f_name, $feed_url)['id']);
                }
                else{
                    die($USER_MESSAGES[8]);
                }
            }
            else{
                $feed = new feed($feed_id);
            }
            show_category_choice($feed, $email);
        }
        else if(isset($_POST["feedId"]) && isset($_POST["catName"])){
            $cat_name = $_POST["catName"];
            $feed_id = $_POST["feedId"];
            $email = session::get_info("email");
            $user_categories = user::getCategories($email);
            $added_category = $user_categories->add_Category($cat_name);
            $feed = new feed($feed_id);
            $added_category->add_Feed_obj($feed);
?>
            <script type="text/javascript">
            var xhr = myGetXmlHttpRequest();
            var param = ["catName", "<?php echo $cat_name; ?>",
                         "feedId", "<?php echo $feed_id; ?>"];
            pageRequest(xhr, "./user_home.php", "POST", param);
            waitResponse();
            </script>
<?php
        }
        else if(isset($_POST["back-to-cat"])){
            visualize_default_categories($default_categories_array);
        }
    }
    else{
        visualize_default_categories($default_categories_array);
    }
?>
<link rel="stylesheet" href="lib/jqueryUi/jquery-ui.css">
<script type="text/javascript" src="lib/jquery-2.1.4.js"></script>
<script src="lib/jqueryUi/jquery-ui.js"></script>
<script type="text/javascript" src="./js/add_content.js"></script>
<script type="text/javascript" src="./js/search.js"></script>