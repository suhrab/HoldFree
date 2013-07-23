<?php
if ($action == 'send')
{
    $captcha = isset($_POST['captcha']) ? filter_var($_POST['captcha'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
    $first_name = isset($_POST['first_name']) ? filter_var($_POST['first_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $topic = isset($_POST['topic']) ? trim($_POST['topic']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (!$captcha) {
        throw new ExceptionImproved ('Введите код безопасности, пожалуйста');
    }

    if (!isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] !== $captcha) {
        throw new Exception ('Неверный код безопасности');
    }

    if (!$topic) {
        throw new ExceptionImproved ('Введите тему сообщения, пожалуйста');
    }

    if (!$email) {
        throw new ExceptionImproved ('Введите корректный email, пожалуйста');
    }

    if (!$message) {
        throw new ExceptionImproved ('Введите текст сообщения, пожалуйста');
    }

    switch ($topic)
    {
        case 1:
            $topic = 'Техническая поддержка';
            break;
        case 2:
            $topic = 'Правообладатель';
            break;
        case 3:
            $topic = 'Пресса';
            break;
        case 4:
            $topic = 'Сообщение об ошибке';
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

