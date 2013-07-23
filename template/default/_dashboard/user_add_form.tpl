{extends "_dashboard/index.tpl"}

{block name="pageTitle"}<span class="icon-user-2"></span>Пользователи{/block}

{block name="breadcrumbs"}<li><a href="/?module=dashboard&dashboard=1">Панель управления</a></li><li><a href="/?module=user&dashboard=1">Пользователи</a></li><li class="current"><a href="#">Новый пользователь</a></li>{/block}

{block name="content"}
    <div class="fluid">
        <form id="usualValidate" class="main" method="post" action="/index.php?module=user&action=add&dashboard=1" novalidate="novalidate">
            <fieldset>
                <div class="widget">
                    <div class="whead"><h6>Новый пользователь</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>Имя пользователя:<span class="req">*</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="first_name" id="first_name"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Фамилия пользователя:</label></div>
                        <div class="grid9"><input type="text" name="last_name" id="last_name"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Email пользователя:<span class="req">*</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="email" id="email"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Пароль пользователя:<span class="req">*</span></label></div>
                        <div class="grid9"><input type="password" class="required" name="password" id="password"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Страна:<span class="req">*</span></label></div>
                        <div class="grid9">
                            <select name="country" id="country" class="required">
                                {foreach from=$countries item=country}
                                <option value="{$country.id}">{$country.name}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Группа:<span class="req">*</span></label></div>
                        <div class="grid9">
                            <select name="group" id="group" class="required">
                                <option value="0">Пользователи</option>
                                <option value="1">Администраторы</option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Аватар:</label></div>
                        <div class="grid9">
                            <input type="file" class="fileInput" id="fileInput" size="18.5" style="opacity: 0;">
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow"><input type="submit" value="Добавить пользователя" class="buttonM bBlack formSubmit"><div class="clear"></div></div>
                    <div class="clear"></div>
                </div>
            </fieldset>
        </form>
    </div>
{/block}