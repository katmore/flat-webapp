<?php
namespace AppWeb;

use \flat\core\config;

/**
 * RESTful API Controller
 */
class ApiController 
{
   
   use InputTrait;
   
   use JsonErrorTrait;
   
   public function __construct(string $resource, string $appDir,string $routeNamespace, string $requestMethod, string $contentType='', \flat\input\map $input=null) {
      
      /*
       * prepare config controller
       */
      config::set_base_dir ( $appDir.'/config/flat' );
      
      if (!$input) $input = $this->_get_input($requestMethod, $contentType);
      
      if ($requestMethod=="GET") {
         foreach (array('POST','PUT','DELETE') as $invoke) {
            if (isset($input->$invoke)) {
               $requestMethod = $invoke;
               unset($input->$invoke);
               break 1;
            }
         }
      }      
      
      /*
       * prepare api controller: set HTTP request method to what is determined
       * by the interface handler
       */
      \flat\core\controller\api::set_method ( $requestMethod );
      
      $this->_enable_custom_errors();
      
      ob_start();
      /*
       * set the api response handler using \flat\deploy\api\interface_handler
       */
      \flat\core\controller\api::set_response_handler (function ($response) {
         
         if ($errlist = error_get_last() ) {
            $this->_error_check($errlist);
         }
         
         $ob = ob_get_clean();
         
         if (!empty($ob)) {
            echo $ob;
            trigger_error("non-empty output buffer, cannot produce valid json response",E_USER_ERROR);
            return;
         }
         
         if (headers_sent()) {
            
            trigger_error("headers already sent, cannot produce valid json response",E_USER_ERROR);
         }
         
         
         if ($response instanceof \flat\api\response) {
            //header($_SERVER["SERVER_PROTOCOL"]." ".$response->get_status()->get_str());
            http_response_code($response->get_status()->get_code());
         }
         
         header('Content-Type: application/json',true);
         $response = (array) json_decode(json_encode($response));
         if (isset($response['data'])) {
            $response = $response['data'];
         }
         echo json_encode($response);
      });
          
         /*
          * process api call through api routing controller
          */
         $api = new \flat\core\controller\route ([
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
                        'traverse'=>true,
                     ))
                     );
               $this->add_route(
                     new \flat\route\rule(array(
                        'ns'=>$routeNamespace.'\Dependency',
                        'weight'=>1,
                        'iterate'=>true,
                        'traverse'=>true,
                     ))
                     );   
   
               $this->add_route(
                     new \flat\route\rule(array(
                        'ns'=>$routeNamespace.'\Resolve',
                        'weight'=>2,
                        'iterate'=>false,
                        'traverse'=>false,
                     ))
                     );
               $this->add_route(
                     new \flat\route\rule(array(
                        'ns'=>'\flat\api\response\forbidden\any_method',
                        'weight'=>2,
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
         if (! $api->get_resolve_count ()) {
            trigger_error ( "no resolution for resource", E_USER_ERROR );
         }
   
   }   
}