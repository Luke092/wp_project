<?php
require_once("./config.php");

// login and register validation
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

// HTML functions
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

//function br($n = 1){
//    $content = '';
//    for($i = 1; $i <= $n; $i++)
//        $content .= '<br />';
//    return $content;
//}