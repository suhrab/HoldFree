<?php

if (!defined('CHECK')) {
    exit;
}

if ($action == 'signup')
{
    $captcha = isset($_POST['captcha']) ? filter_var($_POST['captcha'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
    $first_name = isset($_POST['first_name']) ? filter_var($_POST['first_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $country = isset($_POST['country']) ? filter_var($_POST['country'], FILTER_SANITIZE_NUMBER_INT) : '';
    $password = isset($_POST['password']) ? filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : ''; // TODO Обработать

    if (!$captcha) {
        throw new Exception ('Введите код безопасности, пожалуйста');
    }

    if (!isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] !== $captcha) {
        throw new Exception ('Неверный код безопасности');
    }

    if (!$first_name) {
        throw new ExceptionImproved ('Введите Ваше имя, пожалуйста');
    }

    if (!$email) {
        throw new ExceptionImproved ('Введите корректный email, пожалуйста');
    }

    if (!$password) {
        throw new ExceptionImproved ('Введите пароль, пожалуйста');
    }

    if (!$country) {
        throw new ExceptionImproved ('Укажите Вашу страну, пожалуйста');
    }

    $user_data = $_user->getByEmail($email);

    if ($user_data) {
        throw new ExceptionImproved('Пользователь с таким email адресом уже зарегестрирован.');
    }

    if ($config['email_filter']) {
        preg_match('/(.+)@(.+)/i', $email, $matches);

        if (in_array($matches[2], $config['email_filter'])) {
            throw new Exception('Регистрация через почтовый провайдер '. $matches[2] .' запрещена!');
        }
    }

    $_user->signUp($first_name, $email, $country, $password);

    die('{"success": 1}');
}
elseif ($action == 'signin')
{
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
    $password = isset($_POST['password']) ? filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

    if (!$email) {
        throw new Exception ('Введите корректный email, пожалуйста');
    }

    if (!$password) {
        throw new Exception ('Введите пароль, пожалуйста');
    }

    $_user->signIn($email, $password);

    die('{"success": 1}');
}
elseif ($action == 'profile')
{
    $user_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

    if (!$user_id) {
        throw new ExceptionImproved('Такой страницы не существует!', 'Ошибка 404', 404);
    }

    $user_data = $_user->get($user_id);

    if (!$user_data) {
        throw new ExceptionImproved('Такой страницы не существует!', 'Ошибка 404', 404);
    }

    $qh = $pdo->query('SELECT id, code, name FROM hf_country');
    $qh->execute();
    $countries = $qh->fetchAll();
    $qh = null;
    $smarty->assign('countries', $countries);

    $smarty->assign('user_data', $user_data);
    $smarty->display('profile.tpl');
}
elseif ($action == 'edit')
{
    if (!$_user->isLogged()) {
        throw new ExceptionImproved('Для совершения данной операции необходима авторизация');
    }

    // User ID
    $user_id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

    if ($_user->getId() !== $user_id) {
        throw new ExceptionImproved('Для совершения данной операции необходима авторизация');
    }

    // Email
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';

    if (!$email) {
        throw new ExceptionImproved ('Введите корректный email, пожалуйста');
    }

    $_user->setEmail($email);

    // First name
    $first_name = isset($_POST['first_name']) ? filter_var($_POST['first_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

    if ($first_name) {
        $_user->setFirstName($first_name);
    }

    // Last name
    $last_name = isset($_POST['last_name']) ? filter_var($_POST['last_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

    if ($last_name) {
        $_user->setLastName($last_name);
    }

    // Country
    $country = isset($_POST['country']) ? filter_var($_POST['country'], FILTER_SANITIZE_NUMBER_INT) : '';

    if ($country) {
        $_user->setCountry($country);
    }

    die('{"success": 1}');
}
elseif ($action == 'signout')
{
    $_user->signOut();
    header('Location: ' . URL);
    exit;
}
