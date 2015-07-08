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
        $cats = new categories($mode);
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
        category::insert($c_name);
        $this->update_data();
    }
    
    
    public function remove_Category($cat){
        $c_id = $cat->getId();
        if(!$cat->is_default){
            category::delete($c_id);
        }
        else{
            category::delete_UCF_data($c_id, $this->email);
        }
        $this->update_data();
    }
    
    public function getCatByName($name){
        foreach ($this->categories as $cat){
            if($cat->getName() == $name){
                return $cat;
            }
        }
        return false;
    }
    
}
