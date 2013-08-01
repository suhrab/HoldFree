<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>{$meta_title}</title>
    <meta name="description" content="{$meta_description}" />
    <meta name="keywords" content="{$meta_keywords}" />
    <meta charset="{$meta_charset}">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css' />
    <link href="{$_template}/js/jquery-ui/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css" />
    <link href="{$_template}/js/select2/select2.css" rel="stylesheet" type="text/css" />
    <link href="{$_template}/css/default.css" rel="stylesheet" type="text/css" />
    <script src="{$_template}/js/jquery-2.0.2.min.js" type="text/javascript"></script>
    <script src="{$_template}/js/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
    <script src="{$_template}/js/select2/select2.min.js" type="text/javascript"></script>
    <script src="{$_template}/js/default.js" type="text/javascript"></script>
    <script type="text/javascript">
        var socialUserProfile = {if isset($social_user_profile)}{$social_user_profile|json_encode}{else}false{/if};
        $(function() {
            if (socialUserProfile) {
                $("#formSignUp input[name=first_name]").val(socialUserProfile.firstName);
                $("#formSignUp input[name=email]").val(socialUserProfile.emailVerified);
                $("#formSignUp input[name=country]").val(socialUserProfile.country);
                $("#signup").trigger('click');
            }
        });
    </script>
</head>
<body>
    <div class="page">
        <div class="header">
            <a href="{$_url}" class="logo"></a>
            {if isset($_user.id)}
                <ul class="user_bar">
                    <li class="avatar" style="background: url('{$_url}/upload/avatar/_thumb/{$_user.avatar}') no-repeat;"></li>
                    <li>{$_user.first_name}</li>
                </ul>
            {/if}
            <ul class="menu">
                {if isset($_user.id)}
                    <li><i class="data"></i> <a href="{$_url}/?module=user&action=profile&id={$_user.id}">{"Личные данные"|gettext}</a></li>
                    {if $_user.group == 1}<li><i class="doc"></i> <a href="{$_url}/?module=dashboard&dashboard=1">{"Панель Управления"|gettext}</a></li>{/if}
                    <li><i class="doc"></i> <a href="{$_url}/?module=user&action=signout">{"Выход"|gettext}</a></li>
                    <!--li><i class="mail"></i> <a href="javascript:;" id="signin">Сообщения <strong>(5)</strong></a></li-->
                {else}
                    <li><i class="data"></i> <a href="javascript:;" id="signin">{"Авторизация"|gettext}</a></li>
                    <li><i class="account"></i> <a href="javascript:;" id="signup">{"Регистрация"|gettext}</a></li>
                {/if}
            </ul>
            <ul class="language_bar">
                <li><a href="/?module=language_change&l=fr" class="fr {if $language == 'fr'}active{/if}">FR</a></li>
                <li><a href="/?module=language_change&l=zh" class="ch {if $language == 'zh'}active{/if}">CH</a></li>
                <li><a href="/?module=language_change&l=hi" class="in {if $language == 'hi'}active{/if}">IN</a></li>
                <li><a href="/?module=language_change&l=en" class="en {if $language == 'en'}active{/if}">EN</a></li>
                <li><a href="/?module=language_change&l=ru" class="ru {if $language == 'ru'}active{/if}">RU</a></li>
                <li><a href="/?module=language_change&l=es" class="es {if $language == 'es'}active{/if}">ES</a></li>
            </ul>
        </div>

        {block name="content"}
            <div class="content">&nbsp;</div>
        {/block}
    </div>

    <div class="footer">
        <p>&copy; 2013 {$_url}</p>
        <div>
            <ul class="social_bar">
                <li><a href="#" class="google"></a></li>
                <li><a href="#" class="facebook"></a></li>
                <li><a href="#" class="vkontakte"></a></li>
            </ul>
            <ul class="menu">
                <li><i class="phone"></i> <a href="javascript:;" id="feedback">{"Обратная связь"|gettext}</a></li>
                <li><i class="info"></i> <a href="#">{"Информация"|gettext}</a></li>
                <li><i class="doc"></i> <a href="#">{"Соглашение"|gettext}</a></li>
                <li><i class="comment"></i> <a href="{$_url}/index.php?module=faq">{"FAQ"|gettext}</a></li>
                <li><i class="tool"></i> <a href="javascript:;" id="error">{"Нашли ошибку?"|gettext}</a></li>
            </ul>
        </div>
    </div>

    <div class="dialog dialog-signup" title="{"Регистрация"|gettext}">
        <div class="place-holder-message"></div>
        <form action="index.php?module=user&action=signup" method="post" id="formSignUp">
            <input type="text" name="first_name" class="input-text" placeholder="{"Введите Ваше имя"|gettext}" />
            <input type="text" name="email" class="input-text" placeholder="{"Введите Ваш email"|gettext}" />
            <input type="password" name="password" class="input-text" placeholder="{"Пароль"|gettext}" />

            <select name="country" class="select2">
            <option value="0">{"Выберите страну"|gettext}</option>
            {foreach from=$countries item=country}
                <option value="{$country.id}">{$country.name}</option>
            {/foreach}
            </select>

            <div class="captcha-wrapper">
                <input name="captcha" type="text" class="input-text input-text-small" placeholder="{"Введите код"|gettext}" />
                <img src="{$_url}/class/kcaptcha/" width="160" height="46" title="Captha" class="captcha" />
            </div>

            <div class="line"></div>
            <input type="submit" value="{"Зарегистрироваться"|gettext}" class="submit" />
            <div class="line"></div>

            <ul class="social_bar">
                <li><a href="/index.php?module=signup_social&provider=Google" class="google"></a></li>
                <li><a href="/index.php?module=signup_social&provider=Facebook" class="facebook"></a></li>
                <li><a href="/index.php?module=signup_social&provider=Vkontakte" class="vkontakte"></a></li>
            </ul>
        </form>
    </div>

    <div class="dialog dialog-signin" title="{"Авторизация"|gettext}">
        <div class="place-holder-message"></div>
        <form action="index.php?module=user&action=signin" method="post" id="formSignIn">
            <input type="text" name="email" class="input-text" placeholder="{"Введите Ваш email"|gettext}" />
            <input type="password" name="password" class="input-text" placeholder="{"Пароль"|gettext}" />

            <div class="line"></div>
            <input type="submit" value="{"Войти"|gettext}" class="submit" />
            <div class="line"></div>

            <ul class="social_bar">
                <li><a href="/index.php?module=signup_social&provider=Google" class="google"></a></li>
                <li><a href="/index.php?module=signup_social&provider=Facebook" class="facebook"></a></li>
                <li><a href="/index.php?module=signup_social&provider=Vkontakte" class="vkontakte"></a></li>
            </ul>
        </form>
    </div>

    <div class="dialog dialog-feedback" title="{"Обратная связь"|gettext}">
        <div class="place-holder-message"></div>
        <form action="index.php?module=feedback&action=send" method="post" id="formFeedback">
            <input name="first_name" type="text" class="input-text" placeholder="{"Введите Ваше имя"|gettext}" />
            <input name="email" type="text" class="input-text" placeholder="{"Введите Ваш email"|gettext}" />

            <select name="topic" class="select2">
                <option value="1">{"Техническая поддержка"|gettext}</option>
                <option value="2">{"Правообладатель"|gettext}</option>
                <option value="3">{"Пресса"|gettext}</option>
            </select>

            <textarea name="message" class="input-textarea" placeholder="{"Ваше сообщение"|gettext}"></textarea>

            <div class="captcha-wrapper">
                <input name="captcha" type="text" class="input-text input-text-small" placeholder="{"Введите код"|gettext}" />
                <img src="{$_url}/class/kcaptcha/" width="160" height="46" title="Captha" class="captcha" />
            </div>

            <div class="line"></div>
            <input type="submit" value="{"Отправить"|gettext}" class="submit" />
        </form>
    </div>

    <div class="dialog dialog-error" title="{"Заметили ошибку?"|gettext}">
        <div class="place-holder-message"></div>
        <form action="index.php?module=feedback&action=send" method="post" id="formError">
            <input name="email" type="text" class="input-text" placeholder="{"Введите Ваш email"|gettext}" />
            <textarea  name="message" class="input-textarea" placeholder="{"Сообщение об ошибке"|gettext}"></textarea>

            <div class="captcha-wrapper">
                <input name="captcha" type="text" class="input-text input-text-small" placeholder="{"Введите код"|gettext}" />
                <img src="{$_url}/class/kcaptcha/" width="160" height="46" title="Captha" class="captcha" />
            </div>

            <div class="line"></div>
            <input type="submit" value="{"Отправить"|gettext}" class="submit" />
            <input type="hidden" name="topic" value="4" />
        </form>
    </div>
</body>
</html>