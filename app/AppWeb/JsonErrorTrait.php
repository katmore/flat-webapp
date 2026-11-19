<?php
namespace AppWeb;

trait JsonErrorTrait {
   
   use ErrorTrait;
   protected static  $_checking = false;
   public function _error_check(array $errdata=null) {
      
      if (self::$_checking) {
         return;
      } else {
         //ini_set('display_errors','0');
         self::$_checking=true;
      }
      
      
      
      if (empty($errdata)) {
         $errdata = error_get_last();
      }
//       $traceSize = count($errdata['trace']);
//       var_dump( $traceSize );
//       die(__FILE__);
      //if (isset($errdata['trace'])) unset($errdata['trace']);
      
      if ($errlist['last_error'] = $errdata ) {
         
         if ($level = ob_get_level()) for($i=0;$i<$level;$i++) {
            ob_get_clean();
         }
         
         if (!headers_sent()) http_response_code (500 );
         
         // The details go to the log stream, never to the response.
         error_log(json_encode($errlist['last_error'],\JSON_PARTIAL_OUTPUT_ON_ERROR|\JSON_UNESCAPED_SLASHES));
         if (!headers_sent()) header('Content-Type: text/plain',true);
         echo "Unable to process this request";
         exit(1);
      }
   }
}