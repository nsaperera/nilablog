<?php
class View_controller {

    Public function list(){
        $blog_list = Blog::get_blog_details();

        include_once( TEMPLATE_PATH . "dashboard.php" );
    }

    Public function admin_list(){
        global $user_id;
        if( $user_id ){
            $blog_list = Blog::get_blog_details();
            include_once( TEMPLATE_PATH . "admin_list.php" );
        }else{
            $_SESSION["error"] = "Please Login!!!";
            include_once( TEMPLATE_PATH . "error_404.php" );
        }

        
    }

}