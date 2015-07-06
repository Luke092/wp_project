<?php

class category {

    private static $ALREADY_PRESENT = 0;
    private static $ERROR_INSERT = 1;
    private static $CORRECT_INSERT = 2;
    private static $TABLE = "categories";
    private $id;
    private $c_name;
    private $feeds;

    public function __construct($id, $email) {
        $this->id = $id;
        $attributes = $this->getCatAttributes();
        $this->c_name = $attributes["c_name"];
        $this->buildFeedArray($email);
    }
    
    public function getCatAttributes() {
        $db = dbUtil::connect();
        $sql = "SELECT * FROM " . self::$TABLE . " WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->id));
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    public function getFeedByCategory($email) {
        $db = dbUtil::connect();
        $sql = "SELECT f_id FROM `ucf` WHERE email = ? AND c_id = ?;";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($email, $this->id));
        dbUtil::close($db);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buildFeedArray($email) {
        $rows = $this->getFeedByCategory($email);
        $i = 0;
        foreach ($rows as $row) {
            $this->feeds[$i++] = new feed($row['f_id']);
            //$this->feeds[$i-1]->printFeed();
        }
    }

    public static function insert($c_name) {
//        if (!self::alreadyPresent($c_name)) {
        if (!dbUtil::alreadyPresent(self::$TABLE,["c_name"],[$c_name])) {
            if (dbUtil::insert(self::$TABLE, array("c_name"), array($c_name))) {
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
    
     public function printCategory() {
        echo $this->id;
        echo $this->c_name;
    }

}

?>