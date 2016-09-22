<?php
use \flat\app\asset\error\lib;
use \flat\app\event\meta\app;
$page = (object) [
   'title'=>'error',
   'heading'=>'error',
   'description'=>'we are experiencing difficulties',
];
$section['application'] = false;
$section['description'] = false;
$section['message'] = true;

$tmpl='\flat\app\tmpl\error';
$app=(object) [];
app::trigger(function($data) use(&$app) {
   var_dump($data);
   if (!empty($data) && is_object($data)) $app = $data;
   \flat\core\debug::kill();
});

if (!class_exists($tmpl) || !is_a($tmpl,'flat\tmpl',true)) $tmpl=NULL;
if (is_object($data))
foreach ($page as $prop=>&$val) if (isset($data->$prop)) $val=$data->$prop;
?>
<!doctype html>
<html>
   <head>
      <title><?=$page->title?></title>
      <meta http-equiv="content-type" content="text/html;charset=utf-8">
      <meta http-equiv="Content-Style-Type" content="text/css">
      <link href="<?=lib::asset('branded/styles.css')?>" rel="stylesheet" type="text/css">
      <style> /*style definitions for "sections"*/
      [data-frown] { transform: rotate(90deg); display:inline-block;}
      [data-timestamp] { font-size: 0.75em;}
      
      <?php
      /*
       * adds CSS as appropriate for each potential "section"
       */
      foreach($section as $s=>$enabled) {
         if (isset($_GET) && isset($_GET["enable_".$s]) || isset($_GET) && isset($_GET["enable-".$s])) {
            if (filter_var($_GET["enable_".$s],\FILTER_VALIDATE_BOOLEAN) || filter_var($_GET["enable-".$s],\FILTER_VALIDATE_BOOLEAN)) {
               $enabled = true;
            }
         }
         if ($enabled) {
         ?>
         [data-<?=$s?>]:after {content: "\a"; white-space: pre;} 
         <?php
         } else {
         ?>
         [data-<?=$s?>] {display: none;}
         <?php
         }
      }
      ?>
      </style><!--end (style definitions for "sections")-->
   </head>
   <body>
<div id="wrapper">
		<div id="bg-top"></div>
		<div id="contentWrap">
			<div id="content">
				
				<!-- BEGIN LEFT COLUMN -->
				<div id="leftColumn">
				
					<!-- YOUR LOGO GOES HERE -->
					<a href="http://www.activepitch.com/"><img id="logo" src="<?=$logo?>" alt="Logo Sample" width="265" height="65"/></a>
					
					<!-- BEGIN MENU -->
					<div id="nav">
						<span>menu</span>
						<ul>
							<li class="home"><a href="http://www.activepitch.com/">Home</a></li>
							<li class="about"><a href="http://www.activepitch.com/">About Us</a></li>
							<li class="contact"><a href="mailto:info@activepitch.com">Contact Us</a></li>
						</ul>
					</div><!-- end div #nav -->
					
					<!-- ERROR CODE HERE -->
					<h1><?=$page->title?></h1>
				</div><!-- end div #leftColumn -->
				<!-- END LEFT COLUMN -->
				
				<!-- BEGIN RIGHT COLUMN -->
				<div id="rightColumn">
					<h2><?=$page->heading?></h2>
					<p><?=($tmpl)?$tmpl::format('section/message',array('silent_fail'),array('data'=>$page)):""?></p>
					<br>
					<br>
					<br>
					<h4 class="regular"><strong>Lost? We suggest...</strong></h4>
					<ol>
						<li><span>If you typed in the page address, make sure it is spelled correctly.</span></li>
						<li><span>Click the back button to try another link.</span></li>
						<li><span>visiting our home page (link to the left).</span></li>
						<li><span>contact ActivePitch (link to the left). </span></li>
					</ol>
					
					<!-- BEGIN SEARCH FORM - EDIT YOUR DOMAIN BELOW -->
					
					<!-- END SEARCH FORM -->
					
					<!-- Close Button For AJAX Included Version
					<a id="close" href="#">close</a>
					-->
				</div><!-- end div #rightColumn -->
				<!-- END RIGHT COLUMN -->
				
				<div class="clear"></div>
			</div><!-- end div #content -->
		</div><!-- end div #contentWrap -->
		<div id="bg-bottom"></div>
	</div><!-- end div #wrapper -->
   </body> 
</html>