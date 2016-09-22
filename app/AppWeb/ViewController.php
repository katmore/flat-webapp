<?php
namespace AppWeb;

use \flat\core\config;
use \flat\core\controller\route\restful_status;
/**
 * RESTful API Controller
 */
class ViewController {
   
   use InputTrait;
   
   use HtmlErrorTrait;
   
   public function __construct(string $resource, string $appDir,string $routeNamespace, string $requestMethod, string $contentType='', \flat\input\map $input=null) {
      
      /*
       * prepare config controller
       */
      config::set_base_dir ( $appDir.'/config/flat' );
      
      if (!$input) $input = $this->_get_input($requestMethod, $contentType);

      register_shutdown_function(function() {
         $error = error_get_last();
         if (($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR)) {
            if ($level = ob_get_level()) for($i=0;$i<$level;$i++) {
               ob_get_clean();
            }
            http_response_code (500 );
            $this->_error_check();
         }
      });
      ob_start();
      /*
       * prepare display handler for template system
       */
      $output_started = false;
      \flat\core\controller\tmpl::set_display_handler(
         function ($design, $data, $tmpl) use(&$output_started) {
            if ($output_started) return;
            if ($level = ob_get_level()) for($i=0;$i<$level;$i++) {
               if(!empty(ob_get_clean())) {
                  $this->_display_error_prefix();
                  if (!empty(ini_get('display_errors'))) {
                     echo "aborting template due to unexpected output";
                     $this->_error_check();
                  } else {
                     echo "Please contact support or try again.";
                  }
                  $this->_display_error_suffix();
                  exit(1);
               }
            }
            
            if (headers_sent()) {
               //trigger_error("aborting template due to unexpected headers",\E_USER_ERROR);
               $this->_display_error_prefix();
               if (!empty(ini_get('display_errors'))) {
                  echo "aborting template due to unexpected headers";
                  $this->_error_check();
               } else {
                  echo "Please contact support or try again.";
               }
               $this->_display_error_suffix();    
               exit(1);
            }
            header('Content-Type: text/html; charset=utf-8');
            
            $output_started = true;
            
            ob_start();
            $tmpl->display($design);
            $html = ob_get_clean();
            
            $this->_error_check();
            
            if ($tmpl instanceof restful_status) {
                          
               http_response_code ($tmpl->get_status_code() );
               
            }
            
            echo $html;
         });
          
         /*
          * process api call through api routing controller
          */
         $routeController = new \flat\core\controller\route ([
            'resource' => $resource,
            'input' => $input,
            'route_factory' => new class($routeNamespace) extends \flat\route\factory {
            public function get_base() {
               return "/";
            }
            public function __construct($routeNamespace) {
   
               $this->add_route(
                     new \flat\route\rule(array(
                        'ns'=>$routeNamespace.'\Helper',
                        'weight'=>0,
                        'iterate'=>true,
                     ))
                     );
   
   
               $this->add_route(
                     new \flat\route\rule(array(
                        'ns'=>$routeNamespace.'\Tmpl',
                        'weight'=>1,
                        'iterate'=>false,
                        'traverse'=>false,
                     ))
                     );
               $this->add_route(
                     new \flat\route\rule(array(
                        'ns'=>$routeNamespace.'\NotFound',
                        'weight'=>1,
                        'iterate'=>false,
                        'ignore_resource'=>true
                     ))
                     );
            }
            }
            ]);
          
         /*
          * prevent white screen of death when no route has been resolved trigger
          * an eror.
          */
         if (! $routeController->get_resolve_count ()) {
            trigger_error ( "no resolution for resource", E_USER_ERROR );
         }
   
   }   
}