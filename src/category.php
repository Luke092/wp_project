<?php

class category {

    private static $ALREADY_PRESENT = 0;
    private static $ERROR_INSERT = 1;
    private static $CORRECT_INSERT = 2;
    private static $TABLE = "Categories";
    private $id;
    private $c_name;
    private $is_default;
    private $feeds;

    public function __construct($id, $email = null) {
        $this->id = $id;
        $attributes = $this->getCatAttributes();
        $this->c_name = $attributes["c_name"];
        $this->is_default = $attributes["is_default"];
        $this->buildFeedArray($email);
    }
    
    private function getCatAttributes() {
        $db = dbUtil::connect();
        $sql = "SELECT * FROM " . self::$TABLE . " WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->id));
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    private function getFeedByCategory($email) {
        $db = dbUtil::connect();
        if($email != null){
            $sql = "SELECT f_id FROM `UCF` WHERE email = ? AND c_id = ?;";
            $stmt = $db->prepare($sql);
            $stmt->execute(array($email, $this->id));
        }
        else{
            $sql = "SELECT id FROM `Feeds` WHERE default_cat = ?;";
            $stmt = $db->prepare($sql);
            $stmt->execute(array($this->id));
        }
        dbUtil::close($db);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buildFeedArray($email) {
        $rows = $this->getFeedByCategory($email);
        $i = 0;
        foreach ($rows as $row) {
            if($email != null){
                $this->feeds[$i++] = new feed($row['f_id']);
            }
            else{
                $this->feeds[$i++] = new feed($row['id']);
            }
        }
    }
    
    public function getId(){
        $this->id;
    }
    
    public function getName(){
        $this->c_name;
    }
    
    public function is_default_cat(){
        return (($this->is_default == 1)? true : false);
    }

    public static function insert($c_name) {
//        if (!self::alreadyPresent($c_name)) {
        if (!dbUtil::alreadyPresent(self::$TABLE,["c_name"],[$c_name])) {
            if (dbUtil::insert(self::$TABLE, array("c_name","is_default"), array($c_name,"0"))) {
                return self::$CORRECT_INSERT;
            } else {
                return self::$ERROR_INSERT;
            }
        } else {
            return self::$ALREADY_PRESENT;
        }
    }

    public static function delete($id) {
        return dbUtil::delete(self::$TABLE, array("id"), array($id));
    }
    
    public static function delete_UCF_data($id, $email) {
        return dbUtil::delete("UCF", array("email","c_id"), array($email,$id));
    }

    public static function modifyName($id, $newName) {
        return dbUtil::update(self::$TABLE, array("c_name"), [$newName], array("id"), [$id]);
    }

    public static function alreadyPresent($c_name) {
        $db = dbUtil::connect();
        $sql = "SELECT * FROM " . self::$TABLE . " WHERE c_name = '" . $c_name . "';";
        $stmt = $db->query($sql);

        $present = false;
        if ($stmt->rowCount() > 0) {
            $present = true;
        }

        dbUtil::close($db);

        return $present;
    }

}

?>