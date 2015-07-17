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
    echo div(span("Categorie > ", ["id", "back-to-cat"]).$cat_name, ["class", "table-header"]);
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
        $user_categories = user::getCategories($email);
        $feed_add .= div(get_add_feed_icon($user_categories, $feed), ["class", "add-feed-box"]);    
    }
    $feed_header = div($feed_icon.$feed_name.$feed_desc, ["class", "feed-header"]);
    $article_desc = ($rss->get_item(0) == null ? null : $rss->get_item(0)->get_description());
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
    $user_categories = user::getCategories($email)->get_array();
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

function visualize_article($article, $feed_name = '', $cat_name = ''){
    $article_image = div(img(["src", get_article_image_url($article->get_description()), "class", "article-image-fs"]), ["class", "article-image-box-fs"]);
    $article_title = a(h(get_article_title($article), 5, ["class", "article-title-fs"]), ["href", $article->get_link(), "target", "_blank"]);
    $article_summary = div(get_partial_article_description($article->get_description()), ["class", "article-summary-fs"]);
    $article_text = div($article_title.$article_summary, ["class", "article-text"]);
    $cat_name_intro = ($cat_name == '' ? '' : $cat_name.' | ');
    $feed_name_intro = ($feed_name == '' ? '' : $feed_name.' | ');
    $article_date = div($cat_name_intro.$feed_name_intro.date_transform($article->get_date(DATE_FORMAT)), ["class", "article-date-fs"]);
    $article_description = div($article_text.$article_date, ["class", "article-description-fs"]);
    $article_read_box = div(img(["src", "./img/utils/closed_envelope.png", "class", "envelope-img"]), ["class", "article-readbox-fs"]);
    $article_box = div($article_image.$article_description.$article_read_box, ["class", "article-box-fs"]);
    return $article_box;
}

function visualize_articles_by_feed($feed, $from, $n){
    $rss = new SimplePie();
    $rss->set_feed_url($feed->getURL());
    $rss->init();
    $feed_title = h(a($feed->getName(), ["href", $rss->get_base(), "target", "_blank"]));
    $timeline = "";
    $articles = $rss->get_items();
    $varticles = get_articles_in_range($articles, $from, $n);
    $i = $from;
    foreach ($varticles as $article){
        $timeline .= visualize_article($article);
        $timeline .= (($i < $from + $n - 1) && ($i < get_articles_quantity($feed)-1) ? '<hr>' : '');
        $i++;
    }
    $timeline = div($timeline, ["class", "timeline"]);
    echo $feed_title.$timeline;
}

function visualize_page_navigation_bar($pages, $feed_id = '', $cat_name = '', $current_page = 1){
    $feed_id = ($feed_id == '')? '' : "#" . $feed_id;
    $cat_name = ($cat_name == '')? '' : "#" . $cat_name;
    $p = "<p>";
    $p .= h("Pagine", 3);
    $p .= span("<<", ["class", "paging-header", "id", (1).$feed_id.$cat_name]);
    $p .= span("<", ["class", "paging-header", "id", ($current_page-1).$feed_id.$cat_name]);
    for($i = 1; $i <= $pages; $i++){
        if($i == $current_page){
            $p .= span($i, ["class", "paging-header-selected", "id", $i.$feed_id.$cat_name]);
        }
        else {
            $p .= span($i, ["class", "paging-header", "id", $i.$feed_id.$cat_name]);
        }
    }
    $p .= span(">", ["class", "paging-header", "id", ($current_page+1).$feed_id.$cat_name]);
    $p .= span(">>", ["class", "paging-header", "id", ($pages).$feed_id.$cat_name]);
    $p .= "</p>\n";
    $div = div($p,["class", "paging"]);
    echo $div;
}

function visualize_articles_by_category($category, $from, $n){
    $articles = get_articles_from_category($category);
    $varticles = get_articles_in_range($articles, $from, $n);
    $timeline = "";
    $i = $from;
    foreach($varticles as $article){
        $feed = $category->getFeedByURL($article->get_feed()->subscribe_url());
        $timeline .= visualize_article($article, $feed->getName());
        $timeline .= (($i < $from + $n - 1) && ($i < get_articles_quantity($feed)-1) ? '<hr>' : '');
        $i++;
    }
    $timeline = div($timeline, ["class", "timeline"]);
    return $timeline;
}

function visualize_all_articles($categories, $from, $n){
    $home_header = h("Le ultime notizie", 1, ["class", "home-header"]);
    $timeline = "";
    $articles = get_all_articles_for_home($categories->get_array());
    $varticles = get_articles_in_range($articles, $from, $n);
//    for($i = $from; $i < $from + $n; $i++){
//        $category = $categories[$i];
//        //$cat_name_header = h($category->getName(), 2, ["class", "category-header"]);
//        $timeline .= visualize_articles_by_category($category, $from, $n);
//    }
    foreach($varticles as $article){
        $feed_url = $article->get_feed()->subscribe_url();
        $cat_and_feed = $categories->getCatByFeedURL($feed_url);
        $category = $cat_and_feed[0];
        $feed = $cat_and_feed[1];
        $timeline .= visualize_article($article, $feed->getName(), $category->getName());
    }
    $timeline = div($timeline, ["class", "timeline"]);
    echo $home_header.$timeline;
}