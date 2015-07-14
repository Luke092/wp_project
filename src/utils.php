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

function table($body){
    return '<table>'.$body.'</table>';
}

function tr($row){
    return '<tr>'.$row.'</tr>';
}

function td($cell){
    return '<td>'.$cell.'</td>';
}

function img($class, $src, $alt, $title = null){
    $class_assign = (isset($class) ? ' class="'.$class.'"' : '');
    $title_assign = (isset($title) ? ' title="'.$title.'"' : '');
    return '<img'.$class_assign.' src="'.$src.'" alt="'.$alt.'"'.$title_assign.'></img>';
}

function div($content, $id, $class, $title = null){
    $id_assign = (isset($id) ? ' id="'.$id.'"' : '');
    $class_assign = (isset($class) ? ' class="'.$class.'"' : '');
    $title_assign = (isset($title) ? ' title="'.$title.'"' : '');
    return '<div'.$id_assign.$class_assign.$title_assign.'>'.$content.'</div>';
}

function empty_div($background_image_url, $class){
    $class_assign = (isset($class) ? ' class="'.$class.'"' : '');
    return '<div'.$class_assign.' style="background-image:url('.$background_image_url.')"></div>';
}

function span($content, $id, $class, $title=null){
    $id_assign = (isset($id) ? ' id="'.$id.'"' : '');
    $class_assign = (isset($class) ? ' class="'.$class.'"' : '');
    $title_assign = (isset($title) ? ' title="'.$title.'"' : '');
    return '<span'.$id_assign.$class_assign.$title_assign.'>'.$content.'</span>';
}

function radio_button($text, $value, $name, $checked, $class = null){
    $class_assign = (isset($class) ? ' class="'.$class.'"' : '');
    $check = ($checked ? ' checked="checked"' : '');
    return '<input name="'.$name.'" type="radio" value="'.$value.'"'.$class_assign.$check.'>&nbsp;&nbsp;'.$text.'<br>';
}

function input_text($default_text, $class = null){
    $class_assign = (isset($class) ? ' class="'.$class.'"' : '');
    return '<input'.$class_assign.' type="text" value="'.$default_text.'">';
}

function input_text_with_placeholder($placeholder, $class = null){
    $class_assign = (isset($class) ? ' class="'.$class.'"' : '');
    return '<input'.$class_assign.' type="text" value="" placeholder="'.$placeholder.'">';
}

function button($text, $value){
    return '<button value="'.$value.'">'.$text.'</button>';
}

function hlink($ref, $label, $target = null){
    $target_assign = (isset($target) ? ' target="'.$target.'"' : '');
    return "<a" . $target_assign ." href='". $ref . "'>".$label."</a> ";
}

function h($text, $size = 1){
    $head = 'h' . $size;
    return "<$head>".$text."</$head>";
}

function br($n = 1){
    $content = '';
    for($i = 1; $i <= $n; $i++)
        $content .= '<br />';
    return $content;
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


function get_feed_icon_url($rss){
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

function get_article_image_url($rss, $index){
    $article = $rss->get_item($index);
    $url = $article != null ? $article->get_enclosure()->get_link() : "//?#";
    return $url != "//?#" ? $url : DEF_ARTICLE_IMAGE_PATH;
}

function get_first_article_image_url($rss){
    return get_article_image_url($rss, 0);
}

function get_article_title($rss, $index){
    $article = $rss->get_item($index);
    $title = $article != null ? strip_tags($article->get_title()) : null;
    $res = null;
    if($title == null || trim($title) == "")
        $res = "Titolo articolo non disponibile";
    else if(strlen($title) > MAX_LENGTH_TITLE)
        $res = substr($title, 0, MAX_LENGTH_TITLE).'...';
    else
        $res = $title;
    return $res;
}

function get_first_article_title($rss){
    return get_article_title($rss, 0);
}

function get_image($content){
    $doc = new DOMDocument();
    @$doc->loadHTML($content);
    $imageTags = $doc->getElementsByTagName('img');
    $src = $imageTags[0]->getAttribute("src");
//    foreach($imageTags as $tag) {
//        echo $tag->getAttribute('src');
//    }
    return img(null, $src, "alt");
}

function get_desc($content){
    $doc = new DOMDocument();
    @$doc->loadHTML($content);
    $xpath = new DOMXPath($doc);
    $textnodes = $xpath->query('//text()');
    $body = "";
    foreach($textnodes as $textnode){
        $body .= " ".$textnode->textContent;
    }
    return $body;
}