<?php

class Blog
{

    public static function get_blog_details(){
        global $dbi;

        $sql = sprintf("SELECT id, title, content, date_format(created_time, '%%M %%d, %%Y') created_time FROM %s ORDER BY id DESC LIMIT 100", DB_TBL_BLOG);
        $result = $dbi->query_to_multi_array($sql);

        return ( ! empty($result) ) ? $result : false;

    }
    public static function save_blog( $save_data ){
        global $dbi;

        $dbi->insert(DB_TBL_BLOG, $save_data);
    }
}