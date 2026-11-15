<?php
use AppWeb\ComponentsUrl;
use AppWeb\AssetUrl;
?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?=ComponentsUrl::asset('jquery/dist/jquery.min.js')?>"   crossorigin="anonymous"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- Latest compiled and minified JavaScript -->
<script src="<?=ComponentsUrl::asset('bootstrap/dist/js/bootstrap.min.js')?>" crossorigin="anonymous"></script>


<!-- custom js -->
<script src="<?=AssetUrl::asset('js/custom.js')?>"></script>

<!-- AppWeb js -->
<?=AssetUrl::asset('js/AppWeb.js')->print_script()?>