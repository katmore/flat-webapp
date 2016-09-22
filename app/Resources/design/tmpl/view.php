<?php
use SpyDashWeb\WidgetTmpl;
use SpyDashWeb\ViewMetaEvent as event;
use SpyDashRoute\View\Meta;
use SpyDashRoute\View\Tmpl;
use SpyDashWeb\ComponentsUrl;
use SpyDashWeb\AssetUrl;

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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?=$viewData->getFullTitle()?></title>

   <!-- Latest compiled and minified CSS -->
   <link rel="stylesheet" href="<?=ComponentsUrl::asset('bootstrap/dist/css/bootstrap.min.css')?>">
   
   <!-- croppie css -->
   <link rel="stylesheet" href="<?=ComponentsUrl::asset('Croppie/croppie.css')?>" />
   
   <!-- custom css -->
   <link rel="stylesheet" href="<?=AssetUrl::asset('css/custom.css')?>" />

  </head>
  <body>
<?php WidgetTmpl::display("loading-overlay"); ?>

<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">MiceSpy Dash</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
          <ul data-role="nav-items" class="nav navbar-nav">
            <li><a href="batch-cropper">Batch Cropper</a></li>
            <li><a href="photo-queue">Photo Queue</a></li>
            <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Answer Data <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="attractions">Attractions</a></li>
            <li><a href="parks">Parks</a></li>
            <li><a href="areas">Areas</a></li>
            <li><a href="types">Types</a></li>
          </ul>
        </li>
        <li><a href="answers">Answers</a></li>
             <li><a href="users">Users</a></li>
             <li><a href="stats">stats</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
</nav>

<?php WidgetTmpl::display("alert-set"); ?>

<div data-viewBody="<?=$viewData->view?>" >
<?php Tmpl::display($viewData->view) ?>

</div>

<?php WidgetTmpl::display("script"); ?>

  </body>
</html>