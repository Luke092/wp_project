<?php
namespace RSSAggregator\model;
use JSONSerializable;

class article implements JsonSerializable {
    
    private $id, $c_id, $text, $timestamp;
    
    public function __construct($article_id, $c_id, $text, $timestamp) {
        $this->c_id = $c_id;
        $this->id = $article_id;
        $this->text = $text;
        $this->timestamp = $timestamp;
    }
    
    public function getText(){
        return $this->text;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getCId(){
        return $this->c_id;
    }
    
    public function getTimestamp(){
        return $this->timestamp;
    }

        public function jsonSerialize() {
        return [
            "id" => $this->id,
            "cat_id" => $this->c_id,
            "timestamp" => $this->timestamp,
            "text" => $this->text
        ];
    }
    
    public static function export_JSON($art){
        return json_encode($art, JSON_UNESCAPED_SLASHES);
    }
    
    public static function import_JSON($json){
        $obj_array = json_decode($json, true);
        $article = new article($obj_array["id"], $obj_array["cat_id"], $obj_array["text"], $obj_array["timestamp"]);
        return $article;
    }
}
