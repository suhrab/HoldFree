<?php /* Smarty version Smarty-3.1.13, created on 2013-07-20 14:34:01
         compiled from "/Users/Gee/Sites/Holdfree/template/default/error.tpl" */ ?>
<?php /*%%SmartyHeaderCode:176027451cf772c2f0c72-41470274%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2655fb43ccd24a7257573c50916fe02cff73d695' => 
    array (
      0 => '/Users/Gee/Sites/Holdfree/template/default/error.tpl',
      1 => 1372774520,
      2 => 'file',
    ),
    '15e34177ab80cd4e47f5fbce4df1e9a4df3ae8dc' => 
    array (
      0 => '/Users/Gee/Sites/Holdfree/template/default/index.tpl',
      1 => 1374309200,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '176027451cf772c2f0c72-41470274',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51cf772c38ebe3_54156989',
  'variables' => 
  array (
    'meta_title' => 0,
    'meta_description' => 0,
    'meta_keywords' => 0,
    'meta_charset' => 0,
    '_template' => 0,
    '_url' => 0,
    '_user' => 0,
    'countries' => 0,
    'country' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51cf772c38ebe3_54156989')) {function content_51cf772c38ebe3_54156989($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title><?php echo $_smarty_tpl->tpl_vars['meta_title']->value;?>
</title>
    <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['meta_description']->value;?>
" />
    <meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['meta_keywords']->value;?>
" />
    <meta charset="<?php echo $_smarty_tpl->tpl_vars['meta_charset']->value;?>
">
    <!--link href='http://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'-->
    <link href="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/jquery-ui/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/select2/select2.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/css/default.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/jquery-2.0.2.min.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/select2/select2.min.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/default.js" type="text/javascript"></script>
</head>
<body>
    <div class="page">
        <div class="header">
            <a href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
" class="logo"></a>
            <?php if (isset($_smarty_tpl->tpl_vars['_user']->value['id'])){?>
                <ul class="user_bar">
                    <li class="avatar" style="background: url('<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/upload/avatar/_thumb/<?php echo $_smarty_tpl->tpl_vars['_user']->value['avatar'];?>
') no-repeat;"></li>
                    <li><?php echo $_smarty_tpl->tpl_vars['_user']->value['first_name'];?>
</li>
                </ul>
            <?php }?>
            <ul class="menu">
                <?php if (isset($_smarty_tpl->tpl_vars['_user']->value['id'])){?>
                    <li><i class="data"></i> <a href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/?module=user&action=profile&id=<?php echo $_smarty_tpl->tpl_vars['_user']->value['id'];?>
">Личные данные</a></li>
                    <?php if ($_smarty_tpl->tpl_vars['_user']->value['group']==1){?><li><i class="doc"></i> <a href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/?module=dashboard&dashboard=1">Панель Управления</a></li><?php }?>
                    <li><i class="doc"></i> <a href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/?module=user&action=signout">Выход</a></li>
                    <!--li><i class="mail"></i> <a href="javascript:;" id="signin">Сообщения <strong>(5)</strong></a></li-->
                <?php }else{ ?>
                    <li><i class="data"></i> <a href="javascript:;" id="signin">Авторизация</a></li>
                    <li><i class="account"></i> <a href="javascript:;" id="signup">Регистрация</a></li>
                <?php }?>
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

        
    <div class="content page-error">
        <h4><?php echo $_smarty_tpl->tpl_vars['error']->value['title'];?>
</h4>
        <h5><?php echo $_smarty_tpl->tpl_vars['error']->value['message'];?>
</h5>
        <div class="description">
            <p>Вернитесь на <a href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
">главную страницу</a>. Если эта ошибка будет повторятся, сообщите нам адрес с которого вы сюда попали, на <a href="mailto:<?php echo $_smarty_tpl->tpl_vars['_contact_email']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_contact_email']->value;?>
</a></p>
        </div>
    </div>

    </div>

    <div class="footer">
        <p>&copy; 2013 <?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
</p>
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
                <li><i class="comment"></i> <a href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/index.php?module=faq">FAQ</a></li>
                <li><i class="tool"></i> <a href="javascript:;" id="error">Нашли ошибку?</a></li>
            </ul>
        </div>
    </div>

    <div class="dialog dialog-signup" title="Регистрация">
        <div class="place-holder-message"></div>
        <form action="index.php?module=user&action=signup" method="post" id="formSignUp">
            <input type="text" name="first_name" class="input-text" placeholder="Введите Ваше имя" />
            <input type="text" name="email" class="input-text" placeholder="Введите Ваш email" />
            <input type="password" name="password" class="input-text" placeholder="Пароль" />

            <select name="country" class="select2">
            <option value="0">Выберите страну</option>
            <?php  $_smarty_tpl->tpl_vars['country'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['country']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['countries']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['country']->key => $_smarty_tpl->tpl_vars['country']->value){
$_smarty_tpl->tpl_vars['country']->_loop = true;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['country']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['country']->value['name'];?>
</option>
            <?php } ?>
            </select>

            <div class="captcha-wrapper">
                <input name="captcha" type="text" class="input-text input-text-small" placeholder="Введите код" />
                <img src="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/class/kcaptcha/" width="160" height="46" title="Captha" class="captcha" />
            </div>

            <div class="line"></div>
            <input type="submit" value="Зарегистрироваться" class="submit" />
            <div class="line"></div>

            <ul class="social_bar">
                <li><a href="#" class="google"></a></li>
                <li><a href="#" class="facebook"></a></li>
                <li><a href="#" class="vkontakte"></a></li>
            </ul>
        </form>
    </div>

    <div class="dialog dialog-signin" title="Авторизация">
        <div class="place-holder-message"></div>
        <form action="index.php?module=user&action=signin" method="post" id="formSignIn">
            <input type="text" name="email" class="input-text" placeholder="Введите Ваш email" />
            <input type="password" name="password" class="input-text" placeholder="Пароль" />

            <div class="line"></div>
            <input type="submit" value="Войти" class="submit" />
            <div class="line"></div>

            <ul class="social_bar">
                <li><a href="#" class="google"></a></li>
                <li><a href="#" class="facebook"></a></li>
                <li><a href="#" class="vkontakte"></a></li>
            </ul>
        </form>
    </div>

    <div class="dialog dialog-feedback" title="Обратная связь">
        <div class="place-holder-message"></div>
        <form action="index.php?module=feedback&action=send" method="post" id="formFeedback">
            <input name="first_name" type="text" class="input-text" placeholder="Введите Ваше имя" />
            <input name="email" type="text" class="input-text" placeholder="Введите Ваш email" />

            <select name="topic" class="select2">
                <option value="1">Техническая поддержка</option>
                <option value="2">Правообладатель</option>
                <option value="3">Пресса</option>
            </select>

            <textarea name="message" class="input-textarea" placeholder="Ваше сообщение"></textarea>

            <div class="captcha-wrapper">
                <input name="captcha" type="text" class="input-text input-text-small" placeholder="Введите код" />
                <img src="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/class/kcaptcha/" width="160" height="46" title="Captha" class="captcha" />
            </div>

            <div class="line"></div>
            <input type="submit" value="Отправить" class="submit" />
        </form>
    </div>

    <div class="dialog dialog-error" title="Заметили ошибку?">
        <div class="place-holder-message"></div>
        <form action="index.php?module=feedback&action=send" method="post" id="formError">
            <input name="email" type="text" class="input-text" placeholder="Введите Ваш email" />
            <textarea  name="message" class="input-textarea" placeholder="Сообщение об ошибке"></textarea>

            <div class="captcha-wrapper">
                <input name="captcha" type="text" class="input-text input-text-small" placeholder="Введите код" />
                <img src="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/class/kcaptcha/" width="160" height="46" title="Captha" class="captcha" />
            </div>

            <div class="line"></div>
            <input type="submit" value="Отправить" class="submit" />
            <input type="hidden" name="topic" value="4" />
        </form>
    </div>
</body>
</html><?php }} ?>