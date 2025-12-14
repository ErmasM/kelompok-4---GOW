<?php
session_start();
session_destroy();
header("Location: index.php"); // Diubah dari login.php ke index.php
exit;
?>