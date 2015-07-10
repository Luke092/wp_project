<?php
namespace RSSAggregator\model;
use PDO;
class dbUtil {

    public static $HOSTNAME = "localhost";
    public static $DBNAME = "RssAggregator";
    public static $CHARSET = "utf8";
    public static $DB_USERNAME = "rss";
    public static $DB_PASSWORD = "wp_rss15";

    public static function connect() {
        return new PDO("mysql:host=" . self::$HOSTNAME . ";dbname=" . self::$DBNAME . ";charset=" . self::$CHARSET, self::$DB_USERNAME, self::$DB_PASSWORD);
    }

    public static function close($db) {
        $db = null;
    }

    public static function insert($table, $fields, $values) {
        $valuesArray = array('field0' => $values[0]);

        $sql = "INSERT INTO $table ";
        if ($fields != null) {
            $sql .= "(";
            $sql .= implode(",", $fields);
            $sql .= ") ";
        }
        $sql .= "VALUES (:field0";
        for ($i = 1; $i < count($values); $i++) {
            $sql .= ",:field$i";
            $valuesArray["field$i"] = $values[$i];
        }
        $sql .= ");";

        $db = self::connect();
        $stmt = $db->prepare($sql);
        $stmt->execute($valuesArray);

        self::close($db);

        return !self::checkError($stmt);
    }

    public static function delete($table, $idnames, $idvalues) {
        $sql = "DELETE FROM $table WHERE $idnames[0]=:$idnames[0]";
        for ($i = 1; $i < count($idnames); $i++) {
            $sql .= " AND " . $idnames[$i] . "=:" . $idnames[$i];
        }
        $sql .= ";";
        $db = self::connect();
        $stmt = $db->prepare($sql);
        for ($i = 0; $i < count($idnames); $i++) {
            $stmt->bindValue(":$idnames[$i]", $idvalues[$i], PDO::PARAM_STR);
        }
        $stmt->execute();
        self::close($db);

        return !self::checkError($stmt);
    }

    // updates the entry with id $idvalues setting the fields specified in $fields to the new values specified in $values.
    // return true if query was executed with success, false otherwise
    public static function update($table, $fields, $values, $idnames, $idvalues) {
        $sql = "UPDATE $table SET $fields[0] = :$fields[0]";
        for ($i = 1; $i < count($fields); $i++) {
            $sql .= ", $fields[$i] = :$fields";
        }
        $sql .= " WHERE $idnames[0] = :$idnames[0]";
        for ($i = 1; $i < count($idnames); $i++) {
            $sql .= " AND $idnames[$i] = :$idnames[$i]";
        }

        $db = self::connect();
        $stmt = $db->prepare($sql);
        self::mybind($stmt, $fields, $values);
        self::mybind($stmt, $idnames, $idvalues);
        $stmt->execute();
        self::close($db);

        return self::checkError($stmt);
    }

    private static function mybind($stmt, $fields, $values) {
        for ($i = 0; $i < count($fields); $i++) {
            $stmt->bindValue(":$fields[$i]", $values[$i], PDO::PARAM_STR);
        }
    }

    public static function checkError($stmt) {
        if ($stmt->errorInfo()[0] !== '00000') { // if an error has taken place
            echo $stmt->errorInfo()[2]; // the message that describes the error
            return true;
        }
        return false;
    }

    public static function alreadyPresent($table, $fields, $values) {
        $db = dbUtil::connect();
        $sql = "SELECT * FROM " . $table . " WHERE ";
        $sql .= "$fields[0] = :field0";
        $valuesArray = array('field0' => $values[0]);

        for ($i = 1; $i < count($values); $i++) {
            $sql .= " AND " . $fields[$i] . " = :field$i";
            $valuesArray["field$i"] = $values[$i];
        }
        $sql .= ";";
//        echo $sql;
        $stmt = $db->prepare($sql);
        $stmt->execute($valuesArray);
//        echo " ".$stmt->rowCount()." ";
        dbUtil::close($db);

        $present = false;
        if ($stmt->rowCount() > 0) {
            $present = true;
        }

        return $present;
    }

}

?>