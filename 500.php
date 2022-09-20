<?php 
header("HTTP/1.0 500 Internal Server Error");

require_once "includes/autoloader.inc.php";

$doc = Singleton::GetBoilerPlateInstance();
$doc -> BeginHTML();
?>

<div class="container">
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh">
      <div class="text-center">
        <h1 class="display-2 fw-bold">
            <span style="color: var(--color-primary-override);">5</span>
            <span style="color: var(--color-danger-override);">0</span>
            <span style="color: var(--color-danger-override);">0</span>
        </h1>
        <h4 class="mb-4">Sorry, Something happened on our end :(</h4>
        <h6 class="mb-2 fw-bold">(Don't worry, it's not your fault.)</h6>
        <p class="text-center">INTERNAL SERVER ERROR</p>
        <a class="btn btn-primary btn-primary-override btn-lg btn-rounded" href="login.php" role="button">Back to safety</a>
      </div>
    </div>
</div>

<?php $doc -> EndHTML(); ?>