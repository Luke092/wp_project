<?php
use RSSAggregator\model\dbUtil;

require_once("./visualize.php");

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

$db = dbUtil::connect();

$regex = "/(https?:\/\/)/";

$term = $_GET['term'];

preg_match_all($regex, $term, $matches);

if(count($matches) != 0){
    $term = "%" . $term . "%";
    $sql = "SELECT * FROM Feeds WHERE url LIKE ? AND default_cat <> \"NULL\"";
}
else{
    $term = "%" . $term . "%";
    $sql = "SELECT * FROM Feeds WHERE f_name LIKE ? AND default_cat <> \"NULL\"";
}

$stm = $db->prepare($sql);
$stm->execute(array($term));
$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $row){
    $rss = new SimplePie();
    $rss->set_feed_url($row['url']);
    $rss->init();
    $row['image_url'] = get_feed_icon_url($rss);
    $results[] = $row;
}
echo json_encode($results, JSON_UNESCAPED_SLASHES);
dbUtil::close($db);