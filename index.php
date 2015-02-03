<?php
session_start();
// Сохраняем текущий язык в сессию, включаем словарь
if (isset($_POST['lang'])){
    $_SESSION['lang']=$_POST['lang'];
}
include("dictionary.php");

// Если сессия не содержит айди текущего пользователя - редирект на страницу авторизации
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    die();
}
// Получаем айди текущего пользователя и выполняем запрос к бд, чтобы получить его данные и отобразить на форме
$id = $_SESSION['user'];
include("db.php");
$result = $conn->query("SELECT * FROM users INNER JOIN user_info ON users.id = user_info.user WHERE users.id='$id'");
$conn->close();
$user_info = $result->fetch_array();

?>


<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="js/reg.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div class="content profile">


    <h2><?php echo $n_22; ?></h2>

    <div class="lang">
        <div class="ru" title="ru" onclick="switchLang(this)">
            <form action="index.php" method="post">
                <input type="hidden" name="lang" value="ru">
            </form>
        </div>
        <div class="eng" title="eng"  onclick="switchLang(this)">
            <form action="index.php" method="post">
                <input type="hidden" name="lang" value="eng">
            </form>
        </div>
    </div>
    <hr class="clearfix"/>
    <p><label><?php echo $n_1; ?>:</label> <?php echo $user_info['login']; ?></p>

    <p><label>E-mail:</label> <?php echo $user_info['email']; ?></p>

    <?php
    if ($user_info['name'] != '')
        echo '<p><label>' . $n_4 . ':</label>' . $user_info['name'] . '</p>';

    if ($user_info['birth'] != '0000-00-00')
        echo '<p><label>' . $n_5 . ':</label>' . $user_info['birth'] . '</p>';

    if (!is_null($user_info['sex']))
        echo '<p><label>' . $n_6 . ':</label>' . ($user_info['sex'] == 1 ? $n_12 : $n_13) . '</p>';

    if (!empty($user_info['ava'])) : ?>
        <p><label><?php echo $n_7; ?>:</label>
            <img id="avatar-img" src="data:image/jpeg;base64,<?php echo base64_encode($user_info['ava']) ?>">
        </p>
    <?php endif; ?>

    <p><label><?php echo $n_24; ?>:</label> <?php echo($user_info['news'] == 1 ? $n_25 : $n_26); ?></p>
    <hr/>
    <form action="exit.php" method="post">
        <p>
            <input type="submit" name="submit" value="<?php echo $n_23; ?>" class="btn">
        </p>
    </form>

</div>
</body>
</html>