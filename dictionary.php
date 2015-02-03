<?php
// Текст, который выводится на форму, в зависимисти от текущего языка, записанного в сессии
// По умолчанию - русский

$lang = 'ru';
if (isset($_SESSION['lang']))
    $lang = $_SESSION['lang'];


switch ($lang) {
    case ("eng"):
        $n_1 = "Login";
        $n_2 = "Password";
        $n_3 = "Repeat password";
        $n_4 = "Name";
        $n_5 = "Birth date";
        $n_6 = "Sex";
        $n_7 = "Avatar";
        $n_8 = "Subscription";
        $n_9 = "Site news";
        $n_10 = "Choose file";
        $n_11 = "Sign up";
        $n_12 = "Male";
        $n_13 = "Female";
        $n_14 = "Sign in";
        $n_15 = "Sign up";
        $n_16 = "Password doesn't match";
        $n_17 = "This username is already taken";
        $n_18 = "Fields marked with an asterisk are required.";
        $n_19 = " must be between 6 and 10 characters long and contain only letters or numbers.";
        $n_20 = "The image must be in one of the following formats: JPEG, PNG or GIF. Size should not exceed 1MB.";
        $n_21 = "Sign in";
        $n_22 = "Profile";
        $n_23 = "Log out";
        $n_24 = "Subscribe site news";
        $n_25 = "Yes";
        $n_26 = "No";
        $n_27 = "Wrong login or password.";
        $n_28 = "Login you entered is already registered.";
        $n_29 = "Error! You are not logged in.";
        $n_30 = "Login and password must be between 6 and 10 characters long and contain only letters or numbers.";
        break;

    case ("ru"):
    default:
        $n_1 = "Логин";
        $n_2 = "Пароль";
        $n_3 = "Повторите пароль";
        $n_4 = "Имя";
        $n_5 = "Дата рождения";
        $n_6 = "Пол";
        $n_7 = "Аватар";
        $n_8 = "Подписка";
        $n_9 = "Новости сайта";
        $n_10 = "Выберите файл";
        $n_11 = "Зарегистрироваться";
        $n_12 = "Мужской";
        $n_13 = "Женский";
        $n_14 = "Вход";
        $n_15 = "Регистрация";
        $n_16 = "Пароль не совпадает";
        $n_17 = "Этот логин уже занят";
        $n_18 = "Поля, помеченные звездочкой, обязательны для заполнения.";
        $n_19 = " должен быть длиной от 6 до 10 символов и содержать только латинские буквы или цифры.";
        $n_20 = "Изображение должно быть в одном из следующих форматов: JPEG, PNG или GIF. Размер не должен превышать 1 Мб.";
        $n_21 = "Войти";
        $n_22 = "Профиль";
        $n_23 = "Выйти";
        $n_24 = "Подписка на новости";
        $n_25 = "Да";
        $n_26 = "Нет";
        $n_27 = "Неверный логин или пароль.";
        $n_28 = "Введённый вами логин уже зарегистрирован.";
        $n_29 = "Ошибка! Вы не зарегистрированы.";
        $n_30 = "Логин и пароль должны быть длиной от 6 до 10 символов и содержать только латинские буквы или цифры.";
        break;
}

?>