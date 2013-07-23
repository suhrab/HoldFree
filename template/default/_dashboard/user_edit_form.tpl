{extends "_dashboard/index.tpl"}

{block name="pageTitle"}<span class="icon-user-2"></span>Пользователи{/block}

{block name="breadcrumbs"}<li><a href="/?module=dashboard&dashboard=1">Панель управления</a></li><li><a href="/?module=user&dashboard=1">Пользователи</a></li><li class="current"><a href="#">Редактирование</a></li>{/block}

{block name="content"}
    <div class="fluid">
        <form id="usualValidate" class="main" method="post" action="/index.php?module=user&action=update&dashboard=1" novalidate="novalidate">
            <fieldset>
                <div class="widget">
                    <div class="whead"><h6>Редактирование</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>Имя пользователя:<span class="req">*</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="first_name" id="first_name" value="{$user.first_name}"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Фамилия пользователя:</label></div>
                        <div class="grid9"><input type="text" name="last_name" id="last_name" value="{$user.last_name}"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Email пользователя:<span class="req">*</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="email" id="email" value="{$user.email}"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Страна:<span class="req">*</span></label></div>
                        <div class="grid9">
                            <select name="country" id="country" class="required">
                                {foreach from=$countries item=country}
                                <option value="{$country.id}" {if $user.country == $country.id}selected="selected"{/if}>{$country.name}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Группа:<span class="req">*</span></label></div>
                        <div class="grid9">
                            <select name="group" id="group" class="required">
                                <option value="0" {if $user.group == 0}selected="selected"{/if}>Пользователи</option>
                                <option value="1" {if $user.group == 1}selected="selected"{/if}>Администраторы</option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Новывй аватар:</label></div>
                        <div class="grid9">
                            <input type="file" class="fileInput" id="fileInput" />
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
                        <div class="grid3"><label>Забанен:</label></div>
                        <div class="grid9 on_off">
                            <div class="floatL mr10"><input type="checkbox" id="ban" name="ban" {if $user.ban}checked="checked"{/if} /></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Дата окончания бана:</label></div>
                        <div class="grid9"><input type="text" class="datepicker" value="{$user.ban_end}" name="ban_end" /></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Причина бана:</label></div>
                        <div class="grid9"><input type="text" name="ban_reason" id="reason" value="{$user.ban_reason}"></div><div class="clear"></div>
                    </div>
                    <div class="formRow"><input type="submit" value="Сохранить" class="buttonM bBlack formSubmit"><div class="clear"></div></div>
                    <div class="clear"></div>
                    <input type="hidden" name="id" value="{$user.id}" />
                </div>
            </fieldset>
        </form>
    </div>
{/block}