<?php
use RSSAggregator\model\user;
use RSSAggregator\model\categories;
use RSSAggregator\model\category;
use RSSAggregator\model\feed;
use RSSAggregator\model\session;

require_once("./config.php");
require_once("./utils.php");

function visualize_default_categories($default_categories_array){
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

function visualize_default_feeds($default_categories, $cat_name, $email){
    $category = $default_categories->getCatByName($cat_name);
    $feeds = $category->get_array();
    $i = 0;
    echo div(span("Categorie > ", "back-to-cat", null).$cat_name, null, "table-header");
    $body = "<tr>";
    foreach($feeds as $feed){
        $feed_box = visualize_single_feed_box($feed, $default_categories, "feed-box", $email);
        $body .= td($feed_box);
        if(++$i % FEEDS_PER_ROW == 0)
            $body .= "</tr><tr>";
    }
    $body .= "</tr>";
    echo table($body);
}

function visualize_single_feed_box($feed, $default_categories, $class, $email = null){
    $rss = new SimplePie();
    $rss->set_feed_url($feed->getURL());
    $rss->init();
    $feed_icon = empty_div(get_feed_icon_url($rss), "feed-icon");
    $feed_name = div($feed->getName(), null, "feed-name", $feed->getName());
    $feed_desc = div(get_feed_description($rss), null, "feed-description");
    $feed_add = "";
    if($default_categories !== false){
        $user_categories = user::getCategories($email)->getCategories(categories::$USER_CAT, $email);
        $feed_add .= div(get_add_feed_icon($user_categories, $feed), null, "add-feed-box");    
    }
    $feed_header = div($feed_icon.$feed_name.$feed_desc, null, "feed-header");
    $article_image = empty_div(get_first_article_image_url($rss), "article-image");
    $article_title = div(div(get_first_article_title($rss), null, "article-title"), null, "vignette");
    $feed_article = div($article_image.$article_title, null, "article-box");
    $feed_footer = div($feed_article, null, "feed-footer");
    return div($feed_header.$feed_add.$feed_footer, $feed->getId(), $class);
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

function get_add_feed_icon($user_categories, $feed){
    $title = $feed->getName();
    $url = $feed->getURL();
    $feeds = $user_categories->getFeedsByTitleURL($title, $url);
    $res = "";
    if(empty($feeds))
        $res = img("add-feed", "./img/utils/add-icon.png", "add-icon", "Aggiungi feed");
    else
        $res = img("disabled-add-feed", "./img/utils/add-disabled-icon.png", "add-icon", "Aggiunto");
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

function show_category_choice($feed, $email){
    $user_categories = user::getCategories($email)->getCategories(categories::$USER_CAT, $email)->get_array();
    $feed_def_cat_index = $feed->getDefaultCat();
    $feed_box = visualize_single_feed_box($feed, false, "feed-box-cat-choice");
    $choices = '';
    if($feed_def_cat_index == null){
        $choices = print_all_user_categories($user_categories);
        $choices .= print_last_radio_button("", true, "Nuova categoria");
    }
    else{
        $feed_user_def_cat = user::getCategories($email)->getCatById($feed_def_cat_index);
        $feed_def_cat = categories::getCategories(categories::$DEFAULT_CAT)->getCatById($feed_def_cat_index);
        if($feed_user_def_cat === false){
            $choices = print_all_user_categories($user_categories);
            $choices .= print_last_radio_button($feed_def_cat->getName(), true);
        }
        else{
            $last_checked = true;
            foreach($user_categories as $category){
                if($feed_def_cat->getName() != $category->getName()){
                    $choices .= radio_button($category->getName(), $category->getName(), "category", false);
                }
                else{
                    $choices .= radio_button($category->getName(), $category->getName(), "category", true);
                    $last_checked = false;
                }
            }
            $choices .= print_last_radio_button("", $last_checked, "Nuova categoria");
        }
    }
    $choices = div($choices, null, "category-choice");
    $button = button("Aggiungi", $feed->getId());
    echo $feed_box.$choices.$button;
}

function print_all_user_categories($user_categories){
    $choices = '';
    foreach($user_categories as $category){
        $choices .= radio_button($category->getName(), $category->getName(), "category", false);
    }
    return $choices;
}

function print_last_radio_button($text, $checked, $placeholder = false){
    if($placeholder == false)
        return radio_button(input_text($text, "other-choice"), $text, "category", $checked);
    return radio_button(input_text_with_placeholder($placeholder, "other-choice"), $text, "category", $checked);
}