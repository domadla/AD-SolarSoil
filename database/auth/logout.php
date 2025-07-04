<?php
ob_start();

session_start();

ob_clean();

session_destroy();

header('Location: /index.php?logout=success');
exit();
?>