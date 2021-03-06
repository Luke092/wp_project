<?php
namespace RSSAggregator\model;
use PDO;
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
        $res = category::insert($c_name);
        $present_cat = $this->getCatByName($c_name);
        if($present_cat == false){
            if($res == category::$ERROR_INSERT){
                return false;
            }
            elseif ($res == category::$CORRECT_INSERT || $res ==category::$ALREADY_PRESENT) {
                $this->update_data();
            }
            $cat = new category(category::fetch_by_name($c_name)['id'], $this->email);
            $this->categories[] = $cat;
            return $cat;
        }
        else{
            return $present_cat;
        }
    }
    
    
    public function remove_Category($cat){
        $c_id = $cat->getId();
        $res = category::delete_UCF_data($c_id, $this->email);
        
        if($res){
            $this->update_data();
            return true;
        }
        else{
            return false;
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
    
    public function getCatById($id){
        foreach ($this->categories as $cat){
            if($cat->getId() == $id){
                return $cat;
            }
        }
        return false;
    }
    
    public function getCatByFeedURL($url){
        foreach ($this->categories as $cat){
            foreach($cat->get_array() as $feed){
                if($feed->getURL() == $url){
                    return [$cat, $feed];
                }
            }
        }
        return false;
    }
    
    public function getFeedsByTitleURL($title, $url){
        $res = array();
        foreach ($this->categories as $cat){
            $feed = $cat->getFeedByName($title, $url);
            if ($feed != false){
                $res[] = $feed;
            }
        }
        return $res;
    }
    
    public function searchFeedsByTitleURL($title, $url = null){
        $res = array();
        foreach ($this->categories as $cat){
            $feed = $cat->searchFeedByName($title, $url);
            if ($feed != false){
                $res[] = $feed;
            }
        }
        return $res;
    }
    
    public function count(){
        return count($this->categories);
    }
    
    public function get_array(){
        return $this->categories;
    }
    
    // Debug only function!
    public function print_all_categories(){
        foreach ($this->categories as $cat){
            echo "ID: " . $cat->getId() . "<br>";
            echo "Name: " . $cat->getName() . "<br>";
            echo "is_default: ";
            var_dump($cat->is_default_cat());
            echo "<br>";
            var_dump($cat);
            echo "<br>";
        }
    }
    
}
