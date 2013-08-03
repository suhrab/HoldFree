<?php

if (!defined('CHECK')) {
    exit;
}

if ($action == 'add')
{
    if (!$_user->isLogged()) {
        throw new ExceptionImproved('Данная операция доступна только для зарегистрированных пользователей');
    }

    $dir_name = isset($_POST['dir_name']) ? trim($_POST['dir_name']) : '';
    $dir_name = filter_var($dir_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$dir_name) {
        throw new ExceptionImproved('Введите корректное имя папки');
    }

    $parent = 0;
    $owner = $_user->getId();

    $dir_id = $_fileManager->addDir($dir_name, $_user->getId(), $parent);

    die('{
        "success": 1,
        "dir_id": '. $dir_id .',
        "dir_name": "'. $dir_name .'",
        "parent": "'. $parent .'"
    }');
}
elseif ($action == 'rename')
{
    if (!$_user->isLogged()) {
        throw new ExceptionImproved('Данная операция доступна только для зарегистрированных пользователей');
    }

    $dir_name = isset($_POST['dir_name']) ? trim($_POST['dir_name']) : '';
    $dir_name = filter_var($dir_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$dir_name) {
        throw new ExceptionImproved('Введите корректное имя папки');
    }

    $dir_id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

    if (!$dir_id) {
        throw new ExceptionImproved('Необходимо передать ID дериктории!');
    }

    $owner = $_user->getId();

    $sth = $pdo->prepare('UPDATE hf_dir SET name = :dir_name WHERE id = :dir_id AND owner = :owner');
    $sth->bindParam(':dir_name', $dir_name);
    $sth->bindParam(':dir_id', $dir_id);
    $sth->bindParam(':owner', $owner);
    $sth->execute();
    $sth = null;

    die('{
        "success": 1,
        "dir_id": "'. $dir_id .'",
        "dir_name": "'. $dir_name .'"
    }');
}
elseif ($action == 'delete')
{
    if (!$_user->isLogged()) {
        throw new ExceptionImproved('Данная операция доступна только для зарегистрированных пользователей');
    }

    $dir_id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

    if (!$dir_id) {
        throw new ExceptionImproved('Необходимо передать ID дериктории!');
    }

    $owner = $_user->getId();

    $sth = $pdo->prepare('DELETE FROM hf_dir WHERE id = :dir_id AND owner = :owner');
    $sth->bindParam(':dir_id', $dir_id);
    $sth->bindParam(':owner', $owner);
    $sth->execute();
    $sth = null;

    die('{"success": 1}');
}
