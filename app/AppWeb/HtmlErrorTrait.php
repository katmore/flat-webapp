<?php
namespace AppWeb;

trait HtmlErrorTrait {
   public function _display_error_prefix() {
      echo "<pre><hr><span style='transform: rotate(90deg); display:inline-block;'>:(</span>we are experiencing difficulties<hr></pre>";
   }
   public function _display_error_suffix() {
      echo "<hr><pre>Generated: ".date("c")."</pre><hr>";
   }
   public function _error_check() {
      if ($errlist['Last Error'] = error_get_last() ) {
         
         if (!empty($errlist['Last Error']['type'])) {
            if (! ( $errlist['Last Error']['type'] & error_reporting())) {
               return;
            }
         }
         
         if ($level = ob_get_level()) for($i=0;$i<$level;$i++) {
            ob_get_clean();
         }         
         
         
         $errlist['backtrace'] = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
         
         if (!headers_sent()) http_response_code (500 );
         
         $this->_display_error_prefix();
         if (class_exists('\flat\core\html\encode')) {
            echo \flat\core\html\encode::serialize((object)['Errors'=>$errlist]);
         } else {
            echo nl2br(json_encode((object)['Errors'=>$errlist],\JSON_PRETTY_PRINT));
         }
         $this->_display_error_suffix();
         exit(1);
      }
   }
}