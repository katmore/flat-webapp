<?php
namespace AppWeb;

use AppWeb\ViewMetaEvent as event;
use AppRoute\View\Meta;
use flat\core\config;

class ViewTmpl extends \flat\tmpl
   implements
   \flat\route\unresolved_consumer
   ,\flat\tmpl\design
   ,\flat\tmpl\data_provider
   ,\flat\tmpl\design_base
   ,\flat\route\ignorable
{
    
   public static function get_design_base() {
       
      return config::get('App/ViewTmpl/design');
   
   }
   
   public function get_design() {
       
      return config::get('App/ViewTmpl/design');
   
   }
   
   private $_data;
   private $_ignore_tmpl=false;
   
   public function is_route_ignored() {
      return $this->_ignore_tmpl;
   }
   
   public function get_data() {
      return $this->_data;
   }
   
   public function set_unresolved($resource) {
   
      
      $res = explode("\\",$resource);
      
      $view = array_shift($res);
      
      if (empty($view)) {
         $resource = "";
         $view = "home";
      }
      $tmpl = $this;
      event::trigger(function($data=null) use ($view,&$tmpl) {
         
         if ($data instanceof Meta) {
            $data->subtitle = $view;
            $data->view = $view;
         }
         
      });
   
      if (!$this::check_design($view)) {
          
         return $this->_ignore_tmpl=true;
         
      }
   
   }
   
   
}