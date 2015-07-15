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
    echo div("Categorie", ["class", "table-header"]);
    $body = "<tr>";
    foreach($default_categories_array as $category){
        $category_name = $category->getName();
        $category = div('',["style", "background-image:url(".DEF_CAT_DIR.$category_name.'.jpg'.");", "class", "minicover"])
                    .div($category_name, ["class", "label"]);
        $body .= td(div($category, ["id", $category_name, "class", "block"]));
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
    echo div(span("Categorie > ", ["class", "back-to-cat"]).$cat_name, ["class", "table-header"]);
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
    $feed_icon = div('',["style", "background-image:url(".$feed->getIconURL().");", "class", "feed-icon"]);
    $feed_name = div($feed->getName(), ["class", "feed-name", "title", $feed->getName()]);
    $feed_desc = div(get_feed_description($rss), ["class", "feed-description"]);
    $feed_add = "";
    if($default_categories !== false){
        $user_categories = user::getCategories($email)->getCategories(categories::$USER_CAT, $email);
        $feed_add .= div(get_add_feed_icon($user_categories, $feed), ["class", "add-feed-box"]);    
    }
    $feed_header = div($feed_icon.$feed_name.$feed_desc, ["class", "feed-header"]);
    $article_desc = $rss->get_item(0)->get_description();
    $article_image = div('',["style", "background-image:url(".get_article_image_url($article_desc).");", "class", "article-image"]);
    $article_title = div(div(get_first_article_title($rss), ["class", "article-title"]), ["class", "vignette"]);
    $feed_article = div($article_image.$article_title, ["class", "article-box"]);
    $feed_footer = div($feed_article, ["class", "feed-footer"]);
    return div($feed_header.$feed_add.$feed_footer, ["id", $feed->getId(), "class", $class]);
}

function get_add_feed_icon($user_categories, $feed){
    $title = $feed->getName();
    $url = $feed->getURL();
    $feeds = $user_categories->getFeedsByTitleURL($title, $url);
    $res = "";
    if(empty($feeds))
        $res = img(["class", "add-feed", "src", "./img/utils/add-icon.png", "alt", "add-icon", "title", "Aggiungi feed"]);
    else
        $res = img(["class", "disabled-add-feed", "src", "./img/utils/add-disabled-icon.png", "alt", "add-icon", "title", "Aggiunto"]);
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
                    $choices .= radio_button($category->getName(), ["value", $category->getName(), "name", "category"]);
                }
                else{
                    $choices .= radio_button($category->getName(), ["value", $category->getName(), "name", "category", "checked", "checked"]);
                    $last_checked = false;
                }
            }
            $choices .= print_last_radio_button("", $last_checked, "Nuova categoria");
        }
    }
    $choices = div($choices, ["class", "category-choice"]);
    $button = button("Aggiungi", ["value", $feed->getId()]);
    echo $feed_box.$choices.$button;
}

function print_all_user_categories($user_categories){
    $choices = '';
    foreach($user_categories as $category){
        $choices .= radio_button($category->getName(), ["value", $category->getName(), "name", "category"]);
    }
    return $choices;
}

function print_last_radio_button($text, $checked, $placeholder = ""){
        if($checked)
            return radio_button(input_text(["value", $text, "class", "other-choice", "placeholder", $placeholder]), ["value", $text, "name", "category", "checked", "checked"]);
        else
            return radio_button(input_text(["value", $text, "class", "other-choice", "placeholder", $placeholder]), ["value", $text, "name", "category"]);
}

function visualize_article($article){
    $article_image = td(img(["class", "article-image", "src", get_article_image_url($article->get_description()), "alt", "Immagine"]));
    $article_title = h(get_article_title($article), 4);
    $article_description = get_partial_article_description($article->get_description());
    $article_preview = td($article_title.br().$article_description);
    return tr($article_image.$article_preview);
}

function visualize_articles_by_feed($feed){
    $rss = new SimplePie();
    $rss->set_feed_url($feed->getURL());
    $rss->init();
    $feed_title = h(a($feed->getName(), ["href", $rss->get_base(), "target", "_blank"]));
    $timeline = "";
    for($i = 0; $i < $rss->get_item_quantity(); $i++){
//        $article_image = td(img("article-media", get_article_image_url($rss, $i), "Image ".$i));
//        $article_image = td(get_image($rss->get_item($i)->get_description()));
//        $article_title = h(get_article_title($rss, $i), 4);
        
//        $article_description = $rss->get_item($i)->get_description();
//        $article_description = get_desc($rss->get_item($i)->get_description());
//        $article_preview = td($article_title.br().$article_description);
//        $article_item = tr($article_image.$article_preview);
        $timeline .= visualize_article($rss->get_item($i));
    }
    $timeline = table($timeline);
    echo $feed_title.$timeline;
}

function visualize_articles_by_category($category){
    
}

function visualize_all_articles($email){
    
}