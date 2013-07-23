<?php

if (!defined('CHECK')) {
    exit;
}

// Извлекаем список стран
$qh = $pdo->query('SELECT id, code, name FROM hf_country');
$qh->execute();
$countries = $qh->fetchAll();
$qh = null;
$smarty->assign('countries', $countries);

if ($action == 'delete')
{
    $id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

    if ($id == 0) {
        throw new ExceptionImproved('Необходимо указать ID пользователя!');
    }

    $_user->delete($id);

    die('{"success": "true"}');
}
elseif ($action == 'get')
{
    $id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

    if ($id == 0) {
        throw new ExceptionImproved('Необходимо указать ID пользователя!');
    }

    $user_data = $_user->get($id);

    die('{"success": "true", "user": '. json_encode($user_data) .'}');
}
elseif ($action == 'update')
{
    $id                         = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $user_data['email']         = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
    $user_data['first_name']    = isset($_POST['first_name']) ? filter_var($_POST['first_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $user_data['last_name']     = isset($_POST['last_name']) ? filter_var($_POST['last_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $user_data['country']       = isset($_POST['country']) ? filter_var($_POST['country'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $user_data['group']         = isset($_POST['group']) ? filter_var($_POST['group'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $user_data['ban']           = isset($_POST['ban']) ? 1 : 0;
    $user_data['ban_end']       = isset($_POST['ban_end']) ? strtotime($_POST['ban_end']) : 0;
    $user_data['ban_reason']    = isset($_POST['ban_reason']) ? filter_var($_POST['ban_reason'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

    if (!$user_data['ban']) {
        $user_data['ban_end'] = '';
        $user_data['ban_reason'] = '';
    }

    $_user->update($id, $user_data);

    header('Location: /index.php?module=user&dashboard=1');
    exit;
}
elseif ($action == 'edit_form')
{
    $id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $user_data = $_user->get($id);

    $user_data['ban_end'] = $user_data['ban_end'] ? date('d-m-Y', $user_data['ban_end']) : '';

    $smarty->assign('user', $user_data);
    $smarty->display('_dashboard/user_edit_form.tpl');
    exit;
}
elseif ($action == 'addform')
{
    $smarty->display('_dashboard/user_add_form.tpl');
    exit;
}
elseif ($action == 'add')
{
    require_once(DIR_CLASS . 'passwordLib/bootstrap.php');
    $hasher = new \PasswordLib\PasswordLib();
    $user_data = array();

    $user_data['first_name'] = isset($_POST['first_name']) ? filter_var($_POST['first_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $user_data['last_name'] = isset($_POST['last_name']) ? filter_var($_POST['last_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $user_data['email'] = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
    $user_data['password'] = $hasher->createPasswordHash($_POST['password']);
    $user_data['country'] = isset($_POST['country']) ? filter_var($_POST['country'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $user_data['group'] = isset($_POST['group']) ? filter_var($_POST['group'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $user_data['avatar'] = 'default.png';

    $sth = $pdo->prepare('INSERT INTO hf_user SET first_name = :first_name, last_name = :last_name, email = :email, `password` = :password, country =:country, `group` = :group, avatar = :avatar, reg_date = UNIX_TIMESTAMP()');
    $sth->bindParam(':first_name', $user_data['first_name']);
    $sth->bindParam(':last_name', $user_data['last_name']);
    $sth->bindParam(':email', $user_data['email']);
    $sth->bindParam(':password', $user_data['password']);
    $sth->bindParam(':country', $user_data['country']);
    $sth->bindParam(':group', $user_data['group']);
    $sth->bindParam(':avatar', $user_data['avatar']);
    $sth->execute();

    header('Location: /index.php?module=user&dashboard=1');
    exit;
}


// Извлекаем информацию о пользователе
$users = array();

$sth = $pdo->prepare('SELECT u.id, u.avatar, u.first_name, u.last_name, u.last_signin, u.reg_date, u.files, u.ban, c.name country FROM hf_user u LEFT JOIN hf_country c ON u.country = c.id ORDER BY u.id DESC LIMIT 10');
$sth->execute();

while ($user = $sth->fetch()) {
    $user['reg_date'] = date('d.m.Y / h:i', $user['reg_date']);
    $user['last_signin'] = date('d.m.Y / h:i', $user['last_signin']);
    $users[] = $user;
}

$sth = null;
$smarty->assign('users', $users);


$smarty->display('_dashboard/user.tpl');