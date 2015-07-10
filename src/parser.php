<?php

use RSSAggregator\model\session;
use RSSAggregator\model\user;
use RSSAggregator\model\categories;
use RSSAggregator\model\category;

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

$decoded = json_decode($_GET['json']);

if ($decoded->type == "editCategory") {
//if newName doesn't exist yet, modify category oldName
    session::start();
    $email = session::get_info("email");
//    $categories = categories::getCategories(categories::$USER_CAT, $email);
    $oldCat = category::fetch_by_name($decoded->oldName);
    $newCat = category::fetch_by_name($decoded->newName);
    if ($newCat == null) {
        category::modifyName($oldCat["id"], $decoded->newName);
    } else {
        //query su ucf per modificare tutte le entry con vecchia categoria        
        $sql = "UPDATE `ucf` SET `c_id` = '" . $newCat["id"] . "' WHERE email = '" . $email . "' AND c_id = '" . $oldCat["id"] . "'";
        $db = dbUtil::connect();
        $stmt = $db->query($sql);
        dbUtil::close($db);
    }
}

?>