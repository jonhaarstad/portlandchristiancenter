<?php /* Redirects those sneaking around in directories they don't belong in ;-) */

require_once '../config/subdirectories.php';
require_once '../gdphp/config/engineconfig.php';
$url = BASE_URL; // Define the URL:
header("Location: $url");

?>