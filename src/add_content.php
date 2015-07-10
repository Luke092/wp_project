<?php
    use RSSAggregator\model\categories;
    use RSSAggregator\model\category;
    use RSSAggregator\model\feed;
    
    require_once("./config.php");
    require_once("./utils.php");
    
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
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["catName"])){
        $cat_name = $_POST["catName"];
        $category = $default_categories->getCatByName($cat_name);
        $feeds = $category->get_array();
        foreach($feeds as $feed){
            $rss = new SimplePie();
            $rss->set_feed_url($feed->getURL());
            $rss->init();
            echo $rss->get_title().'<br>';
        }
    }
    else{
        $categories_names = array();
        $i = 0;
        echo div("Categorie", null, "table-header");
        $body = "<tr>";
        foreach($default_categories_array as $category){
            $feeds = $category->get_array();
            $category_name = $category->getName();
            $category = empty_div(DEF_CAT_DIR.$category_name.'.jpg', "minicover").div($category_name, null, "label");
            $body .= td(div($category, $category_name, "block"));
            if(++$i % DEF_CAT_PER_ROW == 0)
                $body .= "</tr><tr>";
        }
        $body .= "</tr>";
        echo table($body);
    }
?>
<script type="text/javascript" src="./js/add_content.js"></script>