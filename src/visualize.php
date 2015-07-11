<?php
require_once("./config.php");

function visualize_default_categories($default_categories_array){
    $categories_names = array();
    $i = 0;
    echo div("Categorie", null, "table-header");
    $body = "<tr>";
    foreach($default_categories_array as $category){
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
    $i = 0;
    echo div($cat_name, null, "table-header");
    $body = "<tr>";
    foreach($feeds as $feed){
        $rss = new SimplePie();
        $rss->set_feed_url($feed->getURL());
        $rss->init();
        $feed_icon = empty_div(get_feed_icon_url($rss), "feed-icon");
        $feed_name = div($feed->getName(), null, "feed-name", $feed->getName());
        $feed_desc = div(get_feed_description($rss), null, "feed-description");
        $feed_add = div(img(null, get_add_feed_icon($default_categories, $feed), "add"), null, "add-feed");
        $feed_header = div($feed_icon.$feed_name.$feed_desc, null, "feed-header");
        $article_image = empty_div(get_first_article_image_url($rss), "article-image");
        $article_title = div(div(get_first_article_title($rss), null, "article-title"), null, "vignette");
        $feed_article = div($article_image.$article_title, null, "article-box");
        $feed_footer = div($feed_article, null, "feed-footer");
        $feed_box = div($feed_header.$feed_add.$feed_footer, null, "feed-box");
        $body .= td($feed_box);
        if(++$i % FEEDS_PER_ROW == 0)
            $body .= "</tr><tr>";
    }
    $body .= "</tr>";
    echo table($body);
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

function get_feed_icon_url($rss){
    $icon_url = $rss->get_image_url();
    $res = null;
    if(isset($icon_url) && $icon_url != null)
        $res = $icon_url;
    else
        $res = DEF_FEED_ICON_PATH;
    return $res;
}

function get_feed_description($rss){
    $desc = strip_tags($rss->get_description());
    $res = null;
    if($desc == null || trim($desc) == "")
        $res = "Descrizione non disponibile";
    else if(strlen($desc) > MAX_LENGTH_DESC)
        $res = substr($desc, 0, MAX_LENGTH_DESC).'...';
    else
        $res = $desc;
    return $res;
}

function get_add_feed_icon($default_categories, $feed){
    $title = $feed->getName();
    $url = $feed->getURL();
    $feeds = $default_categories->getFeedsByTitleURL($title, $url);
    $res = null;
    if(!empty($feeds))
        $res = "./img/utils/add-icon.png";
    else
        $res = "./img/utils/add-disabled-icon.png";
    return $res;
}

function get_first_article_image_url($rss){
    $article = $rss->get_item();
    $url = $article != null ? $article->get_enclosure()->get_link() : "//?#";
    return $url != "//?#" ? $url : DEF_ARTICLE_IMAGE_PATH;
}

function get_first_article_title($rss){
    $article = $rss->get_item();
    $title = $article != null ? strip_tags($article->get_title()) : null;
    $res = null;
    if($title == null || trim($title) == "")
        $res = "Descrizione articolo non disponibile";
    else if(strlen($title) > MAX_LENGTH_TITLE)
        $res = substr($title, 0, MAX_LENGTH_TITLE).'...';
    else
        $res = $title;
    return $res;
}