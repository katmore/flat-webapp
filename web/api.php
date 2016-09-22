<?php
use AppWeb\ApiController;

/**
 * Entry point for html/api Controller
 */
return new class() {
   

   /**
    * int 
    *    override display_errors setting
    *    for example: (int) 0 will WSOD, (int) 1 will display php errors
    *
    *    ignored unless value is 0 or 1
    */
   const DISPLAY_ERRORS = 1;
   
   /**
    * int
    *    override error_reprting level 
    *    ignored unless value is integer
    */
   const ERROR_REPORTING = E_ALL & ~E_DEPRECATED;   
   
   /**
    * string
    *    change this to the path of the SpyDash 'app' directory
    */
   const APP_DIR = __DIR__ . '/../app';
   
   /**
    * string
    *    'name' of route to correspond to to routing autoload configuration;
    *    for example, when the name is "my_route" a file in the location
    *    {APP_DIR}/config/routing/my_route.php would need to exist.
    */   
   const ROUTE_NAME = 'api';
   
   /**
    * string
    *    the request method used when it cannot be determined from $_SERVER['REQUEST_METHOD']
    */
   const FALLBACK_REQUEST_METHOD = 'GET';
   
   /**
    * @uses self::DISPLAY_ERRORS
    * @uses self::ERROR_REPORTING
    * @uses self::APP_DIR
    * @uses self::ROUTE_NAME
    */
   public function __construct() {
      
      if (
            (self::DISPLAY_ERRORS === 0) ||
            (self::DISPLAY_ERRORS === 1)
      ) {
         ini_set('display_errors',self::DISPLAY_ERRORS);
      }
      
      if (
         is_int(self::ERROR_REPORTING) 
      ) {
         error_reporting(self::ERROR_REPORTING);
      }      
      
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
      
      new ApiController($resource,self::APP_DIR, $routeNamespace, $requestMethod, $contentType);
      
   }
};


































































