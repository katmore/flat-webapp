<?php
use AppWeb\ViewMetaEvent as event;
use AppRoute\View\Meta;
use AppRoute\View\Tmpl;
use AppWeb\AssetUrl;

$viewData = new Meta;
event::trigger(function($data=null) use(&$viewData) {

   if ($data instanceof Meta) {
      $viewData = $data;
   }

});
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$viewData->getFullTitle()?></title>
    <link rel="stylesheet" href="<?=AssetUrl::asset('css/custom.css')?>">
  </head>
  <body>
    <header><a href="home">flat webapp</a></header>
    <main data-viewBody="<?=$viewData->view?>">
<?php Tmpl::display($viewData->view) ?>
    </main>
  </body>
</html>