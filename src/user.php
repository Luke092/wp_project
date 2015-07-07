<?php

class user {

    private static $TABLE = "Users";
    private static $ALREADY_PRESENT = 0;
    private static $ERROR_INSERT = 1;
    private static $CORRECT_INSERT = 2;
    private static $categories = array();

    // returns $ALREADY_SIGNEDUP if the user is already signed up.
    // true if user has been added.
    // false if there have been errors.
    public static function insert($email, $password) {
        // make sure user isn't already in the user table
//		if(self::alreadySignedUp($email))
        if (dbUtil::alreadyPresent(self::$TABLE, ["email"], [$email]))
            return self::$ALREADY_PRESENT;
        // if not, insert new user into the table
        else {
            if (dbUtil::insert(self::$TABLE, null, array($email, $password))) {
                return self::$CORRECT_INSERT;
            } else {
                return self::$ERROR_INSERT;
            }
        }
    }

    public static function getCategoryByUser($email) {
        $db = dbUtil::connect();
        $sql = "SELECT DISTINCT c_id FROM `ucf` WHERE email = ?;";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($email));
        dbUtil::close($db);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buildCatArray($email) {
        $rows = self::getCategoryByUser($email);
        $i = 0;
        foreach ($rows as $row) {
            self::$categories[$i++] = new category($row['c_id'], $email);
            self::$categories[$i - 1]->printCategory();
        }
    }

    // returns $NOT_SIGNEDUP if user didn't exist.
    // true if user has been deleted.
    // false if there have been errors.
    public static function delete($email) {
        if (self::alreadySignedUp($email))
            return dbUtil::delete(self::$TABLE, array("email"), array($email));
        else
            return self::$NOT_SIGNEDUP;
    }

    public static function getPassword($email) {
        $data = self::getData($email);
        if ($data != false)
            return $data['password'];
        else
            return false;
    }

    public static function getData($email) {
        if (self::alreadySignedUp($email)) {
            $sql = "SELECT * FROM Users WHERE email=?";
            $db = dbUtil::connect();
            $stmt = $db->prepare($sql);
            $stmt->execute(array($email));

            if (!dbUtil::checkError($stmt))
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

            dbUtil::close($db);
            return $row;
        } else
            return self::$NOT_SIGNEDUP;
    }

    // checks if the email is already present inside the db
    public static function alreadySignedUp($email) {
        // check if email is present in user table
        $db = dbUtil::connect();
        $sql = "SELECT * FROM Users WHERE email='" . $email . "'";
        $stmt = $db->query($sql);

        $signedUp = false;
        if ($stmt->rowCount() > 0)
            $signedUp = true;

        dbUtil::close($db);
        return $signedUp;
    }

    public static function modifyEmail($oldEmail, $newEmail) { // non funziona
        $field = ["email"];
        $id = ["email"];
        return dbUtil::update(self::$TABLE, $field, [$newEmail], $id, [$oldEmail]);
    }

    public static function modifyPassword($email, $newPassword) {
        $field = ["password"];
        $id = ["email"];
        return dbUtil::update(self::$TABLE, $field, [$newPassword], $id, [$email]);
    }

}

?>