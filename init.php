<?php
    
    ini_set( "memory_limit", "1024M" );
    include_once( "loader.php" );
    
    // Start Session
	if( defined( "SESSIONS" ) && SESSIONS ){
		session_save_path( ABS_PATH . "__sessions" );        
		ini_set( "session.gc_maxlifetime", 86400 * 3 );
        session_start();
	}
    
    // Connect to the Database
    $dbi = new DBI();
    $dbi->connect( CMS_DB_HOST, CMS_DB_USER, CMS_DB_PASSWORD, CMS_DB_NAME );
    $dbi->log_query_execution_time = false;

    $user_id = isset( $_SESSION["user"]["id"] ) ? $_SESSION["user"]["id"] : 0;
    if ( $user_id && ! $user_data = User::get_by_id( $user_id ) ) {
        unset( $_SESSION["user"] );
        $user_id = 0;
    }

    if($user_id){
        $user_name = $_SESSION["user"]["name"];
    }

    $post_data = $_POST;
    $get_data = $_GET;

    $flg_error = ( ! empty($_SESSION["login_error"]) ) ? $_SESSION["login_error"] : false;
    unset($_SESSION["login_error"]);


