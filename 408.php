<?php
header("HTTP/1.0 408 Request Timeout");
require_once "includes/autoloader.inc.php";

$doc = Singleton::GetBoilerPlateInstance();
$doc -> BeginHTML();

?>

<div class="container">
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh">
      <div class="text-center">
        <h1 class="display-2 fw-bold">
            <span style="color: var(--color-primary-override);">4</span>
            <span style="color: var(--color-danger-override);">0</span>  
            <span style="color: var(--color-primary-override);">8</span>
        </h1>
        <h4 class="mb-4">SERVER TIMED OUT.</h4>
        <h6 class="mb-2 fw-bold">The server timed out while waiting for client request.</h6> 
        <a class="btn btn-primary btn-primary-override btn-lg btn-rounded" href="login.php" role="button">Back to safety</a>
      </div>
    </div>
</div>

<?php $doc -> EndHTML(); ?>