<?php
require_once "includes/autoloader.inc.php";

// For processing dates
date_default_timezone_set("Asia/Manila");
$doc = Singleton::GetBoilerPlateInstance();
$doc->BeginHTML();

?>

<div class="container-fluid h-100 position-relative bg-dark">
test
</div>

<?php $doc -> EndHTML(); ?>
