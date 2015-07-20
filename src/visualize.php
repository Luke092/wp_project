<?php
use RSSAggregator\model\user;
use RSSAggregator\model\categories;
use RSSAggregator\model\category;
use RSSAggregator\model\feed;
use RSSAggregator\model\session;
use RSSAggregator\model\stat;
use RSSAggregator\model\article;

require_once("./config.php");
require_once("./utils.php");

function visualize_default_categories($default_categories_array){
    $i = 0;
?>
    <div class="table-header">Categorie</div>
    <table>
        <tr>
        <?php
            foreach($default_categories_array as $category){
                $category_name = $category->getName();
        ?>
                <td>
                    <div id="<?php echo $category_name ?>" class="block">
                        <div class="minicover" style="background-image: url(<?php echo DEF_CAT_DIR.$category_name.'.jpg' ?>);"></div>
                        <div class="label"><?php echo $category_name ?></div>
                    </div>
                </td>
            <?php
                if(++$i % DEF_CATS_PER_ROW == 0){
            ?>
                    </tr><tr>
            <?php
                }
            }
            ?>
        </tr>
    </table>
<?php
}

function visualize_default_feeds($default_categories, $cat_name, $email){
    $category = $default_categories->getCatByName($cat_name);
    $feeds = $category->get_array();
    $i = 0;
?>
    <div class="table-header">
        <span id="back-to-cat">Categorie</span>
         &gt; <?php echo $cat_name ?>
    </div>
    <table>
        <tr>
        <?php
            foreach($feeds as $feed){
        ?>
            <td>
                <?php echo visualize_single_feed_box($feed, $default_categories, "feed-box", $email) ?>
            </td>
            <?php
                if(++$i % DEF_FEEDS_PER_ROW == 0){
            ?>
                    </tr><tr>
            <?php
                }
            }
            ?>
        </tr>
    </table>
<?php
}

function visualize_single_feed_box($feed, $default_categories, $class, $email = null){
    $rss = new SimplePie();
    $rss->set_feed_url($feed->getURL());
    $rss->init();
?>
    <div id="<?php echo $feed->getId() ?>" class="<?php echo $class ?>">
        <div class="feed-header">
            <div class="feed-icon" style="background-image: url(<?php echo $feed->getIconURL() ?>);"></div>
            <div class="feed-name" title="<?php echo $feed->getName() ?>">
                <a href="<?php echo $rss->get_base() ?>" target="_blank"><?php echo $feed->getName() ?></a>
            </div>
            <div class="feed-description">
                <?php echo get_feed_description($rss) ?>
            </div>
        </div>
    <?php
        if($default_categories !== false){
            $user_categories = user::getCategories($email);
    ?>
            <div class="add-feed-box">
                <?php echo get_add_feed_icon($user_categories, $feed) ?>
            </div>
    <?php
        }
    ?>
        <a href="<?php echo get_first_article_link($rss) ?>" target="_blank">
            <div class="feed-footer">
                <div class="article-box">
                    <div class="article-image" style="background-image: url(<?php echo get_article_image_url($rss->get_item(0)) ?>);"></div>
                    <div class="vignette">
                        <div class="article-title">
                            <?php echo get_first_article_title($rss) ?>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
 <?php
}

function get_add_feed_icon($user_categories, $feed){
    $title = $feed->getName();
    $url = $feed->getURL();
    $feeds = $user_categories->getFeedsByTitleURL($title, $url);
    if(empty($feeds)){
?>
        <img src="./img/utils/add-icon.png" alt="Aggiungi feed" title="Aggiungi feed" class="add-feed" />
<?php
    }else{
?>
        <img src="./img/utils/add-disabled-icon.png" alt="Aggiunto" title="Aggiunto" class="disabled-add-feed" />
<?php
    }
}

function show_category_choice($feed, $email){
    visualize_single_feed_box($feed, false, "feed-box-cat-choice");
    $user_categories = user::getCategories($email)->get_array();
    $feed_def_cat_index = $feed->getDefaultCat();
?>
        <span class="category-choice-header">Scegli la categoria per questo feed</span>
        <div class="category-choice">
    <?php
        if($feed_def_cat_index == null){
            print_all_user_categories($user_categories);
            print_last_radio_button("", true, "Nuova categoria");
        }else{
            $feed_user_def_cat = user::getCategories($email)->getCatById($feed_def_cat_index);
            $feed_def_cat = categories::getCategories(categories::$DEFAULT_CAT)->getCatById($feed_def_cat_index);
            if($feed_user_def_cat == false){
                print_all_user_categories($user_categories);
                print_last_radio_button($feed_def_cat->getName(), true);
            }else{
                $last_checked = true;
                foreach($user_categories as $category){
                    if($feed_def_cat->getName() !== $category->getName()){
    ?>
                        <input type="radio" name="category" value="<?php echo $category->getName() ?>" />
                <?php
                    }else{
                        $last_checked = false;
                ?>
                        <input type="radio" name="category" value="<?php echo $category->getName() ?>" checked="checked" />
                <?php
                    }
                ?>
                            &nbsp;&nbsp;<?php echo $category->getName() ?><br>
            <?php
                }
                print_last_radio_button("", $last_checked, "Nuova categoria");
            }
        }
            ?>
        </div>
        <button value="<?php echo $feed->getId() ?>">Aggiungi</button>
<?php
}

function print_all_user_categories($user_categories){
    foreach($user_categories as $category){
?>
        <input type="radio" name="category" value="<?php echo $category->getName() ?>" />
            &nbsp;&nbsp;<?php echo $category->getName() ?><br>
<?php
    }
}

function print_last_radio_button($text, $checked, $placeholder = ""){
    if($checked){
?>
        <input type="radio" name="category" value="<?php echo $text ?>" checked="checked" />
<?php
    }else{
?>
        <input type="radio" name="category" value="<?php echo $text ?>" />
<?php
    }
?>
            &nbsp;&nbsp;<input type="text" value="<?php echo $text ?>" class="other-choice" placeholder="<?php echo $placeholder ?>" /><br>
<?php
}

function visualize_article($article, $feed_name = '', $cat_name = ''){
    $article_link = $article->get_link();
    $site_url = $article->get_feed()->get_base();
    $email = session::get_info("email");
    $categories = user::getCategories($email);
    $feed_url = $article->get_feed()->subscribe_url();
    $cat_and_feed = $categories->getCatByFeedURL($feed_url);
    $category = $cat_and_feed[0];
?>
    <div class="article-box-fs">
        <div class="article-image-box-fs">
            <img src="<?php echo get_article_image_url($article) ?>" class="article-image-fs" />        
        </div>
        <div class="article-description-fs">
            <div class="article-text">
                <a href="<?php echo $article_link ?>" target="_blank">
                    <h5 class="article-title-fs">
                        <?php echo get_article_title($article) ?>
                    </h5>
                </a>
                <div class="article-summary-fs">
                    <?php echo get_partial_article_description($article->get_content()) ?>
                </div>
            </div>
            <div class="article-date-fs">
            <?php
                if($cat_name !== ''){
            ?>
                    <span class="cat-name-fs link-evidence">
                        <?php echo $cat_name ?>
                    </span>
                     | 
            <?php
                }
                if($feed_name !== ''){
            ?>
                    <a href="<?php echo $site_url ?>" target="_blank" class="link-evidence">
                        <?php echo $feed_name ?>
                    </a>
                     | 
            <?php
                }
                echo date_transform($article->get_date(DATE_FORMAT))
            ?>
            </div>
        </div>
        <div class="article-readbox-fs">
            <img src="<?php echo get_envelope_src($article, $email); ?>" id="<?php echo $article_link ?>" class="envelope-img" />
        </div>
        <div class="hidden">
            <?php
                $art = new article($article_link, $category->getId(), 
                        get_article_title($article).' '.get_full_article_description($article->get_content()),
                        time());
                echo json_encode($art);
            ?>
        </div>
    </div>
<?php
}

function get_envelope_src($article, $email){
    $stat = stat::getStat($email);
    $status = ($stat->getArticleById($article->get_link()) == false) ? "closed" : "open";
    return "./img/utils/".$status."_envelope.png";
}

function visualize_articles_by_feed($feed, $from, $n){
    $rss = new SimplePie();
    $rss->set_feed_url($feed->getURL());
    $rss->init();
    $articles = $rss->get_items();
    $varticles = get_articles_in_range($articles, $from, $n);
    $i = $from;
?>
    <div class="timeline">
    <h1>
        <a href="<?php echo $rss->get_base() ?>" target="_blank">
            <?php echo $feed->getName() ?>
        </a>
    </h1>
<?php
    foreach($varticles as $article){
        visualize_article($article);
        if(($i < $from + $n - 1) && ($i < get_articles_quantity($feed)-1)){
        ?>
            <hr>
    <?php
        }
        $i++;
    }
    ?>
    </div>
<?php
}

function visualize_page_navigation_bar($pages, $feed_id = '', $cat_name = '', $current_page = 1){
    $feed_id = ($feed_id == '')? '' : "#" . $feed_id;
    $cat_name = ($cat_name == '')? '' : "#" . $cat_name;
?>
    <div class="paging">
        <p>
            <h3>Pagine</h3>
        <?php
            if($current_page != 1){
            ?>
                <span id="<?php echo '1'.$feed_id.$cat_name ?>" class="paging-header">&lt;&lt;</span>
            <?php
                $prev = max(array($current_page-1,1));
            ?>
                <span id="<?php echo $prev.$feed_id.$cat_name ?>" class="paging-header">&lt;</span>
        <?php
            }
            $loop_start = 1;
            if($current_page - VISIBLE_PAGE_NUM_BEF_AFT > 1){
            ?>
                ...
            <?php
                $loop_start = $current_page - VISIBLE_PAGE_NUM_BEF_AFT;
            }
            $loop_end = $pages;
            if($current_page + VISIBLE_PAGE_NUM_BEF_AFT < $pages){
                $loop_end = $current_page + VISIBLE_PAGE_NUM_BEF_AFT;
            }
            for($i = $loop_start; $i <= $loop_end; $i++){
                if($i == $current_page){
                ?>
                    <span id="<?php echo $i.$feed_id.$cat_name ?>" class="paging-header-selected">
            <?php
                }else{
                ?>
                    <span id="<?php echo $i.$feed_id.$cat_name ?>" class="paging-header">
            <?php
                }
                        echo $i;
            ?>
                    </span>
        <?php
            }
            if($loop_end < $pages){
            ?>
                ...
            <?php
            }
            if($current_page != $pages){
                $next = min(array($current_page+1,$pages));
            ?>
                <span id="<?php echo $next.$feed_id.$cat_name ?>" class="paging-header">&gt;</span>
                <span id="<?php echo ($pages).$feed_id.$cat_name ?>" class="paging-header">&gt;&gt;</span>
        <?php
            }
        ?>
        </p>
    </div>
<?php
}

function visualize_articles_by_category($category, $from, $n){
    $articles = get_articles_from_category($category);
    $varticles = get_articles_in_range($articles, $from, $n);
    $i = $from;
?>
    <div class="timeline">
    <?php
        foreach($varticles as $article){
            $feed = $category->getFeedByURL($article->get_feed()->subscribe_url());
            visualize_article($article, $feed->getName());
            if(($i < $from + $n - 1) && ($i < get_articles_quantity($feed)-1)){
    ?>
                <hr>
    <?php
            }
            $i++;
        }
    ?>
    </div>
<?php
}

function visualize_all_articles($categories, $from, $n){
    $articles = get_all_articles_for_home($categories->get_array());
    $varticles = get_articles_in_range($articles, $from, $n);
    $i = $from;
?>
    <h1 class="home-header">Le ultime notizie</h1>
    <div class="timeline">
    <?php
        foreach($varticles as $article){
            $feed_url = $article->get_feed()->subscribe_url();
            $cat_and_feed = $categories->getCatByFeedURL($feed_url);
            $category = $cat_and_feed[0];
            $feed = $cat_and_feed[1];
            visualize_article($article, $feed->getName(), $category->getName());
            if(($i < $from + $n - 1) && ($i < get_articles_quantity($feed)-1)){
    ?>
                <hr>
    <?php
            }
            $i++;
        }
    ?>
    </div>
<?php
}