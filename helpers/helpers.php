<?php if ( ! defined('FROM_INDEX')) die();

// Auto Load classes http://php.net/manual/en/language.oop5.autoload.php
spl_autoload_register(function ($class_name) {
    
        if ( file_exists( MODULES_PATH . DIRECTORY_SEPARATOR . $class_name . '.php' ) ) {
                require MODULES_PATH . DIRECTORY_SEPARATOR . $class_name . '.php';
        }
       
});




function run_module( $module, $save_global = false ){
    
    $module = explode( "/", $module );
    if( count( $module ) !==2 || empty( trim( $module[0] ) || empty( trim( $module[1] ) ) ) ) return false;
    
    $class  = ucfirst( trim( $module[0] ) ) . MODULE_SUFFIX;
    $method = trim( $module[1] );
    
    if( file_exists( MODULES_PATH . $class . ".php" ) && class_exists( $class ) ){
        if( $save_global ) {
            
            $GLOBALS['modules'][ $module[0] ] =  new $class();
            
        } else {
            
            $obj = new $class();
            if( ! empty( $obj ) && method_exists( $obj , $module[1] ) ) return $obj->$method();
            
        }
    }
    
    return false;
}