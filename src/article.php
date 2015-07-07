<?php

class article implements JsonSerializable {
    
    private $id, $c_id, $text;
    
    public function __construct($article_id, $c_id, $text) {
        $this->c_id = $c_id;
        $this->id = $article_id;
        $this->text = $text;
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
    
    public function jsonSerialize() {
        return [
            "id" => $this->id,
            "cat_it" => $this->c_id,
            "text" => $this->text
        ];
    }
    
    public static function export_JSON($art){
        return json_encode($art, JSON_UNESCAPED_SLASHES);
    }
    
    public static function import_JSON($json){
        $obj_array = json_decode($json, true);
        $article = new article($obj_array["id"], $obj_array["cat_it"], $obj_array["text"]);
        return $article;
    }
}
