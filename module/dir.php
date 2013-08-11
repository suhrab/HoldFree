<?php

if (!defined('CHECK')) {
    exit;
}

if ($action == 'add')
{
    if (!$_user->isLogged()) {
        throw new ExceptionImproved(gettext('Данная операция доступна только для зарегистрированных пользователей'));
    }

    $dir_name = isset($_POST['dir_name']) ? trim($_POST['dir_name']) : '';
    $dir_name = filter_var($dir_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$dir_name) {
        throw new ExceptionImproved(gettext('Введите корректное имя папки'));
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
        throw new ExceptionImproved(gettext('Данная операция доступна только для зарегистрированных пользователей'));
    }

    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$name) {
        throw new ExceptionImproved(gettext('Введите корректное имя'));
    }

    $id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

    if (!$id) {
        throw new ExceptionImproved(gettext('Необходимо передать ID дериктории!'));
    }

    $user_id = $_user->getId();

    $sth = $pdo->prepare('UPDATE hf_file SET user_defined_name = :name WHERE id = :id AND user_id = :user_id');
    $sth->bindParam(':name', $name);
    $sth->bindParam(':id', $id);
    $sth->bindParam(':user_id', $user_id);
    $sth->execute();
    $sth = null;

    die('{
        "success": 1,
        "id": "'. $id .'",
        "name": "'. $name .'"
    }');
}
elseif ($action == 'delete')
{
    if (!$_user->isLogged()) {
        throw new ExceptionImproved(gettext('Данная операция доступна только для зарегистрированных пользователей'));
    }

    $dir_id = isset($_POST['dir_id']) ? filter_var($_POST['dir_id'], FILTER_SANITIZE_NUMBER_INT) : 0;

    if (!$dir_id) {
        throw new ExceptionImproved('Необходимо передать ID дериктории!');
    }

    if ($_fileManager->isEmptyDir($dir_id)) {
        $_fileManager->deleteDir($dir_id);
    }
    else {
        die('{"error": "Данная директория не может быть удалена, так как имеет файлы или папки."}');
    }

    die('{"success": 1}');
}
elseif ($action == 'move_to_trash') {
    if (!$_user->isLogged()) {
        throw new ExceptionImproved('Данная операция доступна только для зарегистрированных пользователей');
    }

    $id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

    if (!$id) {
        throw new ExceptionImproved('Необходимо передать ID дериктории!');
    }

    $_fileManager->moveToTrash($id);

    die('{"success": 1}');
}
elseif ($action == 'load_files') {
    $cwd = isset($_POST['cwd']) ? intval($_POST['cwd']) : 0;

    $files = ($cwd == -1) ? $_fileManager->getTrashFilesInfoByUserId($_user->getId()) : $_fileManager->getFilesInfoFromDir($cwd, $_user->getId());

    $response = array('success' => 1, 'files' => $files);
    die(json_encode($response));
}
elseif ($action == 'empty_trash') {
    if (! $_user->isLogged()) {
        throw new ExceptionImproved(gettext('Данная операция доступна только для зарегистрированных пользователей'));
    }

    $_fileManager->emptyTrash($_user->getId());

    $response = array('success' => 1);
    $response = json_encode($response);

    die($response);
}