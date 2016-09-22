<?php
namespace AppWeb;

use AppWeb\ViewMetaEvent as event;
use flat\core\config;

abstract class ViewMeta extends \flat\data
   implements 
      \flat\data\ready,
      \flat\core\routable
{
   
   public $title;
   public $subtitle;
   public $homeUrl;
   public $aboutUrl;
   public $contactUrl;
   public $logoUrl;
   
   public $view;
   
   
   const NON_UCFIRST_WORDS = [
      'of','a','the','and','an','or','nor','but','is','if','then','else','when',
      'at','from','by','on','off','for','in','out','over','to','into','with'
   ];
   
   //public $title = 'App';
   public function data_ready() {
      
      $this->title = config::get('App/ViewTmpl/title');
      
      $meta = $this;
      event::set_handler(function() use(& $meta) {
         return $meta;
      });
   }   
   
   public function getFullTitle($separator = " - ") {
      $tpart = [];
      if ($this->subtitle) $tpart[] = str_replace(['_','-'],' ',$this->subtitle);
      if ($this->title) $tpart[] = $this->title;
      $title =  implode($separator,$tpart);
      $titleWord = explode(' ',$title);
      foreach($titleWord as &$word) {
         if (!in_array($word,self::NON_UCFIRST_WORDS)) {
            $word = ucfirst($word);
         } else {
            $word = lcfirst($word);
         }
      }
      return implode(' ',$titleWord);
   }
}





