<?php
namespace AppWeb;

use \flat\asset;
use \flat\core\config;

class ComponentsUrl extends asset 
   implements 
      asset\base,
      asset\system_base
{
   const CONFIG_KEY_PREFIX = 'App/ComponentsUrl';
   public function _get_system_base() {
      return config::get(self::CONFIG_KEY_PREFIX.'/system_path');
   }
   
   public function _get_base() {
      return config::get(self::CONFIG_KEY_PREFIX.'/url_base');
      
   }
   
}








