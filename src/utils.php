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

function img($src, $alt){
    return '<img src="'.$src.'" alt="'.$alt.'"></img>';
}

function div($content, $id, $class){
    $id_assign = (isset($id) ? ' id="'.$id.'"' : '');
    $class_assign = (isset($class) ? ' class="'.$class.'"' : '');
    return '<div'.$id_assign.$class_assign.'>'.$content.'</div>';
}

function empty_div($background_image_url, $class){
    $class_assign = (isset($class) ? ' class="'.$class.'"' : '');
    return '<div'.$class_assign.' style="background-image:url('.$background_image_url.')"></div>';
}