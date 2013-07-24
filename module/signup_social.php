<?php

if (!defined('CHECK')) {
    exit;
}

try
{
    $provider = !empty($_GET['provider']) ? $_GET['provider'] : '';
    require_once DIR_CLASS . 'hybridauth/Hybrid/Auth.php';
    $hybridauth_config = DIR_CLASS . 'hybridauth/config.php';
    $hybridauth = new Hybrid_Auth( $hybridauth_config );

    $adapter = $hybridauth->authenticate( $provider );

    if($adapter->isUserConnected()){
        $user_profile = (array) $adapter->getUserProfile();

        if (empty($user_profile['emailVerified']) || empty($user_profile['firstName'])) {
            $smarty->assign('social_user_profile', $user_profile);
        }
        else {
            $user_found = $_user->getByEmail($user_profile['emailVerified']);

            if(empty($user_found)) {
                $user_found = $_user->signUp($user_profile['firstName'], $user_profile['emailVerified'], 0, '', $user_profile['lastName']);
            }
            else {
                $_user->signIn($user_profile['emailVerified']);
            }
        }
    } else {
        throw new ExceptionImproved('Ошибка авторизации');
    }
}
catch(Exception $e)
{
    // In case we have errors 6 or 7, then we have to use Hybrid_Provider_Adapter::logout() to
    // let hybridauth forget all about the user so we can try to authenticate again.

    // Display the recived error,
    // to know more please refer to Exceptions handling section on the userguide
    $error = '';
    switch($e->getCode()){
    case 0 : $error = _("Неопознанная ошибка"); break;
    case 1 : $error = _("Ошибка конфигурации Hybridauth"); break;
    case 2 : $error = _("Провайдер не правильно настроен"); break;
    case 3 : $error = _("Неизвестный или отключенный провайдер авторизации"); break;
    case 4 : $error = _("Отсутствуют учетные данные приложения в провайдере"); break;
    case 5 : $error = _("Ошибка авторизации.Пользователь отменил аутентификации или провайдер авторизации отказал в соединении"); break;
    case 6 :
        $error = _("Запрос пользователя у провайдера не удался. Скорее всего, пользователь не подключен к провайдеру или нужно попробовать еще раз");
        $adapter->logout();
        break;
    case 7 :
        $error = _("Пользователь не подключен к провайдеру авторизации");
        $adapter->logout();
        break;
    case 8: $error = _('Провайдер авторизации не поддерживает эту функцию'); break;
    }

    if(!empty($error))
        throw new ExceptionImproved($error);
    throw $e;
}

header('Location: /');