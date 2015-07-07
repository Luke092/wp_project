<?php
    require_once("./config.php");
    function __autoload($class_name){
        require_once $class_name . '.php';
    }
    
    session::start();
    if(!session::user_is_logged())
        header("Location: ./index.php");
    else{
        $email = session::get_info("email");
        $categories = user::getCatArray($email);
        if(count($categories) == 0)
            header("Location: ./add_content.php");
        else{
?>
            <div>
                <div id="search-bar">
                    <table>
                        <tr>
                            <td>
                                <div id="search-icon"></div>
                            </td>
                            <td>
                                <input type="text" name="search-token" placeholder="">
                            </td>
                        </tr>
                    </table>
                </div>
                <?php
                    if($def_cat_icons = load_directory(DEF_CAT_DIR)){
                        
                    }
                ?>
            </div>
<?php
        }
    }
?>