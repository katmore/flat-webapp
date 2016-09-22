<?php
/**
 * \flat\app\tmpl\ap\notfound class 
 *
 * PHP version >=5.6
 * 
 * Copyright (c) 2012-2014 Garrison Koch, Doug Bird, and Daniel Lepthien. 
 *    All Rights Reserved. 
 * 
 * LICENSE: NO LICENSE, EXPRESS OR IMPLIED, BY THE COPYRIGHT OWNERS
 * OR OTHERWISE, IS GRANTED TO ANY INTELLECTUAL PROPERTY IN THIS SOURCE FILE.
 * 
 * @license NO LICENSE GRANTED
 */
/**
 * namespace
 */
namespace AppWeb;

use \flat\app\event\error;
use \flat\app\event\meta\app;
/**
 * notfound
 * 
 * @package    flat\app\ap\reeltrend
 * @author     D. Bird <retran@gmail.com>
 * @copyright  Copyright (c) 2012-2014 Garrison Koch, Doug Bird, and Daniel Lepthien. All Rights Reserved.
 * @version    0.1.0-alpha
 * 
 */
class NotFoundTmpl extends \flat\tmpl 
   implements \flat\tmpl\design, 
   \flat\core\controller\route\restful_status,
   \flat\core\resolver,
   \flat\tmpl\design_base
{
   const base_design="\\error\\minimal";
   public static function get_design_base() {
      return self::base_design;
   }   
   public function set_resource($resource) {
      $app = NULL;
      app::trigger(function($eventdata=NULL) use(& $app) {
         $app = $eventdata;
      });
      error::set_handler(function($data=NULL) use(& $resource, & $app) {
         return (object) array(
            'resource'=>$resource,
            'message'=>'resource "'.str_replace("\\","/",$resource).'" does not exist',
            'application'=>$app
         );
      });
   }
   /**
    * design namespace as string literal
    */
   const error_404_design="\\error\\minimal\\404";
   /**
    * @see \flat\core\controller\route\restful_status
    *    makes controller give 404 status code to HTTP client
    */
   public function get_status_code() {
      return 404;
   }
   /**
    * @see \flat\core\controller\route\restful_status
    *    makes controller give 404 status to HTTP client
    */   
   public function get_status_string() {
      return "Not Found";
   }   
   public function get_design() {
      return self::error_404_design;
   }
}
















