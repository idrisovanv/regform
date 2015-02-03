<?php
// Выход из сессии
session_start();
session_destroy();
header("Location: login.php");
exit;
?>