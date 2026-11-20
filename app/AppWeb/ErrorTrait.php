<?php
namespace AppWeb;

trait ErrorTrait {
   public function _error_check(array $errdata=null) {
      if (empty($errdata)) {
         $errdata = error_get_last();
      }
      // warnings, notices and deprecations are logged by PHP and do not end the request
      if (isset($errdata['type']) && is_int($errdata['type']) && !ErrorType::is_fatal($errdata['type'])) return;
      if ($errlist['last_error'] = $errdata ) {
         if ($level = ob_get_level()) for($i=0;$i<$level;$i++) {
            ob_get_clean();
         }
         if (!headers_sent()) http_response_code (500 );
         
         error_log(json_encode($errlist['last_error'],\JSON_PARTIAL_OUTPUT_ON_ERROR|\JSON_UNESCAPED_SLASHES));
         if (!headers_sent()) header('Content-Type: text/plain',true);
         echo "Unable to process this request";
         exit(1);
      }
   }
   public function _enable_custom_errors() {
      
      set_error_handler(function($errno , $errstr , $errfile =null, $errline =null, $errcontext =null) {
         // honor error_reporting(): errors outside the level, and ones silenced with @,
         // are ignored (returning true marks them handled, so error_get_last() stays clean)
         if (!(error_reporting() & $errno)) return true;
         // Warnings, notices and deprecations: let PHP log them and carry on.
         if (!ErrorType::is_fatal($errno)) return false;
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
            $trace[] = substr((isset($value["file"]) ? $value["file"].'('.$value["line"].')' : '[internal]'). $func,0,400);
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
            if ($error && ErrorType::is_fatal($error['type'])) {
               if ($level = ob_get_level()) for($i=0;$i<$level;$i++) {
                  ob_get_clean();
               }
               $this->_error_check();
            }
         });      
   }
}