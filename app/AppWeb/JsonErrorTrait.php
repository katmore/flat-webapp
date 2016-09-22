<?php
namespace AppWeb;

trait JsonErrorTrait {
   
   use ErrorTrait;
   protected static  $_checking = false;
   protected static $_real_display_errors = null;
   public function _error_check(array $errdata=null) {
      
      if (is_null(self::$_real_display_errors)) {
         self::$_real_display_errors = ini_get('display_errors');
      }
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
         
         if (self::$_real_display_errors) {
            
               if (!headers_sent()) {
                  header('Content-Type: application/json',true);
               }
               //die(__FILE__);
//                echo $errlist['last_error']['message'];
//                die(__FILE__);
               echo json_encode(['error_list'=>$errlist]);
         } else {
            if (!headers_sent()) header('Content-Type: text/plain',true);
            echo "Unable to process this request";
         }
         exit(1);
      }
   }
}