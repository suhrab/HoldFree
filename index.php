<?php
session_start();

define('CHECK', TRUE);

require_once('config.php');
require_once('init.php');

try
{
    if (isset($_COOKIE['hash'])) {
        $_user->signInByHash($_COOKIE['hash']);
    }

    $module     = isset($_REQUEST['module']) ? preg_replace('/[^a-z0-9_]/i', '', $_REQUEST['module']) : 'manager';
    $action     = isset($_REQUEST['action']) ? preg_replace('/[^a-z0-9_]/i', '', $_REQUEST['action']) : '';
    $is_ajax    = isset($_REQUEST['is_ajax']) ? abs($_REQUEST['is_ajax']) : 0;
    $dashboard  = isset($_REQUEST['dashboard']) ? abs($_REQUEST['dashboard']) : 0;

    if ($config['gzip']) {
        ob_start("ob_gzhandler");
    }

    if ($config['site_offline'] && !$dashboard) {
        throw new ExceptionImproved($config['offline_reason'], 'Сайт на реконструкции!');
    }

    // Извлекаем список стран для диалога регистрации
    if (!$_user->isLogged()) {
        $qh = $pdo->query('SELECT id, code, name FROM hf_country');
        $qh->execute();
        $countries = $qh->fetchAll();
        $smarty->assign('countries', $countries);
        $smarty->assign('_user', ['currentCountryId' => $_user->currentCountryId]);
    }
    else {
        $smarty->assign('_user', $_user->get());
    }

    switch ($module) {
        case 'error_403':
            throw new ExceptionImproved('Доступ запрещен!', 'Ошибка 403', 403);

        case 'error_404':
            throw new ExceptionImproved('Такой страницы не существует!', 'Ошибка 404', 404);

        case 'error_503':
            throw new ExceptionImproved('Сервис временно недоступен!', 'Ошибка 503', 503);

        default:
            if ($dashboard === 0 && file_exists(DIR_MODULE . $module . '.php')) {
                include_once DIR_MODULE . $module . '.php';
            }
            elseif ($dashboard === 1 && file_exists(DIR_MODULE . 'dashboard/' . $module . '.php')) {
                include_once DIR_MODULE . 'dashboard/' . $module . '.php';
            }
            else {
                throw new ExceptionImproved('Такой страницы не существует!', 'Ошибка 404', 404);
            }
    }
}
catch (Exception $exception)
{
    $error['title'] = $exception instanceof ExceptionImproved ? $exception->getTitle() : 'Ошибка';
    $error['message'] = $exception->getMessage();
    $error['code'] = $exception->getCode();

    if ($is_ajax) {
        $error['error'] = 1;
        die( json_encode($error) );
    }
    else {
        switch ($error['code']) {
            case 403:
                http_response_code(403);
                break;

            case 404:
                http_response_code(404);
                break;

            case 503:
                http_response_code(503);
                break;
        }

        $smarty->assign('error', $error);
        $smarty->assign('meta_title', $error['title']);
//        die($error['message']);
        $smarty->display('error.tpl');
    }
}