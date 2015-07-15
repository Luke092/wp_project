<?php
use RSSAggregator\model\categories;
use RSSAggregator\model\category;
use RSSAggregator\model\feed;
use RSSAggregator\model\user;
use RSSAggregator\model\session;

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

function compare_feeds($feed1, $feed2){
    return $feed1->getId() - $feed2->getId();
}

$email = session::get_info("email");


$term = $_GET['term'];


$default_categories = categories::getCategories(categories::$DEFAULT_CAT);
$user_categories = categories::getCategories(categories::$USER_CAT, $email);

$feeds = $default_categories->searchFeedsByTitleURL($term, $term);
$user_feeds = $user_categories->searchFeedsByTitleURL($term, $term);

$result_feeds = array_udiff($feeds, $user_feeds, 'compare_feeds');

echo json_encode($result_feeds);