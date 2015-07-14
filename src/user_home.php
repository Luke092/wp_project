<?php
    use RSSAggregator\model\session;
    use RSSAggregator\model\user;
    use RSSAggregator\model\categories;
    
    require_once("./config.php");
    require_once ("./visualize.php");
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
    else{
        $email = session::get_info("email");
        $categories = user::getCategories($email);
        if($categories->count() == 0){
            header("Location: ./add_content.php?back-to-cat=true");
        }
        else{
            
?>
            
<?php
            if(isset($_POST["feedId"]) && isset($_POST["catName"])){
                $feed_id = $_POST["feedId"];
                $cat_name = $_POST["catName"];
                $category = categories::getCategories(categories::$USER_CAT, $email)->getCatByName($cat_name);
                $feed = $category->getFeedById($feed_id);
                visualize_articles_by_feed($feed);
            }
            else if(isset($_POST["catName"])){
                $cat_name = $_POST["catName"];
                $category = categories::getCategories(categories::$USER_CAT, $email)->getCatByName($cat_name);
                visualize_articles_by_category($category);
            }
            else{
                visualize_all_articles($email);
            }
        }
    }
?>