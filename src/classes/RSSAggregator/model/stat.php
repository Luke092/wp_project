<?php
namespace RSSAggregator\model;
use PDO;
use JSONSerializable;

class stat implements JsonSerializable {
    
    public static $CORRECT_INSERT = 1;
    public static $ERROR_INSERT = 0;
    public static $ALREADY_PRESENT = 2;
    
    private $user_id;
    
    private $articles = array();
    
    private static $TABLE = "Stats";
    
    private function __construct($email) {
        $this->user_id = $email;
    }
    
    private function set_articles($articles){
        $this->articles = $articles;
    }


    public function feed_count($c_id){
        $count = 0;
        foreach ($this->articles as $article) {
            if($article->getCId() == $c_id){
                $count++;
            }
        }
        return $count;
    }
    
    public function get_wc_text($c_id){
        $wc = "";
        foreach ($this->articles as $article) {
            if($article->getCId() == $c_id){
                $wc .= $article->getText() . " ";
            }
        }
        return $wc;
    }
    
    public function addArticle($art){
        foreach ($this->articles as $article) {
            if($article->getId() == $art->getId()){
                return false;
            }
        }
        $this->articles[] = $art;
        return true;
    }
    
    public function removeArticle($art){
        foreach ($this->articles as $key => $article) {
            if($article->getId() == $art->getId()){
                unset($this->articles[$key]);
                return true;
            }
        }
        return false;
    }
    
    public function getArticleById($id){
        foreach ($this->articles as $article) {
            if($article->getId() == $id){
                return $article;
            }
        }
        return false;
    }
    
    public function saveStatsToFile(){
        $path = self::fetch_data($this->user_id)['file_path'];
        $json = self::export_JSON($this);
        $res = file_put_contents($path, $json);
        return ($res != false)? true : false;
    }
    
    public function jsonSerialize() {
        return [
            "email" => $this->user_id,
            "feeds" => $this->articles
        ];
    }
    
    public static function export_JSON($stat){
        return json_encode($stat, JSON_UNESCAPED_SLASHES);
    }
    
    public static function import_JSON($json){
        $obj_array = json_decode($json, true);
        $stat = new stat($obj_array['email']);
        $article_array = array();
        foreach ($obj_array['feeds'] as $a){
            $article = new article($a["id"],$a["cat_it"],$a["text"]);
            $article_array[] = $article;
        }
        $stat->set_articles($article_array);
        
        return $stat;
    }
    
    public static function getStat($email){
        $elem = self::fetch_data($email);
        if($elem != null){
            $path = $elem['file_path'];
            if(!file_exists($path) && !is_readable($path)){
                return null;
            }
            else{
                $json = file_get_contents($path);
                return self::import_JSON($json);
            }
        }
        else{
            $path = "./json/stats/" . $email . ".json";
            self::insert($email, $path);
            return new stat($email);
        }
    }
    
    private static function fetch_data($email){
        $db = dbUtil::connect();
        $sql = "SELECT * FROM". self::$TABLE ."WHERE email = ?";
        $stm = $db->prepare($sql);
        $stm->execute(array($email));
        dbUtil::close($db);
        $elems = $stm->fetchAll(PDO::FETCH_ASSOC);
        return ($elems != null)? $elems[0] : null;
    }
    
    private static function alreadyPresent($email) {
        $db = dbUtil::connect();
        $sql = "SELECT * FROM " . self::$TABLE . " WHERE email = '" . $email . "';";
        $stmt = $db->query($sql);

        $present = false;
        if ($stmt->rowCount() > 0) {
            $present = true;
        }

        dbUtil::close($db);

        return $present;
    }
    
    private static function insert($email, $file_path){
        if(!self::alreadyPresent($email)){
            if (dbUtil::insert(self::$TABLE, array("email", "file_path"), array($email, $file_path))) {
                return self::$CORRECT_INSERT;
            } else {
                return self::$ERROR_INSERT;
            }
        } else {
            return self::$ALREADY_PRESENT;
        }
    }
}
