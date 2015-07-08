<?php

class feed {

    private static $ALREADY_PRESENT = 0;
    private static $ERROR_INSERT = 1;
    private static $CORRECT_INSERT = 2;
    private static $TABLE = "Feeds";
    private static $T_HEADER = ["id", "f_name", "url", "default_cat"];
    private $id, $f_name, $url, $default_cat;

    public function __construct($id) {
        $this->id = $id;
        $attributes = $this->getFeedAttributes();
        $this->f_name = $attributes["f_name"];
        $this->url = $attributes["url"];
        $this->default_cat = $attributes["default_cat"];
    }

    // returns the attributes of the feed from the database
    public function getFeedAttributes() {
        $db = dbUtil::connect();
        $sql = "SELECT * FROM " . self::$TABLE . " WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->id));
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
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

    public static function delete($id) {
        return dbUtil::delete(self::$TABLE, array("id"), array($id));
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

    public static function alreadyPresent($f_name, $url) {
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

}

?>