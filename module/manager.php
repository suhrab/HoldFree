<?php
if (!defined('CHECK')) {
    exit;
}

if ($action == 'upload')
{
    if (is_uploaded_file($_FILES['file']['tmp_name']))
    {
        $file_name = uniqid();

        move_uploaded_file($_FILES['file']['tmp_name'], DIR_UPLOAD . 'tmp/' . $file_name);

        $original_name = $_FILES['file']['name'];
        $original_name = filter_var($original_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $original_name = htmlentities($original_name);

        $added = time();

        $size = filesize(DIR_UPLOAD . 'tmp/' . $file_name);
        $server = 's1';
        $dir = date('Y-m');
        $owner = $_user->getId();

        $pdo->beginTransaction();

        $sth = $pdo->prepare('INSERT INTO hf_file SET name =:name, original_name = :original_name, added =:added, size = :size, server = :server, dir =:dir, owner = :owner');
        $sth->bindParam(':name', $file_name);
        $sth->bindParam(':original_name', $original_name);
        $sth->bindParam(':added', $added);
        $sth->bindParam(':size', $size);
        $sth->bindParam(':server', $server);
        $sth->bindParam(':dir', $dir);
        $sth->bindParam(':owner', $owner);
        $sth->execute();
        $sth = null;

        if (!file_exists(DIR_UPLOAD . 'tmp/' . $file_name)) {
            $pdo->rollBack();
        }

        $pdo->commit();
    }
}
else
{
    $sth = $pdo->prepare('SELECT id, name, files, created FROM hf_dir  WHERE owner = :owner ORDER BY id DESC');

    $owner = $_user->getId();

    $sth->bindParam(':owner', $owner);
    $sth->execute();

    $dir_list = array();

    while ($row = $sth->fetch()) {
        $row['created'] = date('d.m.Y', $row['created']);
        $dir_list[] = $row;
    }

    $smarty->assign('dir_list', $dir_list);
}

$smarty->display('manager.tpl');
