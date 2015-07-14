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

if ($decoded->type == "editCategory") {
    $oldCat = category::fetch_by_name($decoded->oldName);
    $newCat = category::fetch_by_name($decoded->newName);
    if ($newCat == null) {
        category::insert($decoded->newName);
        $newCat = category::fetch_by_name($decoded->newName);
    }
    dbUtil::update("ucf", ["c_id"], [$newCat["id"]], ["email", "c_id"], [$email, $oldCat["id"]]);
}

if ($decoded->type == "removeCategory") {
    $category = category::fetch_by_name($decoded->catName);
    dbUtil::delete("ucf", ["email", "c_id"], [$email, $category["id"]]);
}

if ($decoded->type == "editFeed") {
    $sql = "SELECT * FROM feeds WHERE id='" + $decoded->feedId + "'";
    $db = dbUtil::connect();
    $stmt = $db->query($sql);
    dbUtil::close($db);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    $feed = feed::fetch_by_name_url($decoded->oldName, $stmt[0]["url"]);
    feed::insert($decoded->newName, $result[0]["url"], null);
    $feed = feed::fetch_by_name_url($decoded->newName, $result[0]["url"]);

    dbUtil::update("ucf", ["f_id"], [$feed->getId()], ["email", "f_id"], [$email, $decoded->feedId]);

    $json = array();
    $json['oldId'] = $feed->getId();
    $json['newId'] = $decoded->feedId();
    $encoded = json_encode($json);
    die($encoded);
//    $oldFeed = feed::fetch_by_name_url($decoded->oldName, $decoded->feedURL);
//    feed::modifyName($oldFeed["id"], $decoded->newName);
}

if ($decoded->type == "feedCollision") {
    $sql = "SELECT * FROM `feeds` WHERE id='" . $decoded->feedId . "'";
    $db = dbUtil::connect();
    $stmt = $db->query($sql);
    dbUtil::close($db);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql1 = "SELECT * FROM feeds WHERE f_name='" + $decoded->newName + "' AND url = '" + $result[0]["url"] + "'";
    $db = dbUtil::connect();
    $stmt1 = $db->query($sql);
    dbUtil::close($db);
    $result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    var_dump($result1);
    $collision;
    if (count($result1) != 0) {
        $collision = 1;
    } else {
        $collision = 0;
    }

    $json = array();
    $json['collision'] = $collision;
    $encoded = json_encode($json);
    die($encoded);
}

if ($decoded->type == "removeFeed") {
    $sql = "DELETE FROM `ucf` WHERE email='" . $email . "' AND f_id='" . $decoded->feedId . "'";
    $db = dbUtil::connect();
    $stmt = $db->query($sql);
    dbUtil::close($db);
}

if ($decoded->type == "moveFeed") {
    $oldCat = category::fetch_by_name($decoded->oldCatName);
    $newCat = category::fetch_by_name($decoded->newCatName);
    $sql = "UPDATE `ucf` SET `c_id`='" . $newCat["id"] . "' WHERE email='" . $email . "' AND c_id ='" . $oldCat["id"] . "' AND f_id ='" . $decoded->feedId . "'";
    $db = dbUtil::connect();
    $stmt = $db->query($sql);
    dbUtil::close($db);
}

if ($decoded->type == "addCategory") {
    category::insert($decoded->catName);
}
?>