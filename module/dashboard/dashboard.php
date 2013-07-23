<?php

if (!defined('CHECK')) {
    exit;
}

require_once DIR_CLASS . 'google-api-php-client/Google_Client.php';
require_once DIR_CLASS . 'google-api-php-client/contrib/Google_AnalyticsService.php';

$client = new Google_Client();
$client->setApplicationName('Hello Analytics API Sample');

$client->setClientId('921866991525-b1e4fk7u7ho0cn502ola13t17m332qoh.apps.googleusercontent.com');
$client->setClientSecret('K99K8to5ylY1oYncaLkNTB86');
$client->setRedirectUri('http://holdfree.com/index.php?module=dashboard&dashboard=1');
$client->setDeveloperKey('AIzaSyBv0_WUSJBB_edBmt0RCX2AB4hp6st3KXY');
$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));

$client->setUseObjects(true);

if (isset($_GET['code']))
{
    $client->authenticate();
    $_SESSION['token'] = $client->getAccessToken();
    header('Location: index.php?module=dashboard&dashboard=1');
}


$sth = $pdo->prepare('SELECT u.first_name, u.last_name, u.id, l.time, l.os, l.browser, l.engine FROM hf_user_log l LEFT JOIN hf_user u ON l.uid = u.id ORDER BY l.time LIMIT 15');
$sth->execute();
$user_log = $sth->fetchAll();
$sth = null;
foreach ($user_log as &$row) {
    $row['time'] = date('d.m.Y, h:i', $row['time']);
}

$smarty->assign('user_log', $user_log);


$smarty->display('_dashboard/index.tpl');