<?php
require_once(DIR_CLASS . 'ExceptionImproved.class.php');

// PDO
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT . ';charset=utf8', DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


// Инициализируем пользовательские настойки
$dbh = $pdo->query('SELECT `key`, `value` FROM hf_config');

while ( $row = $dbh->fetch() )
{
    $config[$row['key']] = $row['value'];
}


// Smarty
include_once DIR_CLASS . 'smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = DIR_ROOT . 'template/' . $config['template'];
$smarty->compile_dir = DIR_CLASS . 'smarty/template_c';

$smarty->assign('_url', URL);
$smarty->assign('_template', URL . '/template/' . $config['template']);
$smarty->assign('_dashboard', URL . '/template/' . $config['template'] . '/_dashboard');
$smarty->assign('_contact_email', $config['contact_email']);
$smarty->assign('meta_title', $config['homepage_title']);
$smarty->assign('meta_description', $config['homepage_description']);
$smarty->assign('meta_keywords', $config['homepage_keywords']);
$smarty->assign('meta_charset', $config['charset']);


// User
require_once(DIR_CLASS . 'User.class.php');
$_user = new User\User($pdo);
