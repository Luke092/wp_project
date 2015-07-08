<?php
    require_once("./config.php");
    require_once("./utils.php");
    function __autoload($class_name){
        require_once $class_name . '.php';
    }
?>
<div id="search-bar">
    <form>
        <span>
            <input type="text" class="search" name="search-token" placeholder="Cerca feed per categoria, nome o URL">
        </span>
    </form>
</div>
<?php
    $default_categories = categories::getCategories(categories::$DEFAULT_CAT)->get_array();
    $categories_names = array();
    $i = 0;
    foreach($default_categories as $category){
        $categories_names[$i++] = $category->getName();
    }
    $category = empty_div(DEF_CAT_DIR.$categories_names[0].'.jpg', "minicover").div($categories_names[0] , "label");
    $body = "<tr>" . td(div($category, "block"));
    for($i = 1; $i < count($default_categories); $i++){
        if($i % DEF_CAT_PER_ROW == 0)
            $body .= "</tr><tr>";
        $category = empty_div(DEF_CAT_DIR.$categories_names[$i].'.jpg', "minicover").div($categories_names[$i] , "label");
        $body .= td(div($category, "block"));
    }
    $body .= "</tr>";
    echo table($body);
?>