<?php
session_start();

if (isset($_POST['login'])) {
    $login = $_POST['login'];
    if ($login == '') {
        unset($login);
    }
}
if (isset($_POST['pass'])) {
    $pass = $_POST['pass'];
    if ($pass == '') {
        unset($pass);
    }
}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
    if ($password == '') {
        unset($password);
    }
}
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if ($email == '') {
        unset($email);
    }
}
// Включаем словарь, если вызвано не переключение языка
if (!isset($_POST['lang'])) {
    include("dictionary.php");
}
// Переключение языка
if (isset($_POST['lang'])) {
    // Сохраняем текущий язык в сессию, включаем словарь
    $_SESSION['lang'] = $_POST['lang'];
    include("dictionary.php");
} // Авторизация
else if (!empty($login) and !empty($pass)) {
    // Обрабатываем введенные данные, чтобы теги и скрипты не работали
    $login = trim(htmlspecialchars(stripslashes($login)));
    $pass = trim(htmlspecialchars(stripslashes($pass)));
    // Делаем запрос к таблице users
    include("db.php");
    $result = $conn->query("SELECT * FROM users WHERE login='$login'");
    $conn->close();
    $myrow = $result->fetch_array();
    // Если не найден юзер с текущим логином, сохраняем флаг ошибки, чтобы отобразить ее на форме авторизации
    if (empty($myrow['id'])) {
        $signInError = true;
    } // Если пароль не совпадает, также сохраняем флаг
    else if (!password_verify($pass, $myrow['password'])) {
        $signInError = true;
    } // Если все хорошо - сохраняем идентификатор пользователя в сессию и перенаправляем на страницу профайла
    else {
        $_SESSION['user'] = $myrow['id'];
        header("Location: index.php");
        die();
    }
} // Регистрация
else if (!empty($login) and !empty($password) and !empty($email)) {
    if (isset($_POST['name'])) $name = $_POST['name'];
    if (isset($_POST['birth'])) $birth = $_POST['birth'];
    // Обрабатываем введенные данные, чтобы теги и скрипты не работали
    $login = trim(htmlspecialchars(stripslashes($login)));
    $password = trim(htmlspecialchars(stripslashes($password)));
    $email = trim(htmlspecialchars(stripslashes($email)));
    $name = trim(htmlspecialchars(stripslashes($name)));
    $birth = trim(htmlspecialchars(stripslashes($birth)));
    // Хэшируем пароль
    $password = password_hash($password, PASSWORD_DEFAULT);
    // Если введена дата - парсим ее и преобразуем в нужный для бд формат,
    // на тот случай если дата вводилась вручную из firefox или ie
    if (strlen($birth)) {
        $birthObj = date_create($birth);
        // Если дата распарсилась, форматируем
        if ($birthObj)
            $birth = $birthObj->format('Y-m-d');
    }
    // Обрабатываем данные радиокнопок выбора пола, ничего не выбрано - NULL, мужской - 1(true), женский - 0(false)
    $sex = NULL;
    if (isset($_POST['sex'])) $_POST['sex'] == 'man' ? $sex = 1 : $sex = 0;
    // Если добавлен аватар - читаем файл, чтобы сохранить его в виде BLOB
    $ava = NULL;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['tmp_name']) {
        $tmpName = $_FILES['avatar']['tmp_name'];
        $fp = fopen($tmpName, 'r');
        $ava = fread($fp, filesize($tmpName));
        $ava = addslashes($ava);
        fclose($fp);
    }
    // Подписка на новости - 1(true), 0(false)
    if (isset($_POST['news'])) $news = ($_POST['news'] ? 1 : 0);

    include("db.php");
    // Проверка на существование пользователя с таким же логином
    $result = $conn->query("SELECT id FROM users WHERE login='$login'");
    $myrow = $result->fetch_array();
    // Если такой пользователь есть - сохраняем текст ошибки, чтобы отобразить ее на форме регистрации
    if (!empty($myrow['id'])) {
        $conn->close();
        $signUpError = $n_28;
    } // Если такого нет, то сохраняем данные
    else {
        $res1 = $conn->query("INSERT INTO users (login,password) VALUES('$login','$password')");
        // Получаем генерируемый базой айди пользователя, после вставки в таблицу users
        $id = $conn->insert_id;
        // Сохраняем данные в таблицу user_info
        $res2 = $conn->query("INSERT INTO user_info (user,email,name,birth,sex,ava,news)
              VALUES('$conn->insert_id','$email','$name','$birth','$sex','$ava','$news')");
        $conn->close();
        // Если запросы выполнились успешно - сохраняем айди пользователя в сессию и перенаправляем на страницу профайла
        if ($res1 === TRUE && $res2 === TRUE) {
            $_SESSION['user'] = $id;
            header("Location: index.php");
            die();
        } // Если запросы выполнились с ошибкой - сохраняем текст для отображения ошибки на форме регистрации
        else {
            $signUpError = $n_29;
        }
    }
}

?>


<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="js/reg.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<div class="content tabs">

    <h2 tab="enter-tab" <?php if (!isset($signUpError)) echo 'class="active"'; ?>
        onclick="changeTab(this);"><?php echo $n_14; ?></h2>

    <h2 tab="reg-tab" <?php if (isset($signUpError)) echo 'class="active"'; ?>
        onclick="changeTab(this);"><?php echo $n_15; ?></h2>

    <div class="lang">
        <div class="ru" title="ru" onclick="switchLang(this)">
            <form action="login.php" method="post">
                <input type="hidden" name="lang" value="ru">
            </form>
        </div>
        <div class="eng" title="eng" onclick="switchLang(this)">
            <form action="login.php" method="post">
                <input type="hidden" name="lang" value="eng">
            </form>
        </div>
    </div>

    <hr class="clearfix"/>

    <!-- Форма авторизации -->
    <form action="login.php" method="post" id="enter-tab" <?php if (!isset($signUpError)) echo 'class="active"'; ?> >
        <?php if (isset($signInError))
            echo '<p class="submit-error">' . $n_27 . '</p>';
        ?>
        <p>
            <label><?php echo $n_1; ?>:</label>
            <input name="login" type="text" required="required" oninput="checkVal(this);" pattern="[A-Za-z0-9]{6,10}"
                   size="10" maxlength="10">
            <span class="icon"></span>
        </p>

        <p>
            <label><?php echo $n_2; ?>:</label>
            <input name="pass" id="pass" type="password" required="required" pattern="[A-Za-z0-9]{6,10}"
                   oninput="checkVal(this);" size="10" maxlength="10">
            <span class="icon"></span>
        </p>
        <p class="description"><?php echo $n_30; ?></p>
        <hr/>
        <p>
            <input type="submit" name="submit" value="<?php echo $n_21; ?>" class="btn">
        </p>
    </form>

    <!-- Форма регистрации -->
    <form action="login.php" method="post" id="reg-tab" enctype="multipart/form-data"
          onsubmit="signUp(event)" <?php if (isset($signUpError)) echo 'class="active"'; ?> >
        <?php if (isset($signUpError))
            echo '<p class="submit-error">' . $signUpError . '</p>';
        ?>
        <p class="description"><?php echo $n_18; ?></p>

        <p>
            <label><?php echo $n_1; ?>*:</label>
            <input id="login" name="login" type="text" required="required" oninput="checkLogin(this);"
                   pattern="[A-Za-z0-9]{6,10}"
                   size="10" maxlength="10">
            <span class="icon"></span>
            <span class="error-desc"><?php echo $n_17; ?></span>
        </p>

        <p class="description"><?php echo $n_1 . $n_19; ?></p>

        <p>
            <label>E-mail*:</label>
            <input name="email" type="email" required="required" oninput="checkVal(this);" size="255" maxlength="255">
            <span class="icon"></span>
        </p>

        <p>
            <label><?php echo $n_2; ?>*:</label>
            <input name="password" id="password" type="password" required="required" pattern="[A-Za-z0-9]{6,10}"
                   oninput="checkVal(this); checkPass(this);" size="10" maxlength="10">
            <span class="icon"></span>
        </p>

        <p class="description"><?php echo $n_2 . $n_19; ?></p>

        <p>
            <label><?php echo $n_3; ?>*:</label>
            <input name="password2" id="password2" type="password" required="required" pattern="[A-Za-z0-9]{6,10}"
                   oninput="checkPass(this);" size="10" maxlength="10">
            <span class="icon"></span>
            <span class="error-desc"><?php echo $n_16; ?></span>
        </p>
        <hr/>
        <p>
            <label><?php echo $n_4; ?>:</label>
            <input name="name" type="text" size="255" maxlength="255">
        </p>

        <p>
            <label><?php echo $n_5; ?>:</label>
            <input name="birth" type="date" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}"
                   title="dd.mm.yyyy" placeholder="dd.mm.yyyy">
        </p>

        <p>
            <label><?php echo $n_6; ?>:</label>

            <input name="sex" type="radio" value="man" id="man"><label for="man"><?php echo $n_12; ?></label>
            <input name="sex" type="radio" value="woman" id="woman"><label for="woman"><?php echo $n_13; ?></label>
        </p>

        <p class="ava">
            <label><?php echo $n_7; ?>:</label>
            <img id="avatar-img" src=""/>
            <a class="loadfile btn" onclick="sendClickToInput()"><?php echo $n_10; ?></a>
            <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/gif" lang="<?php echo $lang;?>"
                   onchange="onAvaDownload(this)"/>
        </p>

        <p class="description"><?php echo $n_20; ?></p>

        <p>
            <label><?php echo $n_8; ?>:</label>
            <input name="news" id="news" type="checkbox" checked="checked"><label for="news"><?php echo $n_9; ?></label>

        </p>
        <hr/>
        <p>
            <input type="submit" name="submit" value="<?php echo $n_11; ?>" class="btn">
        </p>
    </form>
</div>
</body>
</html>