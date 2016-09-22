<?php
namespace AppWeb;

trait InputTrait {
   /**
    * @return \flat\input\map
    */
   protected function _get_input(string $requestMethod, string $contentType) {
      $input = new \flat\input\map;
      /*
       * prepare input object
       */
      if ($requestMethod=='GET') {
         /*
          * GET request method means input is in pre-parsed query string
          */
         $input = new \flat\input\map( $_GET );
   
      } else {
         /*
          * POST, PUT, DELETE, etc request methods...
          *    the input COULD be available preparsed query string (with $_POST, etc)
          *       --but--
          *    need to handle request's with JSON data input
          *       also...
          *    PHP doesn't put all request methods input data into a convenience super global
          */
         if (empty($contentType)) {
            $contentType = '';
         }
         //\flat\core\debug::dump($contentType,'content type');
   
         $rawinput = file_get_contents('php://input');
         if (!empty($rawinput)) {
            if (false !== (strpos($contentType,"application/json"))) {
   
               if (false !== ($json = json_decode($rawinput))) {
                  $input = new \flat\input\map($json);
               } else {
                  trigger_error(
                        "malformed JSON in request document",
                        E_USER_ERROR
                        );
               }
            } else {
               /*
                * determine if input is JSON document
                */
               $json = json_decode($rawinput);
               if (false !== $json && NULL !==$json) {
                  //\flat\core\debug::dump($json,"implied json input");
                  $input = new \flat\input\map( $json );
               } else {
                  //\flat\core\debug::dump($rawinput,"raw input");
                  /*
                   * determine if input is query string
                   *    (like browser processed HTML forms do by default for POST, etc actions)
                   */
                  parse_str($rawinput, $input);
                   
                  if (!empty($input)) {
                     $input = new \flat\input\map($input);
                  } else {
                     $input = new \flat\input\map();
                  }
               }
   
            }
         }
   
      }
   
      return $input;
   }   
}