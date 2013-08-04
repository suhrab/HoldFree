<?php
if ($action == 'send')
{
    $captcha = isset($_POST['captcha']) ? filter_var($_POST['captcha'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
    $first_name = isset($_POST['first_name']) ? filter_var($_POST['first_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $topic = isset($_POST['topic']) ? trim($_POST['topic']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (!$captcha) {
        throw new ExceptionImproved (gettext('Введите код безопасности, пожалуйста'));
    }

    if (!isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] !== $captcha) {
        throw new Exception (gettext('Неверный код безопасности'));
    }

    if (!$topic) {
        throw new ExceptionImproved (gettext('Введите тему сообщения, пожалуйста'));
    }

    if (!$email) {
        throw new ExceptionImproved (gettext('Введите корректный email, пожалуйста'));
    }

    if (!$message) {
        throw new ExceptionImproved (gettext('Введите текст сообщения, пожалуйста'));
    }

    switch ($topic)
    {
        case 1:
            $topic = gettext('Техническая поддержка');
            break;
        case 2:
            $topic = gettext('Правообладатель');
            break;
        case 3:
            $topic = gettext('Пресса');
            break;
        case 4:
            $topic = gettext('Сообщение об ошибке');
            break;
    }

    $message = htmlentities($message);
    $created = time();

    $sth = $pdo->prepare('INSERT INTO hf_feedback SET first_name = :first_name, topic = :topic, email = :email, message = :message, created = :created');

    $sth->bindParam(':first_name', $first_name);
    $sth->bindParam(':topic', $topic);
    $sth->bindParam(':email', $email);
    $sth->bindParam(':message', $message);
    $sth->bindParam(':created', $created);

    $sth->execute();
    $sth = null;

    die('{"success": 1}');
}

