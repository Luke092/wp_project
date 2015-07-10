<?php
require_once("./config.php");

function visualize_default_categories($default_categories_array){
    $categories_names = array();
    $i = 0;
    echo div("Categorie", null, "table-header");
    $body = "<tr>";
    foreach($default_categories_array as $category){
        $feeds = $category->get_array();
        $category_name = $category->getName();
        $category = empty_div(DEF_CAT_DIR.$category_name.'.jpg', "minicover").div($category_name, null, "label");
        $body .= td(div($category, $category_name, "block"));
        if(++$i % DEF_CATS_PER_ROW == 0)
            $body .= "</tr><tr>";
    }
    $body .= "</tr>";
    echo table($body);
}

function visualize_default_feeds($default_categories, $cat_name){
    $category = $default_categories->getCatByName($cat_name);
    $feeds = $category->get_array();
    foreach($feeds as $feed){
        $rss = new SimplePie();
        $rss->set_feed_url($feed->getURL());
        $rss->init();
        $img = "<img class=\"img-art\" src=\"";
        $url = null !== $rss->get_item(0) ? $rss->get_item(0)->get_enclosure()->get_link() : "//?#";
        $url_default = "./img/utils/default-article.jpg";
        $img.= $url != "//?#" ? $url : $url_default;
        $img .= "\">";
        echo date_transform($rss->get_item(0)->get_date(DATE_FORMAT)).' '.$img.'<br>';
    }
}

function date_transform($date_format){
    global $DAYS, $MONTHS;
    $date_parts = explode(" ", $date_format);
    $day = $DAYS[$date_parts[0]];
    $date = $date_parts[1];
    $month = $MONTHS[$date_parts[2]];
    $year = $date_parts[3];
    $hour = $date_parts[4];
    $minutes = $date_parts[5];
    return $day.' '.$date.' '.$month.' '.$year.', '.$hour.':'.$minutes;
}