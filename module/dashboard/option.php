<?php

if (!defined('CHECK')) {
    exit;
}


if ($action == 'update')
{
    $data['homepage_title'] = htmlentities($_POST['homepage_title']);
    $data['charset'] = htmlentities($_POST['charset']);
    $data['homepage_description'] = htmlentities($_POST['homepage_description']);
    $data['homepage_keywords'] = htmlentities($_POST['homepage_keywords']);
    $data['template'] = htmlentities($_POST['template']);
    $data['site_offline'] = isset($_POST['site_offline']) ? 1 : 0;
    $data['gzip'] = isset($_POST['gzip']) ? 1 : 0;
    $data['offline_reason'] = htmlentities($_POST['offline_reason']);
    $data['max_allowed_file_size_guest'] = intval($_POST['max_allowed_file_size_guest']);
    $data['max_allowed_file_size_user'] = intval($_POST['max_allowed_file_size_user']);
    $data['file_keep_guest'] = intval($_POST['file_keep_guest']);
    $data['file_keep_user'] = intval($_POST['file_keep_user']);

    foreach ($data as $k => $v) {
        $sth = $pdo->prepare('UPDATE hf_config SET `value` = :value WHERE `key` = :key LIMIT 1');
        $sth->bindParam(':key', $k);
        $sth->bindParam(':value', $v);
        $sth->execute();
        $sth = null;
    }
}


$sth = $pdo->prepare('SELECT `key`, `value` FROM hf_config');
$sth->execute();
$config = array();
while ($row = $sth->fetch()) {
    $config[$row['key']] = $row['value'];
}


$smarty->assign('config', $config);
$smarty->display('_dashboard/option.tpl');