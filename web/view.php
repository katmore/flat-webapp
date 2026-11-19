<?php
use AppWeb\ViewController;

/**
 * Entry point for html/view Controller
 */
return new class() {
   

   /*
    * display_errors and error_reporting are not set here: they come from PHP's
    * configuration (php.ini; the Docker image selects it with docker/bin/serve --production).
    */

   /**
    * string
    *    change this to the path of the webapp 'app' directory
    */
   const APP_DIR = __DIR__ . '/../app';
   
   /**
    * string
    *    'name' of route to correspond to to routing autoload configuration;
    *    for example, when the name is "my_route" a file in the location
    *    {APP_DIR}/config/routing/my_route.php would need to exist.
    */   
   const ROUTE_NAME = 'view';
   
   /**
    * string
    *    the request method used when it cannot be determined from $_SERVER['REQUEST_METHOD']
    */
   const FALLBACK_REQUEST_METHOD = 'GET';
   
   /**
    * @uses self::APP_DIR
    * @uses self::ROUTE_NAME
    */
   public function __construct() {
      
      require realpath(self::APP_DIR).'/autoload.php';
      
      $routeNamespace = require realpath(self::APP_DIR) . '/config/routing/'.self::ROUTE_NAME .'.php';
      
      if (!empty($_SERVER ['PATH_INFO'] )) {
         $resource = $_SERVER ['PATH_INFO'];
      } else {
         $resource = '/';
      }
      
      
      if (!empty($_SERVER["CONTENT_TYPE"])) {
         $contentType = $_SERVER["CONTENT_TYPE"];
      } else {
         $contentType = '';
      }
      
      if (!empty($_SERVER['REQUEST_METHOD'])) {
         $requestMethod = $_SERVER['REQUEST_METHOD'];
      } else {
         $requestMethod = self::FALLBACK_REQUEST_METHOD;
      }
      
      new ViewController($resource,self::APP_DIR, $routeNamespace, $requestMethod, $contentType);
      
   }
};


































































