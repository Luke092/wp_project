<?php

use RSSAggregator\model\session;
use RSSAggregator\model\user;
use RSSAggregator\model\categories;
use RSSAggregator\model\category;
use RSSAggregator\model\dbUtil;
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

$decoded = json_decode($_GET['json']);

session::start();
$email = session::get_info("email");
$cats = user::getCategories($email);

if ($decoded->type == "editCategory") {
    $oldCat = $cats->getCatByName($decoded->oldName);
    $newCat = $cats->add_Category($decoded->newName);
    category::update_UCF_data($oldCat->getId(), $newCat->getId(), $email);
}

if ($decoded->type == "removeCategory") {
    $category = $cats->getCatByName($decoded->catName);
    $cats->remove_Category($category);
}

if ($decoded->type == "removeFeed") {
    $cat = $cats->getCatByName($decoded->catName);
    $feed = $cat->getFeedById($decoded->feedId);
    $cat->remove_Feed($feed);
}

if ($decoded->type == "moveFeed") {
    $oldCat = $cats->getCatByName($decoded->oldCatName);
    $newCat = category::fetch_by_name($decoded->newCatName);
    feed::update_UCF_data($email, $oldCat->getId(), $newCat["id"], $decoded->feedId);
}

if ($decoded->type == "addCategory") {
    $res = $cats->add_Category($decoded->catName);
}
?>