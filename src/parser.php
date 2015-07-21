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
}

if ($decoded->type == "removeCategory") {
    $category = $cats->getCatByName($decoded->catName);
    $json['success'] = $cats->remove_Category($category);
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
}

if ($decoded->type == "addCategory") {
    $json['success'] = $cats->add_Category($decoded->catName);
}

if ($decoded->type == "readFeed") {
    $stats = stat::getStat($email);
    $catArray = $cats->get_array();
    $json['categories'] = array();
    $json['numReadFeed'] = array();
    for ($i = 0; $i < count($catArray); $i++) {
        $json['categories'][] = $catArray[$i]->getName();
        $json['numReadFeed'][] = $stats->feed_count($catArray[$i]->getId(), stat::$SINCE_REGISTERED);
    }
}

if ($decoded->type == "sendWords") {
    $stats = stat::getStat($email);
    $catArray = $cats->get_array();
    $catId = $catArray[$decoded->classIndex]->getId();
    $json['text'] = $stats->get_wc_text($catId, stat::$SINCE_REGISTERED);
}

if ($decoded->type == "loadStopWords") {
    $stopWords = file_get_contents("./stopwords/stopwords_it_1.txt");
    $stopWords .= file_get_contents("./stopwords/stopwords_en_1.txt");
    if ($stopWords != false) {
        $json['stopWords'] = explode("\r\n", $stopWords);
    } else {
        $json['stopWords'] = array();
    }
}

$encoded = json_encode($json);
die($encoded);
?>
