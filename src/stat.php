<?php
class stat implements JsonSerializable {
    
    private $user_id;
    
    private $articles = array();
    
    public function __construct($email) {
        $this->user_id = $email;
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
        return json_decode($json);
    }
    
}
