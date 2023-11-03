<?php

    // General
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    
    define( "MODULE_SUFFIX", "_controller" );
    
    define( "CLASSES_DIR", "classes" );
    define( "MODULES_DIR", "modules" );
    define( "HELPERS_DIR", "helpers" );
    define( "PUBLIC_DIR", "public" );
    define( "ASSETS_DIR", "assets" );
    define( "TEMPLATE_DIR", "template" );

    
    define( "ABS_PATH", __DIR__ . DIRECTORY_SEPARATOR  );
    define( "CLASSES_PATH", ABS_PATH . CLASSES_DIR . DIRECTORY_SEPARATOR );    
    define( "MODULES_PATH", ABS_PATH . MODULES_DIR . DIRECTORY_SEPARATOR );
    define( "HELPERS_PATH", ABS_PATH . HELPERS_DIR . DIRECTORY_SEPARATOR );
    define( "TEMPLATE_PATH", ABS_PATH . TEMPLATE_DIR . DIRECTORY_SEPARATOR );

    
    define( "BASE_URL", "http://localhost/blog/");  // ************ Need to change to domain and folder

    
    define( "TEMPLATE_URL", BASE_URL . "/template/" );
    define( "TEMPLATE_CSS_URL", BASE_URL . PUBLIC_DIR . "/" . ASSETS_DIR . "/css/");
    define( "TEMPLATE_JS_URL", BASE_URL . PUBLIC_DIR . "/" . ASSETS_DIR . "/js/");
    
    // // DB
    define( "CMS_DB_HOST", "localhost" );
    define( "CMS_DB_USER", "root" );
    define( "CMS_DB_PASSWORD", "" );
    define( "CMS_DB_NAME", "blog" ); 
   

    