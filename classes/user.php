<?php

class User{
    public static function get_by_id($id)
    {

        global $dbi;

        $sql = sprintf("SELECT user_email, user_name FROM %s WHERE id = '%d';", DB_TBL_USER, $id);
        $results = $dbi->query_to_array($sql);

        return (!empty($results)) ? $results : false;

    }

    public static function get_by_email($email)
    {

        global $dbi;

        $sql = sprintf("SELECT * FROM %s WHERE user_email = '%s';", DB_TBL_USER, $email);
        $results = $dbi->query_to_array($sql);

        return (!empty($results)) ? $results : false;

    }

    public static function login($email, $psw){
        global $dbi;

        $sql = sprintf("SELECT id, user_email, user_name, user_password FROM %s WHERE user_email = '%s';", DB_TBL_USER, $email);
        $results = $dbi->query_to_array($sql);

        if( ! empty($results) && $email == $results["user_email"] && md5($psw) == $results["user_password"] ){
            $_SESSION["user"]["id"] = $results["id"];
            $_SESSION["user"]["email"] = $results["user_email"];
            $_SESSION["user"]["name"] = $results["user_name"];
            return true;
        }

        return false;
    }
}