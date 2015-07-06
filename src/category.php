<?php

class category {
	
	private static $TABLE = "categories";

	public static function insert($name)
	{
		return dbUtil::insert(self::$TABLE,array("c_name"),array($name));
	}
	
	public static function delete($id)
	{
		return dbUtil::delete(self::$TABLE,array("id"),array($id));
	}

	public static function modifyName($id, $newName)
	{
		return dbUtil::update(self::$TABLE,array("c_name"),[$newName],array("id"),[$id]);
	}
	
}

?>