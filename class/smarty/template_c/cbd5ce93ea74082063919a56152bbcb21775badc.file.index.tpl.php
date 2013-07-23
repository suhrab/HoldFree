<?php /* Smarty version Smarty-3.1.13, created on 2013-06-28 15:12:59
         compiled from "/Users/Gee/Sites/Holdfree/template/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:101851246151cd533437cb38-18828644%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cbd5ce93ea74082063919a56152bbcb21775badc' => 
    array (
      0 => '/Users/Gee/Sites/Holdfree/template/index.tpl',
      1 => 1372410775,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '101851246151cd533437cb38-18828644',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51cd533446bf16_94447945',
  'variables' => 
  array (
    '_url' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51cd533446bf16_94447945')) {function content_51cd533446bf16_94447945($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>Hold Free</title>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
    <link href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/template/js/jquery-ui/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/template/css/main.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/template/js/jquery-2.0.2.min.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/template/js/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(function(){
            var windowHeight = $(window).height();
            var headerHeight = $('.header').innerHeight();
            var contentHeight = $('.content').innerHeight();
            var footerHeight = $('.footer').innerHeight();

            $('.content').height(windowHeight - headerHeight - footerHeight);

            var regDialog = $('.dialog-registration').dialog({
                autoOpen    : false,
                width       : 480,
                resizable   : false,
                modal       : true,
                dialogClass : 'dialog-registration',
                title       : 'Регистрация'
            });

            $('#registration').click(function() {
                regDialog.dialog('open');
            });
        });
    </script>
</head>
<body>
    <div class="page">
        <div class="header">
            <ul class="menu">
                <li><i class="data"></i> <a href="#">Авторизация</a></li>
                <li><i class="account"></i> <a href="javascript:;" id="registration">Регистрация</a></li>
                <li><i class="mail"></i> <a href="#">Сообщения <strong>(5)</strong></a></li>
            </ul>
            <ul class="language_bar">
                <li><a href="#" class="fr">FR</a></li>
                <li><a href="#" class="ch">CH</a></li>
                <li><a href="#" class="in">IN</a></li>
                <li><a href="#" class="en">EN</a></li>
                <li><a href="#" class="ru active">RU</a></li>
                <li><a href="#" class="es">ES</a></li>
            </ul>
        </div>
        <div class="content">&nbsp;</div>
        <div class="footer">
            <p>&copy; 2013 holdfree.com</p>
            <div>
                <ul class="social_bar">
                    <li><a href="#" class="google"></a></li>
                    <li><a href="#" class="facebook"></a></li>
                    <li><a href="#" class="vkontakte"></a></li>
                </ul>
                <ul class="menu">
                    <li><i class="phone"></i> <a href="#">Обратная связь</a></li>
                    <li><i class="info"></i> <a href="#">Информация</a></li>
                    <li><i class="doc"></i> <a href="#">Соглашение</a></li>
                    <li><i class="comment"></i> <a href="#">FAQ</a></li>
                    <li><i class="tool"></i> <a href="#">Нашли ошибку?</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="dialog dialog-registration">
        <form action="#" method="post">
            <input type="text" class="input-text" placeholder="Введите Ваш email"></input>
            <input type="password" class="input-text" placeholder="Пароль"></input>
            <input type="password" class="input-text" placeholder="Страна"></input>

            <div class="captcha-wrapper">
                <input type="text" class="input-text input-text-small" placeholder="Введите код"></input>
                <img src="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/template/img/captch.png" width="159" title="Captha" class="captcha" />
            </div>

            <div class="line"></div>
            <input type="submit" value="Зарегистрироваться" class="submit"></input>
            <div class="line"></div>

            <ul class="social_bar">
                <li><a href="#" class="google"></a></li>
                <li><a href="#" class="facebook"></a></li>
                <li><a href="#" class="vkontakte"></a></li>
            </ul>
        </form>
    </div>
</body>
</html><?php }} ?>