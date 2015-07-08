<?php
    require_once("./config.php");
    require_once("./utils.php");
?>
<div id="search-bar">
    <form>
        <span>
            <input type="text" class="search" name="search-token" placeholder="Cerca feed per categoria, nome o URL">

        </span>
    </form>
</div>
<?php
    if($def_cat_icons = load_directory(DEF_CAT_DIR)){

    }
?>