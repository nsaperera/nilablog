<?php
class Login_controller {

    Public function user_login(){
        global $post_data;

        if( ! empty($post_data["email"]) ){

            if( ! User::login($post_data["email"], $post_data["password"]) ) {
                unset( $_SESSION["user"]);
                $_SESSION["login_error"] = "Invalid User Name or Password !!!";
            }

        }
        SYSTEM::redirect(BASE_URL);
    }

    Public function user_logout(){

        session_start();
        session_unset();
        session_destroy();

        SYSTEM::redirect(BASE_URL);
    }

}