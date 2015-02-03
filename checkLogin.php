<?php
// Проверка на существование пользователя с таким же логином
// Вызывается асинхронно во время ввода логина на форме регистрации

if (!isset($_POST['login'])) return;
$login = $_POST['login'];
$login = trim(htmlspecialchars(stripslashes($login)));
include("db.php");
$result = $conn->query("SELECT id FROM users WHERE login='$login'");
$conn->close();
$myrow = $result->fetch_array();
if (!empty($myrow['id']))
    echo 'false';
else
    echo 'true';
?>