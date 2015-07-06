<?php

class feed {

	private static $TABLE = "feeds";
	private static $T_HEADER = ["id", "f_name", "url", "default_cat"];

	public static function insert($f_name, $url, $default_cat)
	{
		return dbUtil::insert(self::$TABLE,array("f_name","url","default_cat"),array($f_name, $url, $default_cat));
	}
	
	public static function delete($id)
	{
		return dbUtil::delete(self::$TABLE,array("id"),array($id));
	}
	
	// modifies name, url and default category of the entry with the specified id.
	// It's possible to modify also only 1 or 2 attribute values by putting "null" as parameter
	// where no changes have to be made.
	public static function modifyEntry($id, $name, $url, $default_cat)
	{
		$mod = [$name, $url, $default_cat];
		for($i=0; $i<count($mod); $i++)
		{
			if($mod[$i] != null)
			{
				dbUtil::update(self::$TABLE, array(self::$T_HEADER[$i+1]), [$mod[$i]], array("id"), [$id]);
			}
		}
	}
	
	public static function modifyName($id, $newName)
	{
		return dbUtil::update(self::$TABLE,array("f_name"),[$newName],array("id"),[$id]);
	}
	
	public static function modifyURL($id, $newUrl)
	{
		return dbUtil::update(self::$TABLE,array("url"),[$newUrl],array("id"),[$id]);
	}
	
	public static function modifyDefaultCat($id, $newDef)
	{
		return dbUtil::update(self::$TABLE,array("default_cat"),[$newDef],array("id"),[$id]);
	}
}

?>