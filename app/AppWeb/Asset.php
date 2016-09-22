<?php
namespace AppWeb;
abstract class Asset extends \flat\asset implements \flat\asset\base , \flat\asset\system_base {
   const FALLBACK_BASE = '/flat/asset';
   
   const VERSION_BASE = '';
   
   abstract public function _get_version_base();
   private function _concat_base() {
      if (!empty($this->_get_version_base())) {
         return "/".$this->_get_version_base();
      }
      return "";
   }
   public function _get_base() {
      
      $version = config::get("app/asset/version/active/version",['default'=>false]);
      if (!$version) {
         return self::fallback_base.$this->_concat_base();
      }
      $url_base = config::get("app/asset/version/url_base");
      return "$url_base/$version".$this->_concat_base();
   }
   public function _get_system_base() {
      return config::get("app/asset/version/system_base").$this->_concat_base();
      
//       $version = config::get("app/asset/version/active/version",['default'=>false]);
//       if (!$version) {
//          return self::fallback_base.$this->_concat_base();
//       }      
//       $base = config::get("app/asset/version/system_base");
//       return "$base/$version".$this->_concat_base();
   }
}