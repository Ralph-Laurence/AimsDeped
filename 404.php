<?php 
header("HTTP/1.0 404 Not Found");

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
            <span style="color: var(--color-primary-override);">4</span>
        </h1>
        <h4 class="mb-4">We can't seem to find the page you're looking for.</h4>
        <h6 class="mb-2 fw-bold">Here are things you can try:</h6>
        <p class="mb-5">
            1. Check that the website address is spelled correctly.<br>
            2. Go back to the main page, and navigate using the menus.
        </p>
        <a class="btn btn-primary btn-primary-override btn-lg btn-rounded" href="login.php" role="button">Back to safety</a>
      </div>
    </div>
</div>

<?php $doc -> EndHTML(); ?>