<?php
namespace RSSAggregator\model;
use PDO;
class category {

    public static $ALREADY_PRESENT = 0;
    public static $ERROR_INSERT = 1;
    public static $CORRECT_INSERT = 2;
    private static $TABLE = "Categories";
    private $id;
    private $c_name;
    private $is_default;
    private $feeds = array();
    private $email;

    public function __construct($id, $email = null) {
        $this->id = $id;
        $attributes = $this->getCatAttributes();
        $this->c_name = $attributes["c_name"];
        $this->is_default = $attributes["is_default"];
        $this->buildFeedArray($email);
        $this->email = $email;
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
        if ($email != null) {
            $sql = "SELECT f_id FROM `UCF` WHERE email = ? AND c_id = ?;";
            $stmt = $db->prepare($sql);
            $stmt->execute(array($email, $this->id));
        } else {
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
            if ($email != null) {
                $this->feeds[$i++] = new feed($row['f_id']);
            } else {
                $this->feeds[$i++] = new feed($row['id']);
            }
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->c_name;
    }

    public function is_default_cat() {
        return (($this->is_default == 1) ? true : false);
    }

    public function add_Feed($f_name, $url, $default_cat) {
        $res = feed::insert($f_name, $url, $default_cat);
        if ($res == feed::$ERROR_INSERT) {
            return false;
        }
        $feed = new feed(feed::fetch_by_name_url($f_name, $url)['id']);
        $this->add_Feed_obj($feed);
        $this->feeds = array();
        $this->buildFeedArray($this->email);
    }
    
    public function add_Feed_obj($feed){
        if($this->getFeedByNameURL($feed->getName(), $feed->getURL()) == false){
            feed::insert_UCF_data($this->email, $this->id, $feed->getId());
        }
        else{
            return false;
        }
        return true;
    }

    public function remove_Feed($feed) {
        $f_id = $feed->getId();
        $res = feed::delete_UCF_data($this->email, $this->id, $f_id);

        if ($res) {
            $this->buildFeedArray($this->email);
            return true;
        } else {
            return false;
        }
    }

    public function getFeedByNameURL($name, $url) {
        foreach ($this->feeds as $feed) {
            if ($feed->getName() == $name && $feed->getURL() == $url) {
                return $feed;
            }
        }
        return false;
    }
    
    public function getFeedById($id) {
        foreach ($this->feeds as $feed) {
            if ($feed->getId() == $id) {
                return $feed;
            }
        }
        return false;
    }

    public function get_array() {
        return $this->feeds;
    }

    public static function insert($c_name) {
//        if (!self::alreadyPresent($c_name)) {
        if (!dbUtil::alreadyPresent(self::$TABLE, ["c_name"], [$c_name])) {
            if (dbUtil::insert(self::$TABLE, array("c_name", "is_default"), array($c_name, "0"))) {
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
        return dbUtil::delete("UCF", array("email", "c_id"), array($email, $id));
    }

    public static function modifyName($id, $newName) {
        return dbUtil::update(self::$TABLE, array("c_name"), [$newName], array("id"), [$id]);
    }

    private static function alreadyPresent($c_name) {
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

    public static function fetch_by_name($c_name) {
        $db = dbUtil::connect();
        $sql = "SELECT * FROM " . self::$TABLE . " WHERE c_name=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($c_name));
        dbUtil::close($db);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) != 0)
            return $result[0];
        else
            return null;
    }

}

?>