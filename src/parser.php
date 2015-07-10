<?php

function __autoload($class_name) {
    require_once $class_name . '.php';
}

$decoded = json_decode($_GET['json']);

if ($decoded->type == "editCategory") {
//if newName doesn't exist yet, modify category oldName
    session::start();
    $email = session::get_info("email");
    $categories = categories::getCategories(categories::$USER_CAT, $email);
    $oldCat = $categories->getCatByName($decoded->oldName);
    $newCat = category::fetch_by_name($decoded->newName);
    if ($newCat == null) {
        category::modifyName($oldCat->getId(), $decoded->newName);
    } else {
        //query su ucf per modificare tutte le entry con vecchia categoria        
        dbUtil::update("ucf", ["c_id"], [$newCat["id"]], ["email", "c_id"], [$email, $oldCat->getId()]);
        category::delete($oldCat->getId());
    }
}
?>