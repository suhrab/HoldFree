<?php /* Smarty version Smarty-3.1.13, created on 2013-07-20 14:22:52
         compiled from "/Users/Gee/Sites/Holdfree/template/default/_dashboard/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:88747226851ddcdab90fbf9-46424440%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c2d8af8601aa1dd15bbb9a959c5a5163fc9f0999' => 
    array (
      0 => '/Users/Gee/Sites/Holdfree/template/default/_dashboard/index.tpl',
      1 => 1374240988,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '88747226851ddcdab90fbf9-46424440',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51ddcdab968de1_41936856',
  'variables' => 
  array (
    'meta_title' => 0,
    '_dashboard' => 0,
    '_url' => 0,
    '_user' => 0,
    'user_log' => 0,
    'log' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51ddcdab968de1_41936856')) {function content_51ddcdab968de1_41936856($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title><?php echo $_smarty_tpl->tpl_vars['meta_title']->value;?>
 &mdash; Панель управления</title>

<link href="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/css/styles.css" rel="stylesheet" type="text/css" />
<!--[if IE]> <link href="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/css/ie.css" rel="stylesheet" type="text/css"> <![endif]-->

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/ui.spinner.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/jquery.mousewheel.js"></script>
 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/charts/excanvas.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/charts/jquery.flot.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/charts/jquery.flot.orderBars.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/charts/jquery.flot.pie.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/charts/jquery.flot.resize.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/charts/jquery.flot.time.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/charts/jquery.sparkline.min.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/tables/jquery.sortable.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/tables/jquery.resizable.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/autogrowtextarea.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/jquery.uniform.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/jquery.inputlimiter.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/jquery.autotab.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/jquery.chosen.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/jquery.dualListBox.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/jquery.cleditor.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/jquery.ibutton.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/forms/jquery.validationEngine.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/uploader/plupload.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/uploader/plupload.html4.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/uploader/plupload.html5.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/uploader/jquery.plupload.queue.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/wizards/jquery.form.wizard.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/wizards/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/wizards/jquery.form.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/ui/jquery.collapsible.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/ui/jquery.breadcrumbs.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/ui/jquery.tipsy.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/ui/jquery.progress.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/ui/jquery.timeentry.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/ui/jquery.colorpicker.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/ui/jquery.jgrowl.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/ui/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/ui/jquery.fileTree.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/ui/jquery.sourcerer.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/others/jquery.fullcalendar.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/others/jquery.elfinder.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/plugins/ui/jquery.easytabs.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/files/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/files/functions.js"></script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/charts/chart.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/js/charts/hBar_side.js"></script>

</head>

<body>

<!-- Top line begins -->
<div id="top">
    <div class="wrapper">
        <a href="/?module=dashboard&dashboard=1" title="" class="logo"><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/logo.png" alt="" /></a>
        
        <!-- Right top nav -->
        <div class="topNav">
            <ul class="userNav">
                <li><a title="" class="search"></a></li>
                <li><a href="#" title="" class="screen"></a></li>
                <li><a href="#" title="" class="settings"></a></li>
                <li><a href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
" title="" class="logout"></a></li>
                <li class="showTabletP"><a href="#" title="" class="sidebar"></a></li>
            </ul>
            <a title="" class="iButton"></a>
            <a title="" class="iTop"></a>
            <div class="topSearch">
                <div class="topDropArrow"></div>
                <form action="">
                    <input type="text" placeholder="search..." name="topSearch" />
                    <input type="submit" value="" />
                </form>
            </div>
        </div>
        
        <!-- Responsive nav -->
        <ul class="altMenu">
            <li><a href="index.html" title="">Dashboard</a></li>
            <li><a href="ui.html" title="" class="exp" id="current">UI elements</a>
                <ul>
                    <li><a href="ui.html">General elements</a></li>
                    <li><a href="ui_icons.html">Icons</a></li>
                    <li><a href="ui_buttons.html">Button sets</a></li>
                    <li><a href="ui_grid.html" class="active">Grid</a></li>
                    <li><a href="ui_custom.html">Custom elements</a></li>
                    <li><a href="ui_experimental.html">Experimental</a></li>
                </ul>
            </li>
            <li><a href="forms.html" title="" class="exp">Общие настройки</a>
                <ul>
                    <li><a href="forms.html">Inputs &amp; elements</a></li>
                    <li><a href="form_validation.html">Validation</a></li>
                    <li><a href="form_editor.html">File uploads &amp; editor</a></li>
                    <li><a href="form_wizards.html">Form wizards</a></li>
                </ul>
            </li>
            <li><a href="messages.html" title="">Messages</a></li>
            <li><a href="statistics.html" title="">Statistics</a></li>
            <li><a href="tables.html" title="" class="exp">Tables</a>
                <ul>
                    <li><a href="tables.html">Standard tables</a></li>
                    <li><a href="tables_dynamic.html">Dynamic tables</a></li>
                    <li><a href="tables_control.html">Tables with control</a></li>
                    <li><a href="tables_sortable.html">Sortable &amp; resizable</a></li>
                </ul>
            </li>
            <li><a href="other_calendar.html" title="" class="exp">Other pages</a>
                <ul>
                    <li><a href="other_calendar.html">Calendar</a></li>
                    <li><a href="other_gallery.html">Images gallery</a></li>
                    <li><a href="other_file_manager.html">File manager</a></li>
                    <li><a href="other_404.html">Sample error page</a></li>
                    <li><a href="other_typography.html">Typography</a></li>
                </ul>
            </li>
        </ul>
        <div class="clear"></div>
    </div>
</div>
<!-- Top line ends -->


<!-- Sidebar begins -->
<div id="sidebar">
    <div class="mainNav">
        <div class="user">
            <a title="" class="leftUserDrop"><img src="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
/upload/avatar/_thumb/<?php echo $_smarty_tpl->tpl_vars['_user']->value['avatar'];?>
" width="72" height="72" alt="" /><span><strong>3</strong></span></a><span><?php echo $_smarty_tpl->tpl_vars['_user']->value['first_name'];?>
</span>
            <ul class="leftUser">
                <li><a href="#" title="" class="sProfile">My profile</a></li>
                <li><a href="#" title="" class="sMessages">Messages</a></li>
                <li><a href="#" title="" class="sSettings">Settings</a></li>
                <li><a href="#" title="" class="sLogout">Logout</a></li>
            </ul>
        </div>
        
        <!-- Responsive nav -->
        <div class="altNav">
            <div class="userSearch">
                <form action="">
                    <input type="text" placeholder="search..." name="userSearch" />
                    <input type="submit" value="" />
                </form>
            </div>
            
            <!-- User nav -->
            <ul class="userNav">
                <li><a href="#" title="" class="profile"></a></li>
                <li><a href="#" title="" class="messages"></a></li>
                <li><a href="#" title="" class="settings"></a></li>
                <li><a href="#" title="" class="logout"></a></li>
            </ul>
        </div>
        
        <!-- Main nav -->
        <ul class="nav">
            <li><a href="/?module=dashboard&dashboard=1" title="" <?php if ($_GET['module']=='dashboard'){?>class="active"<?php }?>><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/icons/mainnav/dashboard.png" alt="" /><span>Dashboard</span></a></li>
            <li><a href="/?module=user&dashboard=1" title="Пользователи" <?php if ($_GET['module']=='user'){?>class="active"<?php }?>><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/icons/mainnav/ui.png" alt="" /><span>Пользователи</span></a>
                <ul>
                    <li><a href="ui.html" title=""><span class="icol-fullscreen"></span>General elements</a></li>
                    <li><a href="ui_icons.html" title=""><span class="icol-images2"></span>Icons</a></li>
                    <li><a href="ui_buttons.html" title=""><span class="icol-coverflow"></span>Button sets</a></li>
                    <li><a href="ui_grid.html" title=""><span class="icol-view"></span>Grid</a></li>
                    <li><a href="ui_custom.html" title=""><span class="icol-cog2"></span>Custom elements</a></li>
                    <li><a href="ui_experimental.html" title=""><span class="icol-beta"></span>Experimental</a></li>
                </ul>
            </li>
            <li><a href="/index.php?module=option&dashboard=1" title=""><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/icons/mainnav/forms.png" alt="" /><span>Настройки</span></a>
                <ul>
                    <li><a href="forms.html" title=""><span class="icol-list"></span>Inputs &amp; elements</a></li>
                    <li><a href="form_validation.html" title=""><span class="icol-alert"></span>Validation</a></li>
                    <li><a href="form_editor.html" title=""><span class="icol-pencil"></span>File uploader &amp; WYSIWYG</a></li>
                    <li><a href="form_wizards.html" title=""><span class="icol-signpost"></span>Form wizards</a></li>
                </ul>
            </li>
            <li><a href="messages.html" title=""><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/icons/mainnav/messages.png" alt="" /><span>Messages</span></a></li>
            <li><a href="statistics.html" title=""><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/icons/mainnav/statistics.png" alt="" /><span>Statistics</span></a></li>
            <li><a href="tables.html" title=""><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/icons/mainnav/tables.png" alt="" /><span>Tables</span></a>
                <ul>
                    <li><a href="tables.html" title=""><span class="icol-frames"></span>Standard tables</a></li>
                    <li><a href="tables_dynamic.html" title=""><span class="icol-refresh"></span>Dynamic table</a></li>
                    <li><a href="tables_control.html" title=""><span class="icol-bullseye"></span>Tables with control</a></li>
                    <li><a href="tables_sortable.html" title=""><span class="icol-transfer"></span>Sortable and resizable</a></li>
                </ul>
            </li>
            <li><a href="other_calendar.html" title=""><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/icons/mainnav/other.png" alt="" /><span>Other pages</span></a>
                <ul>
                    <li><a href="other_calendar.html" title=""><span class="icol-dcalendar"></span>Calendar</a></li>
                    <li><a href="other_gallery.html" title=""><span class="icol-images2"></span>Images gallery</a></li>
                    <li><a href="other_file_manager.html" title=""><span class="icol-files"></span>File manager</a></li>
                    <li><a href="#" title="" class="exp"><span class="icol-alert"></span>Error pages <span class="dataNumRed">6</span></a>
                        <ul>
                            <li><a href="other_403.html" title="">403 error</a></li>
                            <li><a href="other_404.html" title="">404 error</a></li>
                            <li><a href="other_405.html" title="">405 error</a></li>
                            <li><a href="other_500.html" title="">500 error</a></li>
                            <li><a href="other_503.html" title="">503 error</a></li>
                            <li><a href="other_offline.html" title="">Website is offline error</a></li>
                        </ul>
                    </li>
                    <li><a href="other_typography.html" title=""><span class="icol-create"></span>Typography</a></li>
                    <li><a href="other_invoice.html" title=""><span class="icol-money2"></span>Invoice template</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- Sidebar ends -->
    
    
<!-- Content begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle">
            <span class="icon-screen"></span>Панель управления
        </span>
        <ul class="quickStats">
            <li>
                <a href="" class="blueImg"><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/icons/quickstats/plus.png" alt="" /></a>
                <div class="floatR"><strong class="blue">5489</strong><span>visits</span></div>
            </li>
            <li>
                <a href="" class="redImg"><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/icons/quickstats/user.png" alt="" /></a>
                <div class="floatR"><strong class="blue">4658</strong><span>users</span></div>
            </li>
            <li>
                <a href="" class="greenImg"><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/icons/quickstats/money.png" alt="" /></a>
                <div class="floatR"><strong class="blue">1289</strong><span>orders</span></div>
            </li>
        </ul>
        <div class="clear"></div>
    </div>
    
    <!-- Breadcrumbs line -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
            
                <li class="current"><a href="/?module=dashboard&dashboard=1">Панель управления</a></li>
            
            </ul>
        </div>
        
        <div class="breadLinks">
            <ul>
                <li><a href="#" title=""><i class="icos-list"></i><span>Orders</span> <strong>(+58)</strong></a></li>
                <li><a href="#" title=""><i class="icos-check"></i><span>Tasks</span> <strong>(+12)</strong></a></li>
                <li class="has">
                    <a title="">
                        <i class="icos-money3"></i>
                        <span>Invoices</span>
                        <span><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/elements/control/hasddArrow.png" alt="" /></span>
                    </a>
                    <ul>
                        <li><a href="#" title=""><span class="icos-add"></span>New invoice</a></li>
                        <li><a href="#" title=""><span class="icos-archive"></span>History</a></li>
                        <li><a href="#" title=""><span class="icos-printer"></span>Print invoices</a></li>
                    </ul>
                </li>
            </ul>
             <div class="clear"></div>
        </div>
    </div>
    
    <!-- Main content -->
    <div class="wrapper">
    
    	<!-- Chart -->
        <div class="widget chartWrapper">
            <div class="whead"><h6>Статистика посещаемости</h6>
                <div class="titleOpt">
                    <a href="#" data-toggle="dropdown"><span class="icos-cog3"></span><span class="clear"></span></a>
                    <ul class="dropdown-menu pull-right">
                            <li><a href="#"><span class="icos-add"></span>Add</a></li>
                            <li><a href="#"><span class="icos-trash"></span>Remove</a></li>
                            <li><a href="#" class=""><span class="icos-pencil"></span>Edit</a></li>
                            <li><a href="#" class=""><span class="icos-heart"></span>Do whatever you like</a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="body"><div class="chart"></div></div>
        </div>


        <div class="widget">
        <div class="whead"><h6>Лог пользователей</h6><div class="clear"></div></div>
        <div id="dyn" class="hiddenpars">
        <a class="tOptions" title="Options"><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/icons/options" alt="" /></a>
        <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="dynamic">
        <thead>
        <tr>
            <th>Пользователь</th>
            <th>Ренедринг дживок</th>
            <th>Браузер</th>
            <th>Платформа</th>
            <th>Время</th>
        </tr>
        </thead>
        <tbody>
        <?php  $_smarty_tpl->tpl_vars['log'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['log']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['user_log']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['log']->key => $_smarty_tpl->tpl_vars['log']->value){
$_smarty_tpl->tpl_vars['log']->_loop = true;
?>
        <tr class="gradeU">
            <td><a href="/index.php?module=user&action=edit_form&dashboard=1&id=<?php echo $_smarty_tpl->tpl_vars['log']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['log']->value['first_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['log']->value['last_name'];?>
</a></td>
            <td><?php echo $_smarty_tpl->tpl_vars['log']->value['engine'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['log']->value['browser'];?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['log']->value['os'];?>
</td>
            <td class="center"><?php echo $_smarty_tpl->tpl_vars['log']->value['time'];?>
</td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
        </div>
        </div>
    
    	<!-- 6 + 6 -->
        <div class="fluid">
        
            <!-- Messages #1 -->
            <div class="widget grid6">
                <div class="whead">
                    <h6>Messages layout #1</h6>
                    <div class="on_off">
                        <span class="icon-reload-CW"></span>
                        <input type="checkbox" id="check1" checked="checked" name="chbox" />
                    </div>            
                    <div class="clear"></div>
                </div>
                
                <ul class="messagesOne">
                    <li class="by_user">
                        <a href="#" title=""><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/live/face1.png" alt="" /></a>
                        <div class="messageArea">
                            <span class="aro"></span>
                            <div class="infoRow">
                                <span class="name"><strong>John</strong> says:</span>
                                <span class="time">3 hours ago</span>
                                <div class="clear"></div>
                            </div>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel est enim, vel eleifend felis. Ut volutpat, leo eget euismod scelerisque, eros purus lacinia velit, nec rhoncus mi dui eleifend orci. 
                            Phasellus ut sem urna, id congue libero. Nulla eget arcu vel massa suscipit ultricies ac id velit
                        </div>
                        <div class="clear"></div>
                    </li>
                
                    <li class="divider"><span></span></li>
                
                    <li class="by_me">
                        <a href="#" title=""><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/live/face2.png" alt="" /></a>
                        <div class="messageArea">
                            <span class="aro"></span>
                            <div class="infoRow">
                                <span class="name"><strong>Eugene</strong> says:</span>
                                <span class="time">3 hours ago</span>
                                <div class="clear"></div>
                            </div>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel est enim, vel eleifend felis. Ut volutpat, leo eget euismod scelerisque, eros purus lacinia velit, nec rhoncus mi dui eleifend orci. 
                            Phasellus ut sem urna, id congue libero. Nulla eget arcu vel massa suscipit ultricies ac id velit
                        </div>
                        <div class="clear"></div>
                    </li>
                
                    <li class="by_me">
                        <a href="#" title=""><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/live/face2.png" alt="" /></a>
                        <div class="messageArea">
                            <span class="aro"></span>
                            <div class="infoRow">
                                <span class="name"><strong>Eugene</strong> says:</span>
                                <span class="time">3 hours ago</span>
                                <div class="clear"></div>
                            </div>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel est enim, vel eleifend felis. Ut volutpat, leo eget euismod scelerisque, eros purus lacinia velit, nec rhoncus mi dui eleifend orci. 
                            Phasellus ut sem urna, id congue libero. Nulla eget arcu vel massa suscipit ultricies ac id velit
                        </div>
                        <div class="clear"></div>
                    </li>
                    
                    <li class="divider"><span></span></li>
                
                    <li class="by_user">
                        <a href="#" title=""><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/live/face1.png" alt="" /></a>
                        <div class="messageArea">
                            <span class="aro"></span>
                            <div class="infoRow">
                                <span class="name"><strong>John</strong> says:</span>
                                <span class="time">3 hours ago</span>
                                <div class="clear"></div>
                            </div>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel est enim, vel eleifend felis. Ut volutpat, leo eget euismod scelerisque, eros purus lacinia velit, nec rhoncus mi dui eleifend orci. 
                            Phasellus ut sem urna, id congue libero. Nulla eget arcu vel massa suscipit ultricies ac id velit
                        </div>
                        <div class="clear"></div>
                    </li>
                    
                    <li class="divider"><span></span></li>
                
                    <li class="by_me">
                        <a href="#" title=""><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/live/face2.png" alt="" /></a>
                        <div class="messageArea">
                            <span class="aro"></span>
                            <div class="infoRow">
                                <span class="name"><strong>Eugene</strong> says:</span>
                                <span class="time">3 hours ago</span>
                                <div class="clear"></div>
                            </div>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel est enim, vel eleifend felis. Ut volutpat, leo eget euismod scelerisque, eros purus lacinia velit, nec rhoncus mi dui eleifend orci. 
                            Phasellus ut sem urna, id congue libero. Nulla eget arcu vel massa suscipit ultricies ac id velit
                        </div>
                        <div class="clear"></div>
                    </li>
                </ul>
            </div>
            
            <!-- Calendar -->
            <div class="widget grid6">
                <div class="whead"><h6>Calendar</h6><div class="clear"></div></div>
                <div id="calendar"></div>
            </div>
            <div class="clear"></div>
        </div>


        
        <div class="fluid">
        	
            <div class="grid6">
                <!-- Search widget -->
                <div class="searchLine">
                    <form action="">
                        <input type="text" name="search" class="ac" placeholder="Enter search text..." />
                       <button type="submit" name="find" value=""><span class="icos-search"></span></button>
                    </form>
                </div>
                
                <!-- Multiple files uploader -->
                <div class="widget">    
                    <div class="whead"><h6>WYSIWYG editor</h6><div class="clear"></div></div>
                    <textarea id="editor" name="editor" rows="" cols="16">Some cool stuff here</textarea>                    
                </div>
            </div>
            
            <!-- Media table -->
          <div class="widget check grid6">
            <div class="whead">
                <span class="titleIcon"><input type="checkbox" id="titleCheck" name="titleCheck" /></span>
                <h6>Media table</h6><div class="clear"></div>
            </div>
            <table cellpadding="0" cellspacing="0" width="100%" class="tDefault checkAll tMedia" id="checkAll">
                <thead>
                    <tr>
                        <td><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/elements/other/tableArrows.png" alt="" /></td>
                        <td width="50">Image</td>
                        <td class="sortCol"><div>Description<span></span></div></td>
                        <td width="130" class="sortCol"><div>Date<span></span></div></td>
                        <td width="120">File info</td>
                        <td width="100">Actions</td>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="itemActions">
                                <label>Apply action:</label>
                                <select>
                                    <option value="">Select action...</option>
                                    <option value="Edit">Edit</option>
                                    <option value="Delete">Delete</option>
                                    <option value="Move">Move somewhere</option>
                                </select>
                            </div>
                            <div class="tPages">
                                <ul class="pages">
                                    <li class="prev"><a href="#" title=""><span class="icon-arrow-14"></span></a></li>
                                    <li><a href="#" title="" class="active">1</a></li>
                                    <li><a href="#" title="">2</a></li>
                                    <li><a href="#" title="">3</a></li>
                                    <li><a href="#" title="">4</a></li>
                                    <li>...</li>
                                    <li><a href="#" title="">20</a></li>
                                    <li class="next"><a href="#" title=""><span class="icon-arrow-17"></span></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <tr>
                        <td><input type="checkbox" name="checkRow" /></td>
                        <td><a href="images/big.png" title="" class="lightbox"><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/live/face3.png" alt="" /></a></td>
                        <td class="textL"><a href="#" title="">Image1 description</a></td>
                        <td>Feb 12, 2012. 12:28</td>
                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                        <td class="tableActs">
                            <a href="#" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove"><span class="iconb" data-icon="&#xe136;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="checkRow" /></td>
                        <td><a href="images/big.png" title="" class="lightbox"><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/live/face7.png" alt="" /></a></td>
                        <td class="textL"><a href="#" title="">Image1 description</a></td>
                        <td>Feb 12, 2012. 12:28</td>
                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                        <td class="tableActs">
                            <a href="#" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove"><span class="iconb" data-icon="&#xe136;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="checkRow" /></td>
                        <td><a href="images/big.png" title="" class="lightbox"><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/live/face6.png" alt="" /></a></td>
                        <td class="textL"><a href="#" title="">Image1 description</a></td>
                        <td>Feb 12, 2012. 12:28</td>
                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                        <td class="tableActs">
                            <a href="#" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove"><span class="iconb" data-icon="&#xe136;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="checkRow" /></td>
                        <td><a href="images/big.png" title="" class="lightbox"><img src="<?php echo $_smarty_tpl->tpl_vars['_dashboard']->value;?>
/images/live/face5.png" alt="" /></a></td>
                        <td class="textL"><a href="#" title="">Image1 description</a></td>
                        <td>Feb 12, 2012. 12:28</td>
                        <td class="fileInfo"><span><strong>Size:</strong> 215 Kb</span><span><strong>Format:</strong> .jpg</span></td>
                        <td class="tableActs">
                            <a href="#" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Remove"><span class="iconb" data-icon="&#xe136;"></span></a>
                            <a href="#" class="tablectrl_small bDefault tipS" title="Options"><span class="iconb" data-icon="&#xe1f7;"></span></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
            
        </div>
    
    </div>
    <!-- Main content ends -->
    
</div>
<!-- Content ends -->

</body>
</html>
<?php }} ?>