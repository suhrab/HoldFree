<?php /* Smarty version Smarty-3.1.13, created on 2013-06-30 05:41:48
         compiled from "/Users/Gee/Sites/Holdfree/template/default/messages.tpl" */ ?>
<?php /*%%SmartyHeaderCode:145138482151cef2f27cd7c1-09234499%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4e153615977423ba18b5d1ffc8dd08c22f001c23' => 
    array (
      0 => '/Users/Gee/Sites/Holdfree/template/default/messages.tpl',
      1 => 1372549305,
      2 => 'file',
    ),
    '15e34177ab80cd4e47f5fbce4df1e9a4df3ae8dc' => 
    array (
      0 => '/Users/Gee/Sites/Holdfree/template/default/index.tpl',
      1 => 1372508982,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '145138482151cef2f27cd7c1-09234499',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51cef2f291ed89_09913584',
  'variables' => 
  array (
    '_template' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51cef2f291ed89_09913584')) {function content_51cef2f291ed89_09913584($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>Hold Free</title>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
    <link href="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/jquery-ui/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/css/main.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/jquery-2.0.2.min.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/main.js" type="text/javascript"></script>

    <!-- CSS -->
    

    <!-- JS -->
    
        <script type="text/javascript">
            $(function(){

            });
        </script>
    

</head>
<body>
    <div class="page">
        <div class="header">
            <ul class="menu">
                <li><i class="data"></i> <a href="javascript:;" id="signin">Авторизация</a></li>
                <li><i class="account"></i> <a href="javascript:;" id="signup">Регистрация</a></li>
                <!--li><i class="mail"></i> <a href="javascript:;" id="signin">Сообщения <strong>(5)</strong></a></li-->
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

        
    <div class="content page-messages">
        <h2>Сообщения</h2>

        <a href="#" class="button">Написать сообщение</a>
        <a href="#" class="button">Удалить</a>

        <table width="100%" border="0" cellspacing="0" cellpadding="4">
            <thead>
                <tr>
                    <td width="230">Отправитель</td>
                    <td>Сообщение</td>
                    <td width="180">Дата</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Администрация</td>
                    <td class="strong"><a href="">Ваш файл был удален</a></td>
                    <td>23 Января 2013, 8:44</td>
                </tr>
                <tr>
                    <td>Администрация</td>
                    <td class="strong"><a href="">Ваш файл был удален</a></td>
                    <td>23 Января 2013, 8:44</td>
                </tr>
                <tr>
                    <td>Администрация</td>
                    <td class="strong"><a href="">Ваш файл был удален</a></td>
                    <td>23 Января 2013, 8:44</td>
                </tr>
                <tr>
                    <td>Администрация</td>
                    <td class="strong"><a href="">Ваш файл был удален</a></td>
                    <td>23 Января 2013, 8:44</td>
                </tr>
            </tbody>
        </table>
    </div>


        <div class="footer">
            <p>&copy; 2013 holdfree.com</p>
            <div>
                <ul class="social_bar">
                    <li><a href="#" class="google"></a></li>
                    <li><a href="#" class="facebook"></a></li>
                    <li><a href="#" class="vkontakte"></a></li>
                </ul>
                <ul class="menu">
                    <li><i class="phone"></i> <a href="javascript:;" id="feedback">Обратная связь</a></li>
                    <li><i class="info"></i> <a href="#">Информация</a></li>
                    <li><i class="doc"></i> <a href="#">Соглашение</a></li>
                    <li><i class="comment"></i> <a href="#">FAQ</a></li>
                    <li><i class="tool"></i> <a href="javascript:;" id="error">Нашли ошибку?</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="dialog dialog-signup">
        <form action="#" method="post">
            <input type="text" class="input-text" placeholder="Введите Ваш email"></input>
            <input type="password" class="input-text" placeholder="Пароль"></input>
            <input type="password" class="input-text" placeholder="Страна"></input>

            <div class="captcha-wrapper">
                <input type="text" class="input-text input-text-small" placeholder="Введите код"></input>
                <img src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/img/captch.png" width="159" title="Captha" class="captcha" />
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

    <div class="dialog dialog-signin">
        <form action="#" method="post">
            <input type="text" class="input-text" placeholder="Введите Ваш email"></input>
            <input type="password" class="input-text" placeholder="Пароль"></input>

            <div class="line"></div>
            <input type="submit" value="Войти" class="submit"></input>
            <div class="line"></div>

            <ul class="social_bar">
                <li><a href="#" class="google"></a></li>
                <li><a href="#" class="facebook"></a></li>
                <li><a href="#" class="vkontakte"></a></li>
            </ul>
        </form>
    </div>

    <div class="dialog dialog-feedback">
        <form action="#" method="post">
            <input type="text" class="input-text" placeholder="Введите Ваше имя"></input>
            <input type="text" class="input-text" placeholder="Введите Ваш email"></input>
            <textarea  name="message" class="input-textarea" placeholder="Ваше сообщение"></textarea>

            <div class="captcha-wrapper">
                <input type="text" class="input-text input-text-small" placeholder="Введите код"></input>
                <img src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/img/captch.png" width="159" title="Captha" class="captcha" />
            </div>

            <div class="line"></div>
            <input type="submit" value="Отправить" class="submit"></input>
        </form>
    </div>

    <div class="dialog dialog-error">
        <form action="#" method="post">
            <input type="text" class="input-text" placeholder="Введите Ваш email"></input>
            <textarea  name="message" class="input-textarea" placeholder="Сообщение об ошибке"></textarea>

            <div class="captcha-wrapper">
                <input type="text" class="input-text input-text-small" placeholder="Введите код"></input>
                <img src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/img/captch.png" width="159" title="Captha" class="captcha" />
            </div>

            <div class="line"></div>
            <input type="submit" value="Отправить" class="submit"></input>
        </form>
    </div>
</body>
</html><?php }} ?>