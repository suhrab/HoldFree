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

        $userFound = User\User::GetByProvider($provider, $user_profile);
        if(empty($userFound)){
            if ($config['email_filter']) {
                preg_match('/(.+)@(.+)/i', $user_profile['emailVerified'], $matches);

                if (in_array($matches[2], $config['email_filter'])) {
                    throw new Exception($matches[2] . ': ' . gettext('Регистрация через этот почтовый провайдер запрещена!'), 100);
                }
            }
        }
        $userFound = User\User::SignUpOrGetByProvider($provider, $user_profile);
        $r = $_user->SignInByProvider($provider, $user_profile);
    } else {
        throw new ExceptionImproved(gettext('Ошибка авторизации'));
    }
}
catch(Exception $e)
{
    $hybrid_auth_error = '';
    switch($e->getCode()){
    case 0 : $hybrid_auth_error = _("Неопознанная ошибка"); break;
    case 1 : $hybrid_auth_error = _("Ошибка конфигурации Hybridauth"); break;
    case 2 : $hybrid_auth_error = _("Провайдер не правильно настроен"); break;
    case 3 : $hybrid_auth_error = _("Неизвестный или отключенный провайдер авторизации"); break;
    case 4 : $hybrid_auth_error = _("Отсутствуют учетные данные приложения в провайдере"); break;
    case 5 : $hybrid_auth_error = _("Ошибка авторизации.Пользователь отменил аутентификации или провайдер авторизации отказал в соединении"); break;
    case 6 :
        $hybrid_auth_error = _("Запрос пользователя у провайдера не удался. Скорее всего, пользователь не подключен к провайдеру или нужно попробовать еще раз");
        $adapter->logout();
        break;
    case 7 :
        $hybrid_auth_error = _("Пользователь не подключен к провайдеру авторизации");
        $adapter->logout();
        break;
    case 8: $hybrid_auth_error = _('Провайдер авторизации не поддерживает эту функцию'); break;
    case 100: $hybrid_auth_error = _($e->getMessage()); break;
    }

    if(!empty($hybrid_auth_error))
        throw new Exception($hybrid_auth_error);
    throw $e;
}

header('Location: /');