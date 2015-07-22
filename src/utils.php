<?php
require_once("./config.php");

//////////////////////////////////////////////////////////////
//////////////// login and register validation ///////////////
//////////////////////////////////////////////////////////////

function show_form_errors($errors){
    if(!empty($errors)){
        global $USER_MESSAGES;
        echo $USER_MESSAGES[0].":<ul><li>" . implode("</li><li>", $errors) . "</li></ul>";
    }
}

function is_valid_email($email){
    return preg_match('/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/', $email);
}

function is_empty_field($field){
    return (strlen($field) == 0) || (preg_match('/^[[:space:]]+$/', $field));
}


//////////////////////////////////////////////////////////////
/////////////////// XHTML reader functions ///////////////////
//////////////////////////////////////////////////////////////

function date_transform($date_to_transf){
    global $DAYS, $MONTHS;
    $date_parts = explode(" ", $date_to_transf);
    $day = $DAYS[$date_parts[0]];
    $date = $date_parts[1];
    $month = $MONTHS[$date_parts[2]];
    $year = $date_parts[3];
    $hour = $date_parts[4];
    $minutes = $date_parts[5];
    return $day.' '.$date.' '.$month.' '.$year.', '.$hour.':'.$minutes;
}

function get_favicon($url){
    $api_url = FAVICON_API_URL;
    $domain = get_domain($url);
    if(trim($domain) == "")
        return DEF_FEED_ICON_PATH;
    return $api_url . $domain;
}

function get_domain($url){
    $host = parse_url($url, PHP_URL_HOST);
    return $host;
}


function get_feed_icon_url($url){
    $rss = new SimplePie();
    $rss->set_feed_url($url);
    $rss->init();
    $icon_url = get_favicon($rss->get_base());
    if(isset($icon_url) && $icon_url != null)
        $res = $icon_url;
    else
        $res = DEF_FEED_ICON_PATH;
    return $res;
}

function get_feed_description($rss){
    $desc = trim(htmlspecialchars_decode(strip_tags($rss->get_description())));
    $res = null;
    if($desc == null || $desc == "")
        $res = "Descrizione non disponibile";
    else if(strlen($desc) > MAX_LENGTH_DESC)
        $res = substr($desc, 0, MAX_LENGTH_DESC).'...';
    else
        $res = $desc;
    return $res;
}

function get_article_title($article){
    $title = $article != null ? htmlspecialchars_decode(strip_tags($article->get_title())) : null;
    $res = null;
    if($title == null || trim($title) == "")
        $res = "Titolo articolo non disponibile";
    else
        $res = $title;
    return $res;
}

function get_first_article_title($rss){
    return get_article_title($rss->get_item(0));
}

function get_article_image_url($article){
    $src = DEF_ARTICLE_IMAGE_PATH;
    $dom = new DOMDocument();
    @$dom->loadHTML($article->get_content());
    $image_tags = $dom->getElementsByTagName('img');
    if($image_tags->item(0) != null){
        $src = $image_tags->item(0)->getAttribute("src");
        list($width, $height) = @getimagesize($src);
        if($width < MIN_IMAGE_WIDTH || $height < MIN_IMAGE_HEIGHT)
            $src = DEF_ARTICLE_IMAGE_PATH;
    }
    return $src;
}

function get_full_article_description($article_content){
    $dom = new DOMDocument();
    @$dom->loadHTML($article_content);
    $xpath = new DOMXPath($dom);
    $text_nodes = $xpath->query('//text()');
    $body = "";
    foreach($text_nodes as $text_node){
        $body .= " ".$text_node->textContent;
    }
    return $body;
}

function get_first_article_link($rss){
    return $rss->get_item(0)->get_link();
}

function get_partial_article_description($article_content){
    $body = trim(get_full_article_description($article_content));
    $res = null;
    if($body == null || $body == "")
        $res = "Contenuto non disponibile";
    else if(strlen($body) > MAX_LENGTH_SUMMARY)
        $res = substr($body, 0, MAX_LENGTH_SUMMARY).'...';
    else
        $res = $body;
    return $res;
}

function get_articles_quantity($feed){
    $rss = new SimplePie();
    $rss->set_feed_url($feed->getURL());
    $rss->init();
    return $rss->get_item_quantity();
}

function get_articles_from_category($category){
    $articles = array();
    foreach($category->get_array() as $feed){
        $rss = new SimplePie();
        $rss->set_feed_url($feed->getURL());
        $rss->init();
        for($i = 0; $i < ARTICLES_PER_FEED; $i++)
            $articles[] = $rss->get_item($i);
    }
    usort($articles, 'compare_articles');
    return $articles;
}

function compare_articles($article1, $article2){
    return $article2->get_date('U') - $article1->get_date('U');
}

function get_articles_in_range($articles, $from, $n){
    $result = array();
    for($i = $from; ($i < $from + $n) && ($i < count($articles)); $i++)
        $result[] = $articles[$i];
    return $result;
}

function get_all_articles_for_home($categories){
    $articles = array();
    foreach($categories as $category){
        $articles = array_merge($articles, get_articles_from_category($category));
    }
    usort($articles, "compare_articles");
    return $articles;
}