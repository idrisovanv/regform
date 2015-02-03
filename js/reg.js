// Проверка повторного ввода пароля на форме регистрации, вызывается при вводе паролей
function checkPass(sender) {
    var pass1 = document.getElementById('password');
    var pass2 = document.getElementById('password2');
    // Если пароли не совпадают - родительскому элементу добавляется класс для отображения ошибки и текстовой подсказки
    if (pass1.value != pass2.value)
        pass2.parentNode.className = "error-txt";
    // Если пароль во втором поле не соответсвует шаблону -
    // добавляем родительскому элементу класс для отображения ошибки шаблона
    else if (pass2.validity.patternMismatch)
        pass2.parentNode.className = "error";
    // Если все нормально - убираем классы с родительского элемента
    else
        pass2.parentNode.className = "";
};

// Проверка валидности вводимых значений при вводе
function checkVal(sender) {
    if (!sender.validity.valid)
        sender.parentNode.className = "error";
    else
        sender.parentNode.className = "";

};

// Проверяет, есть ли в базе пользователь с введенным логином, вызывается при вводе логина на форме регистрации
function checkLogin(sender) {
    // Сначала проверяем введенный текст на соответствие шаблону, чтобы не ждать ответа от асинхронного вызова
    checkVal(sender);
    // Создаем объект для асинхронного вызова метода БЛ
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'checkLogin.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    // Обработчик ответа
    xhr.onreadystatechange = function () {
        if (xhr.readyState != 4) return;
        // Если ответ "false" - родительскому элементу добавляется класс для отображения ошибки и текстовой подсказки
        if (xhr.responseText == "false")
            sender.parentNode.className = "error-txt";
        // Если логин не соответсвует шаблону -
        // добавляем родительскому элементу класс для отображения ошибки шаблона
        else if (!sender.validity.valid)
            sender.parentNode.className = "error";
        // Если все нормально - убираем классы с родительского элемента
        else
            sender.parentNode.className = "";
    }
    // Вызов метода и передача параметра
    xhr.send('login=' + sender.value);

};

// Эмулирует клик на скрытый элемент загрузки файла
function sendClickToInput() {
    var evObj = document.createEvent('MouseEvents');
    evObj.initEvent('click', true, false);
    document.getElementById('avatar').dispatchEvent(evObj);
};

// Обработчик загрузки файла
function onAvaDownload(sender) {
    var file = sender.files[0],
        mime = ['image/jpeg', 'image/png', 'image/gif'],
        img = document.getElementById('avatar-img'),
        isEng = sender.getAttribute('lang') == 'eng';
    // Если недопустимый тип файла - удаляем его и выводим сообщение об ошибке
    if (mime.indexOf(file.type) < 0) {
        sender.value = '';
        img.setAttribute("src", '');
        if (isEng)
            alert('Wrong format of file');
        else
            alert('Не верный формат файла');
    }
    // Если размер файла превышает 1Мб - удаляем его и выводим сообщение об ошибке
    else if (file.size > 1048576) {
        sender.value = '';
        img.setAttribute("src", '');
        if (isEng)
            alert('Image size exceeds 1MB');
        else
            alert('Размер изображения превышает 1Мб');
    }
    // Если все нормально - читаем файл и отрисовываем в предпросмотре
    else {
        var reader = new FileReader();
        reader.onload = function (e) {
            img.setAttribute("src", e.target.result);
        };
        reader.readAsDataURL(file);
    }
};

//Переключение вкладок входа/регистрации
function changeTab(sender) {
    var tab = sender.getAttribute('tab'),
        // Получаем статический NodeList, содержащий элементы с классом "active"
        cur = document.querySelectorAll('.active');
    // Убираем класс "active" с текущей формы и заголовка
    for (var i = 0; i < cur.length; i++) {
        cur[i].className = '';
    }
    // Добавляем  класс "active" на новый заголовок и форму
    sender.className = 'active';
    document.getElementById(tab).className = 'active';
};

// Отмена отправки формы регистрации, если введеный  логин уже есть в базе или подтверждение пароля не совпало
function signUp(event) {
    if (document.getElementById('login').parentNode.className == 'error-txt' ||
        document.getElementById('password2').parentNode.className == 'error-txt') {
        event.preventDefault();
    }
}

// Вызов отправки скрытой формы для переключения языка
function switchLang(sender) {
    sender.getElementsByTagName('form')[0].submit();
}