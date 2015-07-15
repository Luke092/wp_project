<?php
require_once("./config.php");

//////////////////////////////////////////////////////////////
//////////////// login and register validation ///////////////
//////////////////////////////////////////////////////////////

function show_form_errors($errors){
    if(!empty($errors))
        echo "Errori riscontrati:<ul><li>" . implode("</li><li>", $errors) . "</li></ul>";
}

function is_valid_email($email){
    return preg_match('/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/', $email);
}

function is_empty_field($field){
    return (strlen($field) == 0) || (preg_match('/^[[:space:]]+$/', $field));
}


//////////////////////////////////////////////////////////////
/////////////////// XHTML writer functions ///////////////////
//////////////////////////////////////////////////////////////

function table($content, $attribute_assign = array()){
    return '<table'.add_attributes($attribute_assign).'>'.$content.'</table>';
}

function tr($content, $attribute_assign = array()){
    return '<tr'.add_attributes($attribute_assign).'>'.$content.'</tr>';
}

function td($content, $attribute_assign = array()){
    return '<td'.add_attributes($attribute_assign).'>'.$content.'</td>';
}

function img($attribute_assign = array()){
    return '<img'.add_attributes($attribute_assign).' />';
}

function div($content, $attribute_assign = array()){
    return '<div'.add_attributes($attribute_assign).'>'.$content.'</div>';
}

function span($content, $attribute_assign = array()){
    return '<span'.add_attributes($attribute_assign).'>'.$content.'</span>';
}

//function radio_button($text, $value, $name, $checked, $class = null){
//    $class_assign = (isset($class) ? ' class="'.$class.'"' : '');
//    $check = ($checked ? ' checked="checked"' : '');
//    return '<input name="'.$name.'" type="radio" value="'.$value.'"'.$class_assign.$check.'>&nbsp;&nbsp;'.$text.'<br>';
//}

function radio_button($text, $attribute_assign = array()){
    return '<input type="radio"'.add_attributes($attribute_assign).'>'.nbsp(2).$text.'<br>';
}

//function input_text($default_text, $attribute_assign = array()){
//    $class_assign = (isset($class) ? ' class="'.$class.'"' : '');
//    return '<input'.$class_assign.' type="text" value="'.$default_text.'">';
//}
//
//function input_text_with_placeholder($placeholder, $class = null){
//    $class_assign = (isset($class) ? ' class="'.$class.'"' : '');
//    return '<input'.$class_assign.' type="text" value="" placeholder="'.$placeholder.'">';
//}

function input_text($attribute_assign = array()){
    return'<input type="text"'.add_attributes($attribute_assign).'>';
}

//function button($text, $value){
//    return '<button value="'.$value.'">'.$text.'</button>';
//}

function button($text, $attribute_assign = array()){
    return '<button'.  add_attributes($attribute_assign).'>'.$text.'</button>';
}

//function hlink($ref, $label, $target = null){
//    $target_assign = (isset($target) ? ' target="'.$target.'"' : '');
//    return "<a" . $target_assign ." href='". $ref . "'>".$label."</a> ";
//}

function a($text, $attribute_assign = array()){
    return '<a'.  add_attributes($attribute_assign).'>'.$text.'</a>';
}

function h($text, $size = 1, $attribute_assign = array()){
    $head = 'h' . $size;
    return '<'.$head.add_attributes($attribute_assign).'>'.$text.'</'.$head.'>';
}

function br($n = 1){
    $content = '';
    for($i = 1; $i <= $n; $i++)
        $content .= '<br />';
    return $content;
}

function nbsp($n = 1){
    $content = '';
    for($i = 1; $i <= $n; $i++)
        $content .= '&nbsp;';
    return $content;
}

function add_attributes($attribute_assign){
    $res = '';
    for($i = 0; $i < count($attribute_assign); $i += 2){
        $res .= ' '.$attribute_assign[$i].'='.'"'.$attribute_assign[$i+1].'"';
    }
    return $res;
}

//////////////////////////////////////////////////////////////
/////////////////// XHTML reader functions ///////////////////
//////////////////////////////////////////////////////////////

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

function get_favicon($url){
    $api_url = FAVICON_API_URL;
    $domain = get_domain($url);
    return $api_url . $domain;
}

function get_domain($url){
//        $sUrl = str_ireplace('www.', '', $sUrl);
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

//function get_article_image_url($rss, $index){
//    $article = $rss->get_item($index);
//    $url = $article != null ? $article->get_enclosure()->get_link() : "//?#";
//    return $url != "//?#" ? $url : DEF_ARTICLE_IMAGE_PATH;
//}
//
//function get_first_article_image_url($rss){
//    return get_article_image_url($rss, 0);
//}

function get_article_title($article){
    $title = $article != null ? strip_tags($article->get_title()) : null;
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

function get_article_image_url($article_content){
    $dom = new DOMDocument();
    @$dom->loadHTML($article_content);
    $image_tags = $dom->getElementsByTagName('img');
    if($image_tags->item(0) != null)
        $src = $image_tags->item(0)->getAttribute("src");
    else
        $src = DEF_ARTICLE_IMAGE_PATH;
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

function get_partial_article_description($article_content){
    $body = get_full_article_description($article_content);
    $res = null;
    if($body == null || trim($body) == "")
        $res = "Contenuto non disponibile";
    else if(strlen($body) > MAX_LENGTH_SUMMARY)
        $res = substr($body, 0, MAX_LENGTH_SUMMARY).'...';
    else
        $res = $body;
    return $res;
}