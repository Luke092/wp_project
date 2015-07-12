<?php
use RSSAggregator\model\session;
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

?>
<div id="search-bar">
    <form>
        <span>
            <input type="text" class="search" name="search-token" placeholder="Cerca feed per categoria, nome o URL">
        </span>
    </form>
</div>
<?php
    $default_categories = categories::getCategories(categories::$DEFAULT_CAT);
    $default_categories_array = $default_categories->get_array();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["catName"])){
            $cat_name = $_POST["catName"];
            visualize_default_feeds($default_categories, $cat_name);
        }
        else if(isset($_POST["feedId"])){
            $feed_id = $_POST["feedId"];
            session::start();
            $email = session::get_info("email");
            show_category_choice($feed_id, $email);
        }
        else if(isset($_POST["back-to-cat"])){
            visualize_default_categories($default_categories_array);
        }
    }
    else{
        visualize_default_categories($default_categories_array);
    }
?>
<script type="text/javascript" src="./js/add_content.js"></script>