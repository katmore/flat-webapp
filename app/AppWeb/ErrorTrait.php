<?php
namespace AppWeb;

trait ErrorTrait {
   public function _error_check(array $errdata=null) {
      if (empty($errdata)) {
         $errdata = error_get_last();
      }
      if ($errlist['last_error'] = $errdata ) {
         if ($level = ob_get_level()) for($i=0;$i<$level;$i++) {
            ob_get_clean();
         }
         if (!headers_sent()) http_response_code (500 );
         
         if (ini_get('display_errors')) {
            if (!headers_sent()) header('Content-Type: application/json',true);
            echo nl2br(json_encode((object)['error_list'=>$errlist],\JSON_PRETTY_PRINT));
         } else {
            if (!headers_sent()) header('Content-Type: text/plain',true);
            echo "Unable to process this request";            
         }
         exit(1);
      }
   }
   public function _enable_custom_errors() {
      
      set_error_handler(function($errno , $errstr , $errfile =null, $errline =null, $errcontext =null) {
         //debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);
         $trace=[];
         $i=0;
         foreach(debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS,10) as $k=>$value) {
//            $trace[] = substr(substr($line,0,$pos),0,20);
            if (!empty($value["function"])) {
               $func = " ".$value["function"]."()"; 
            } else {
               $func="";
            }
            $trace[] = substr($value["file"]. '('.$value["line"].')'. $func,0,400);
            if ($i>19) break 1;
            $i++;            
         }         
         $info = [
            'type'=>'php-error',
            'name'=>'error',     
            'line'=>$errline,
            'file'=>$errfile,
            'message'=>$errstr,
            'errno'=>$errno,
            'trace'=>$trace,
            //'context'=>$errcontext,            
         ];
         $this->_error_check($info);
      });
      
      set_exception_handler(function($e) {
         $info = [
            'type'=>'exception',
            'name'=>get_class($e),
         ];
         if (($e instanceof \Exception) || ($e instanceof \Error)) {    
            $trace = [];
            //$pos = strpos($info, '-', strpos($info, '-'));
            $i=0;
            foreach(explode("\n",$e->getTraceAsString()) as $line) {
               $pos = strpos($line, '(', strpos($line, '(')+1);
               if (false===($pos = strpos($line, '(', strpos($line, '(')+1))) {
                  $trace[] = $line;
               } else {
                  $trace[] = substr(substr($line,0,$pos),0,400);
               }
               if ($i>19) break 1;
               $i++;
            }
            $info['line'] = $e->getLine();
            $info['file'] = $e->getFile();
            $info['message'] = $e->getMessage();
            $info['code'] = $e->getCode();
            //$info['trace_as_string'] = explode("\n",$e->getTraceAsString());
            $info['trace'] = $trace;
         } 
         //die(__FILE__);
         $this->_error_check($info);
      });
      
         register_shutdown_function(function() {
            $error = error_get_last();
            if (($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR)) {
               if ($level = ob_get_level()) for($i=0;$i<$level;$i++) {
                  ob_get_clean();
               }
               $this->_error_check();
            }
         });      
   }
}