<?php

class categories{

    public static $USER_CAT = 0;
    public static $DEFAULT_CAT = 1;
    
    private $categories = array();
    private $mode;
    
    private $email;
    
    private function __construct($mode, $email) {
        $this->mode = $mode;
        $this->email = $email;
    }
    
    public static function getCategories($mode, $email = null){
        $cats = new categories($mode, $email);
        switch ($mode){
            case categories::$USER_CAT:
                if($email != null){
                    $cats->user_cats($email);
                }
                else{
                    return false;
                }
                break;
            case categories::$DEFAULT_CAT:
                $cats ->default_cats();
                break;
        }
        return $cats;
    }
    
    private function user_cats($email){
        $db = dbUtil::connect();
        $sql = "SELECT DISTINCT c_id FROM `UCF` WHERE email = ?;";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($email));
        dbUtil::close($db);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach ($rows as $row) {
            $this->categories[$i++] = new category($row['c_id'], $email);
        }
    }
    
    private function default_cats(){
        $db = dbUtil::connect();
        $sql = "SELECT id FROM `Categories` WHERE is_default = ?;";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(1));
        dbUtil::close($db);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach ($rows as $row) {
            $this->categories[$i++] = new category($row['id']);
        }
    }
    
    private function update_data(){
        $this->categories = array();
        switch ($this->mode){
            case categories::$USER_CAT:
                $this->user_cats($this->email);
                break;
            case categories::$DEFAULT_CAT:
                $this ->default_cats();
                break;
        }
    }


    public function add_Category($c_name){
        switch (category::insert($c_name)){
            case category::$ALREADY_PRESENT:
                $cat = new category(category::fetch_by_name($c_name)['id'], $this->email);
                $this->categories[] = $cat;
                break;
            case category::$ERROR_INSERT:
                break;
            case category::$CORRECT_INSERT:
                $this->update_data();
                $cat = new category(category::fetch_by_name($c_name)['id'], $this->email);
                $this->categories[] = $cat;
                break;
        }
    }
    
    
    public function remove_Category($cat){
        $c_id = $cat->getId();
        if(!$cat->is_default){
            $res = category::delete($c_id);
        }
        else{
            $res = category::delete_UCF_data($c_id, $this->email);
        }
        
        if($res){
            $this->update_data();
        }
        else{
            // db delete error
        }
    }
    
    public function getCatByName($name){
        foreach ($this->categories as $cat){
            if($cat->getName() == $name){
                return $cat;
            }
        }
        return false;
    }
    
    // Debug only function!
    public function print_all_categories(){
        foreach ($this->categories as $cat){
//            echo "ID: " . $cat->getId() . "<br>";
//            echo "Name: " . $cat->getName() . "<br>";
//            echo "is_default: ";
//            var_dump($cat->is_default_cat());
//            echo "<br>";
            var_dump($cat);
            echo "<br>";
        }
    }
    
}
