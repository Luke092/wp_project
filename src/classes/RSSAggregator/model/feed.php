<?php

namespace RSSAggregator\model;

use PDO;

require_once ("./utils.php");

class feed implements \JsonSerializable{

    public static $ALREADY_PRESENT = 0;
    public static $ERROR_INSERT = 1;
    public static $CORRECT_INSERT = 2;
    private static $TABLE = "Feeds";
    private static $T_HEADER = ["id", "f_name", "url", "default_cat"];
    private $id, $f_name, $url, $default_cat, $image_url;

    public function __construct($id) {
        $this->id = $id;
        $attributes = $this->getFeedAttributes();
        $this->f_name = $attributes["f_name"];
        $this->url = $attributes["url"];
        $this->default_cat = $attributes["default_cat"];
        $this->image_url = get_feed_icon_url($this->url);
    }

    // returns the attributes of the feed from the database
    public function getFeedAttributes() {
        $db = dbUtil::connect();
        $sql = "SELECT * FROM " . self::$TABLE . " WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->id));
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->f_name;
    }

    public function getURL() {
        return $this->url;
    }
    
    public function getIconURL() {
        return $this->image_url;
    }

    public function getDefaultCat() {
        return $this->default_cat;
    }
    
    public function jsonSerialize() {
        return [
            "id" => $this->id,
            "f_name" => $this->f_name,
            "url" => $this->url,
            "default_cat" => $this->default_cat,
            "image_url" => $this->image_url
        ];
    }

    public static function insert($f_name, $url, $default_cat) {
//        if (!self::alreadyPresent($f_name, $url)) {
        if (!dbUtil::alreadyPresent(self::$TABLE, ["f_name", "url"], [$f_name, $url])) {
            if (dbUtil::insert(self::$TABLE, array("f_name", "url", "default_cat"), array($f_name, $url, $default_cat))) {
                return self::$CORRECT_INSERT;
            } else {
                return self::$ERROR_INSERT;
            }
        } else {
            return self::$ALREADY_PRESENT;
        }
    }

    public static function insert_UCF_data($email, $c_id, $f_id) {
        $table = "UCF";
        if (!dbUtil::alreadyPresent($table, ["email", "c_id", "f_id"], [$email, $c_id, $f_id])) {
            if (dbUtil::insert($table, array("email", "c_id", "f_id"), array($email, $c_id, $f_id))) {
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

    public static function delete_UCF_data($email, $c_id, $f_id) {
        return dbUtil::delete("UCF", array("email", "c_id", "f_id"), array($email, $c_id, $f_id));
    }

    // modifies name, url and default category of the entry with the specified id.
    // It's possible to modify also only 1 or 2 attribute values by putting "null" as parameter
    // where no changes have to be made.
    public static function modifyEntry($id, $name, $url, $default_cat) {
        $mod = [$name, $url, $default_cat];
        for ($i = 0; $i < count($mod); $i++) {
            if ($mod[$i] != null) {
                dbUtil::update(self::$TABLE, array(self::$T_HEADER[$i + 1]), [$mod[$i]], array("id"), [$id]);
            }
        }
    }

    public static function modifyName($id, $newName) {
        return dbUtil::update(self::$TABLE, array("f_name"), [$newName], array("id"), [$id]);
    }

    public static function modifyURL($id, $newUrl) {
        return dbUtil::update(self::$TABLE, array("url"), [$newUrl], array("id"), [$id]);
    }

    public static function modifyDefaultCat($id, $newDef) {
        return dbUtil::update(self::$TABLE, array("default_cat"), [$newDef], array("id"), [$id]);
    }

    private static function alreadyPresent($f_name, $url) {
        $db = dbUtil::connect();
        $sql = "SELECT * FROM " . self::$TABLE . " WHERE f_name = '" . $f_name . "' AND url = '" . $url . "';";
        $stmt = $db->query($sql);

        $present = false;
        if ($stmt->rowCount() > 0) {
            $present = true;
        }

        dbUtil::close($db);

        return $present;
    }

    public static function fetch_by_name_url($f_name, $f_url) {
        $db = dbUtil::connect();
        $sql = "SELECT * FROM " . self::$TABLE . " WHERE f_name=? AND url=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($f_name, $f_url));
        dbUtil::close($db);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) != 0)
            return $result[0];
        else
            return null;
    }

}

?>