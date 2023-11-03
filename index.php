<?php

    define( "FROM_INDEX", true );
    define( "SESSIONS", true );
    include_once( "init.php" );
    
    $module = $segment1 = SYSTEM::uri_segments( 1 );   
    $method = $segment2 = SYSTEM::uri_segments( 2, "index" );

    // Load and execute the module
    $module = ucfirst( $module ) . MODULE_SUFFIX;
 
    //die(MODULES_PATH . $module . ".php");
    if( file_exists( MODULES_PATH . $module . ".php" ) && class_exists( $module ) ) $module = new $module();  

    if( ! empty( $module ) && method_exists( $module , $method ) ) $module->$method();

    if( empty( $segment1 ) )  run_module("view/list");
    else if( ! empty( $segment1 ) && ( empty( $module ) || ! method_exists( $module, $method ) ) ) include_once( TEMPLATE_PATH . "error_404.php" );