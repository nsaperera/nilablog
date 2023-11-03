<?php
class Blog_controller {

    Public function save_post(){

        global $post_data;
        
        if( ! empty($post_data["title"]) ){
            
            $save_data = [
                "title" => $post_data["title"],
                "content" => $post_data["content"]
            ];

            Blog::save_blog( $save_data );

            SYSTEM::redirect(BASE_URL);
        }

        include_once( TEMPLATE_PATH . "post_blog.php" );
    }

}