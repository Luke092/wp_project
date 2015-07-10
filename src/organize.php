<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta charset="ISO-8859-1">

        <?php
        use RSSAggregator\model\session;
        use RSSAggregator\model\user;
        use RSSAggregator\model\categories;
        use RSSAggregator\model\category;
        use RSSAggregator\model\feed;
        
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
        ?>

        <link rel = "stylesheet" type = "text/css" href = "./css/generic.css">
        <link rel="stylesheet" type="text/css" href="./css/organize.css">

        <script type="text/javascript" src="./lib/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="./lib/json2.js"></script>
        <script type="text/javascript" src="./js/ajax.js"></script>
        <script type="text/javascript" src="./js/organize.js"></script>

        <title>Organize</title>
    </head>

    <body>
        <div id="pageHeader"><h1 id="titleBar">Organize</h1></div>
        <div id="mainArea">
            <?php
            session::start();
            $email = session::get_info("email");
            $categories = categories::getCategories(categories::$USER_CAT, $email)->get_array();
            foreach ($categories as $category) {
                $feeds = $category->get_array();
                if (count($feeds) > 0) {
                    $html = "<div id=\"category_".$category->getName()."_contents\" class=\"itemContentsHolder\">";
                    $html .= "<h2>";
                    $html .= "<div class=\"label\">" . $category->getName() . "</div>";
                    $html .= "<div class=\"modCanc\"><img class=\"editCat\" src=\"./img/utils/icon-edit.png\"></img><img class=\"removeCat\" src=\"./img/utils/icon-bury.png\"></img></div>";
                    $html .= "</h2>";

                    foreach ($feeds as $feed) {
                        $html .= "<div class=\"subscriptionContents\">" . $feed->getName() . "</div>";
                        $html .= "<div class=\"modCanc\"><img class=\"editFeed\" src=\"./img/utils/icon-edit.png\"></img><img class=\"removeFeed\" src=\"./img/utils/icon-bury.png\"></img></div>";
                    }
                    $html .= "</div>";
                    echo $html;
                }
            }
            ?>
        </div>
        <?php
        require("./footer.php");
        ?>
    </body>
</html>