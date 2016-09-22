<?php
use SpyDashWeb\ComponentsUrl;
use SpyDashWeb\AssetUrl;
?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?=ComponentsUrl::asset('jquery/dist/jquery.min.js')?>"   crossorigin="anonymous"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- Latest compiled and minified JavaScript -->
<script src="<?=ComponentsUrl::asset('bootstrap/dist/js/bootstrap.min.js')?>" crossorigin="anonymous"></script>

<!-- croppie js -->
<script src="<?=ComponentsUrl::asset('Croppie/croppie.min.js')?>"></script>

<!-- custom js -->
<script src="<?=AssetUrl::asset('js/custom.js')?>"></script>

<!-- SpyDashWeb js -->
<?=AssetUrl::asset('js/WebApp.js')->print_script()?>