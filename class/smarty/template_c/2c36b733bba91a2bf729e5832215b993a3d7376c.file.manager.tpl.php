<?php /* Smarty version Smarty-3.1.13, created on 2013-07-24 10:53:29
         compiled from "/Users/Gee/Sites/Holdfree/template/default/manager.tpl" */ ?>
<?php /*%%SmartyHeaderCode:194625905451d03729a9cbc1-81917462%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2c36b733bba91a2bf729e5832215b993a3d7376c' => 
    array (
      0 => '/Users/Gee/Sites/Holdfree/template/default/manager.tpl',
      1 => 1374590667,
      2 => 'file',
    ),
    '15e34177ab80cd4e47f5fbce4df1e9a4df3ae8dc' => 
    array (
      0 => '/Users/Gee/Sites/Holdfree/template/default/index.tpl',
      1 => 1374641347,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '194625905451d03729a9cbc1-81917462',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51d03729b410b1_48410288',
  'variables' => 
  array (
    'meta_title' => 0,
    'meta_description' => 0,
    'meta_keywords' => 0,
    'meta_charset' => 0,
    '_template' => 0,
    'social_user_profile' => 0,
    '_url' => 0,
    '_user' => 0,
    'countries' => 0,
    'country' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51d03729b410b1_48410288')) {function content_51d03729b410b1_48410288($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
    <script type="text/javascript">
        var socialUserProfile = <?php if (isset($_smarty_tpl->tpl_vars['social_user_profile']->value)){?><?php echo json_encode($_smarty_tpl->tpl_vars['social_user_profile']->value);?>
<?php }else{ ?>false<?php }?>;
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

        
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/noty/jquery.noty.js"></script>
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/noty/layouts/topRight.js"></script>
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/noty/themes/default.js"></script>

    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/context-menu/jquery.contextMenu.js"></script>
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/context-menu/jquery.ui.position.js"></script>
    <link type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/js/context-menu/jquery.contextMenu.css" rel="stylesheet" />

    <script type="text/javascript">
        $(function()
        {
            function getBytesWithUnit (bytes)
            {
                if( isNaN( bytes ) ){ return; }
                var units = [ ' bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB' ];
                var amountOf2s = Math.floor( Math.log( +bytes )/Math.log(2) );
                if( amountOf2s < 1 ){
                    amountOf2s = 0;
                }
                var i = Math.floor( amountOf2s / 10 );
                bytes = +bytes / Math.pow( 2, 10*i );

                if( bytes.toString().length > bytes.toFixed(1).toString().length ){
                    bytes = bytes.toFixed(1);
                }
                return bytes + units[i];
            };
        });
    </script>

    <div class="content page-manager">
        <style type="text/css">
            .swfupload {
                display: inline-block;
                padding: 5px 20px 7px 20px;
                font: bold 14px Roboto, arial, helvetica, sans-serif;
                text-decoration: none;
                background-color: #293640;
                border-radius: 2px;
            }
        </style>

        <h2>Менеджер видео</h2>

        <a href="javascript:;" class="button">Загрузить файл</a>
        <a href="javascript:;" class="button" id="newDir">Создать папку</a>

        <div class="manager">
            <div class="dir-tree">
                <ul>
                    <li class="dir-root">
                        <a href="#" class="parent">Менеджер файлов</a>
                        <ul>
                            <li class="dir"><a href="#">Dexter</a></li>
                            <li class="dir"><a href="#">House M.D.</a></li>
                            <li class="dir"><a href="#">How to make it in America</a></li>
                        </ul>
                    </li>

                    <li class="dir-trash">
                        <a href="#" class="parent">Удаленные файлы</a>
                    </li>
                </ul>
            </div>

            <div class="file-panel">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td>Имя</td>
                            <td width="140">Размер</td>
                            <td width="140">Дата</td>
                            <td width="50">URL</td>
                        </tr>
                    </thead>
                    <tbody id="fileList">
                        <?php  $_smarty_tpl->tpl_vars['dir'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['dir']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dir_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['dir']->key => $_smarty_tpl->tpl_vars['dir']->value){
$_smarty_tpl->tpl_vars['dir']->_loop = true;
?>
                            <tr id="row_dir_<?php echo $_smarty_tpl->tpl_vars['dir']->value['id'];?>
">
                                <td colspan="3"><a href="javascript:;" class="dir" id="dir_<?php echo $_smarty_tpl->tpl_vars['dir']->value['id'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['dir']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['dir']->value['name'];?>
</a></td>
                                <td><a href="#"><img src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/img/icon_24_chain.png" width="24" height="24" alt="URL" /></a></td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <td><a href="#" class="file">Dexter_s1_e1.mp4</a></td>
                            <td>347.1 MB</td>
                            <td>17.09.2012, 18:01</td>
                            <td><a href="#"><img src="<?php echo $_smarty_tpl->tpl_vars['_template']->value;?>
/img/icon_24_chain.png" width="24" height="24" alt="URL" /></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="cleaner"></div>

            <div class="loading-panel">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <td>Имя</td>
                        <td width="140">Размер</td>
                        <td width="200">Статус</td>
                        <td width="140">Скорость</td>
                    </tr>
                    </thead>
                    <tbody id="uploading-files">
                        <tr>
                            <td>Dexter_s1_e1.mp4</td>
                            <td>292 MB</td>
                            <td>В очереди</td>
                            <td>742 Kb/s</td>
                        </tr>
                        <tr>
                            <td>Dexter_s1_e1.mp4</td>
                            <td>292 MB</td>
                            <td class="dark-blue">В очереди</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Dexter_s1_e1.mp4</td>
                            <td>292 MB</td>
                            <td class="dark-blue">В очереди</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dialog dialog-dir" title="Создать новую папку">
            <div class="place-holder-message"></div>
            <form action="index.php?module=dir&action=add" method="post" id="formDir">
                <input name="dir_name" type="text" class="input-text" placeholder="Имя новой папки" />
                <input type="submit" value="Создать папку" class="submit" />
            </form>
        </div>

        <div class="dialog dialog-dir-rename" title="Переименовать папку">
            <div class="place-holder-message"></div>
            <form action="index.php?module=dir&action=rename" method="post" id="formDirRename">
                <input name="dir_name" type="text" class="input-text" placeholder="Имя папки" />
                <input name="id" type="hidden" value="0" id="dir_id" />
                <input type="submit" value="Переименовать" class="submit" />
            </form>
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
                <li><a href="/index.php?module=signup_social&provider=Google" class="google"></a></li>
                <li><a href="/index.php?module=signup_social&provider=Facebook" class="facebook"></a></li>
                <li><a href="/index.php?module=signup_social&provider=Vkontakte" class="vkontakte"></a></li>
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
                <li><a href="/index.php?module=signup_social&provider=Google" class="google"></a></li>
                <li><a href="/index.php?module=signup_social&provider=Facebook" class="facebook"></a></li>
                <li><a href="/index.php?module=signup_social&provider=Vkontakte" class="vkontakte"></a></li>
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