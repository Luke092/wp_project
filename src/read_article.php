<?php
    use RSSAggregator\model\article;

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
$article = article::import_JSON($json);
echo $article->getText();