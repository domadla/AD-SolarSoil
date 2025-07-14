<?php
// 404 Error Page
http_response_code(404);
$error_code = '404';
$error_title = 'Page Not Found';
$error_message = 'Sorry, the page you are looking for does not exist or may have drifted to another galaxy. You seem to have reached the edge of the SolarSoil universe.';

include '../layouts/error-layout.php';