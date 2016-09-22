<?php
namespace AppWeb;

class WidgetTmpl extends \flat\tmpl
   implements
   \flat\route\unresolved_consumer
   ,\flat\tmpl\data_provider
   ,\flat\tmpl\design_base
   ,\flat\route\ignore
{
   
   private $_data;
   private $_ignore_tmpl=false;
   
   const TMPL_DESIGN_BASE='\\widget';
   
   static public function get_design_base() {
      return self::TMPL_DESIGN_BASE;
   }
   
   public function is_route_ignored() {
      return $this->_ignore_tmpl;
   }
   
   public function get_data() {
      return $this->_data;
   }
   
   public function set_unresolved($resource) {
   
      
      $res = explode("\\",$resource);
      
      $page = array_shift($res);
      
      if (empty($page)) {
         $resource = "";
         $page = "home";
      }
      
      $this->_data = (object) array(
         'page'=>$page,
         'resource'=>$resource,
      );
      
      if (empty($this->_data->title)) $this->_data->title = $page;
   
      if (!$this::check_design($page)) {
          
         return $this->_ignore_tmpl=true;
         
      }
   
   }
   
   
}