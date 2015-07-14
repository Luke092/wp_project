<?php

use RSSAggregator\model\session;
use RSSAggregator\model\user;
use RSSAggregator\model\categories;
use RSSAggregator\model\category;
use RSSAggregator\model\dbUtil;

function __autoload($class) {

    // convert namespace to full file path
    if (strpos($class, 'SimplePie') !== 0) {
        $class = 'classes/' . str_replace('\\', '/', $class) . '.php';
    } else {
        $class = 'php/library/' . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
    }
    require_once($class);
}

//session::start();
//$email = session::get_info("email");
////$categories = categories::getCategories(categories::$USER_CAT, $email);
//$oldCat = category::fetch_by_name("ciao");
//$newCat = category::fetch_by_name("nuova");
//if ($newCat == null) {
////    echo "id categoria: " . $oldCat["id"];
//    category::modifyName($oldCat["id"], "nuova");
//} else {
//    //query su ucf per modificare tutte le entry con vecchia categoria   
//    $sql = "UPDATE `ucf` SET `c_id` = '" . $newCat["id"] . "' WHERE email = '" . $email . "' AND c_id = '" . $oldCat["id"] . "'";
//    $db = dbUtil::connect();
//    $stmt = $db->query($sql);
//    dbUtil::close($db);
////    dbUtil::update("ucf", ["c_id"], [$newCat["id"]], ["email", "c_id"], [$email, $oldCat["id"]]);
//}
    session::start();
    $email = session::get_info("email");
    echo $email . "<br>";
    $category = category::fetch_by_name("provaCategoria");
    echo $category["id"] . "<br>";
    $sql = "DELETE FROM `ucf` WHERE email='" . $email . "' AND c_id='" . $category["id"] . "'";
    echo $sql;
    $db = dbUtil::connect();
    $stmt = $db->query($sql);
    dbUtil::close($db);
//}
?>
