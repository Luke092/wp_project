<?php
    use RSSAggregator\model\article;
    use RSSAggregator\model\stat;
    use RSSAggregator\model\session;
    use RSSAggregator\model\user;

    function __autoload($class) {

        // convert namespace to full file path
        if (strpos($class, 'SimplePie') !== 0) {
            $class = 'classes/' . str_replace('\\', '/', $class) . '.php';
        } else {
            $class = 'php/library/' . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        }
        require_once($class);
}

$json = $_POST["article"];
$readonly = $_POST["readonly"];
$json->text = trim(preg_replace('/\u0026/', '&', $json->text));
$article = article::import_JSON($json);
session::start();
$email = session::get_info("email");
$stat = user::getStat($email);
if(!$stat->addArticle($article)){
    if($readonly == "false"){
        $stat->removeArticle($article);
    }
}