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
                if ($config['email_filter']) {
                    preg_match('/(.+)@(.+)/i', $user_profile['emailVerified'], $matches);

                    if (in_array($matches[2], $config['email_filter'])) {
                        throw new Exception('Регистрация через почтовый провайдер '. $matches[2] .' запрещена!', 100);
                    }
                }

                $user_found = $_user->signUp($user_profile['firstName'], $user_profile['emailVerified'], \User\User::get_countryId_by_code2(\User\User::get_geoip_country()), '', $user_profile['lastName']);

                if ($user_profile['photoURL']) {
                    $photo = file_get_contents($user_profile['photoURL']);
                    $tmp_name = uniqid();
                    file_put_contents(DIR_UPLOAD . '/tmp/' . $tmp_name, $photo);
                    require_once DIR_CLASS . 'phpthumb/ThumbLib.inc.php';
                    $phpThumb = PhpThumbFactory::create(DIR_UPLOAD . '/tmp/' . $tmp_name);
                    $phpThumb->adaptiveResize(150, 150)->save(DIR_UPLOAD . 'avatar/avatar-' . $_user->getId() . '.jpg', 'jpg');
                    $phpThumb->adaptiveResize(32, 32)->save(DIR_UPLOAD . 'avatar/_thumb/avatar-' . $_user->getId() . '.jpg', 'jpg');
                    $_user->setAvatar('avatar-' . $_user->getId() . '.jpg');
                    unlink(DIR_UPLOAD . '/tmp/' . $tmp_name);
                }
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