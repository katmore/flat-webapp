<?php
namespace AppWeb;

use AppWeb\ErrorEvent as error;
use AppWeb\ViewMetaEvent as app;
/**
 * Template for the not-found (404) page.
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
