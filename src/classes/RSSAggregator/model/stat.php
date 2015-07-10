<?php
namespace RSSAggregator\model;
use PDO;
class stat implements JsonSerializable {
    
    private $user_id;
    
    private $articles = array();
    
    public function __construct($email) {
        $this->user_id = $email;
    }
    
    public function set_articles($articles){
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
    
}
