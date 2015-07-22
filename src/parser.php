<?php

use RSSAggregator\model\session;
use RSSAggregator\model\user;
use RSSAggregator\model\categories;
use RSSAggregator\model\category;
use RSSAggregator\model\dbUtil;
use RSSAggregator\model\feed;
use RSSAggregator\model\stat;

function __autoload($class) {

    // convert namespace to full file path
    if (strpos($class, 'SimplePie') !== 0) {
        $class = 'classes/' . str_replace('\\', '/', $class) . '.php';
    } else {
        $class = 'php/library/' . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
    }
    require_once($class);
}

$decoded = json_decode($_GET['json']);

session::start();
$email = session::get_info("email");
$cats = user::getCategories($email);

$json = array();

if ($decoded->type == "editCategory") {
    $oldCat = $cats->getCatByName($decoded->oldName);
    $newCat = $cats->add_Category($decoded->newName);
    $json['success'] = category::update_UCF_data($oldCat->getId(), $newCat->getId(), $email);
    $stat = user::getStat($email);
    $stat->change_articles_cat_by_cat($oldCat->getId(), $newCat->getId());
}

if ($decoded->type == "removeCategory") {
    $category = $cats->getCatByName($decoded->catName);
    $json['success'] = $cats->remove_Category($category);
    $stat = user::getStat($email);
    $stat->remove_all_art_in_cat($category->getId());
}

if ($decoded->type == "removeFeed") {
    $cat = $cats->getCatByName($decoded->catName);
    $feed = $cat->getFeedById($decoded->feedId);
    $json['success'] = $cat->remove_Feed($feed);
}

if ($decoded->type == "moveFeed") {
    $oldCat = $cats->getCatByName($decoded->oldCatName);
    $newCat = $cats->add_Category($decoded->newCatName);
    $json['success'] = feed::update_UCF_data($email, $oldCat->getId(), $newCat->getId(), $decoded->feedId);
    $stat = user::getStat($email);
    $stat->change_articles_cat_by_feed($decoded->feedId, $newCat->getId());
}

if ($decoded->type == "addCategory") {
    $json['success'] = $cats->add_Category($decoded->catName);
}

if ($decoded->type == "readFeed") {
    $stats = user::getStat($email);
    $catArray = $cats->get_array();
    $from = $decoded->from;
    switch ($from) {
        case 'week':
            $form = stat::$LAST_WEEK;
            break;
        case 'month':
            $from = stat::$LAST_MONTH;
            break;
        case 'registration':
            $from = stat::$SINCE_REGISTERED;
            break;
        default:
            $from = stat::$SINCE_REGISTERED;
            break;
    }
    $json['categories'] = array();
    $json['numReadFeed'] = array();
    for ($i = 0; $i < count($catArray); $i++) {
        $json['categories'][] = $catArray[$i]->getName();
        $json['numReadFeed'][] = $stats->feed_count($catArray[$i]->getId(), $from);
    }
}

if ($decoded->type == "sendWords") {
    $stats = user::getStat($email);
    $catArray = $cats->get_array();
    $from = $decoded->from;
    switch ($from) {
        case 'week':
            $form = stat::$LAST_WEEK;
            break;
        case 'month':
            $from = stat::$LAST_MONTH;
            break;
        case 'registration':
            $from = stat::$SINCE_REGISTERED;
            break;
        default:
            $from = stat::$SINCE_REGISTERED;
            break;
    }
    $catId = $catArray[$decoded->classIndex]->getId();
    $json['text'] = $stats->get_wc_text($catId, $from);
}

if ($decoded->type == "loadStopWords") {
    $stopWords = file_get_contents("./stopwords/stopwords_it_1.txt");
    $stopWords .= file_get_contents("./stopwords/stopwords_en_1.txt");
    if ($stopWords != false) {
        $json['stopWords'] = explode(",", $stopWords);
    } else {
        $json['stopWords'] = array();
    }
}

$encoded = json_encode($json);
die($encoded);
?>
