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
//    $newCat = category::fetch_by_name($decoded->newCatName);
    $newCat = $cats->add_Category($decoded->newCatName);
//    $json['success'] = feed::update_UCF_data($email, $oldCat->getId(), $newCat["id"], $decoded->feedId);
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
        $json['numReadFeed'][] = $stats->feed_count($catArray[$i]->getId());
    }
}

if ($decoded->type == "sendWords") {
    $catArray = $cats->get_array();
    $catId = $catArray[$decoded->classIndex]->getId();
    $json['text'] = $stats->get_wc_text($c_id);
}

$encoded = json_encode($json);
die($encoded);
?>
