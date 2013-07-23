{extends "_dashboard/index.tpl"}

{block name="pageTitle"}<span class="icon-user-2"></span>Пользователи{/block}

{block name="breadcrumbs"}<li><a href="/?module=dashboard&dashboard=1">Панель управления</a></li><li class="current"><a href="/?module=user&dashboard=1">Пользователи</a></li>{/block}

{block name="content"}
    <div class="widget check">
        <div class="whead">
            <h6>Список пользователей</h6>
            <div class="titleOpt">
                <a href="#" data-toggle="dropdown"><span class="icos-cog3"></span><span class="clear"></span></a>
                <ul class="dropdown-menu pull-right">
                    <li><a href="/index.php?module=user&action=addform&dashboard=1"><span class="icos-add"></span>Добавить пользователя</a></li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
        <table cellpadding="0" cellspacing="0" width="100%" class="tDefault checkAll tMedia" id="checkAll">
            <thead>
            <tr>
                <td><img src="{$_dashboard}/images/elements/other/tableArrows.png" alt="" /></td>
                <td width="50">Аватар</td>
                <td class="sortCol"><div>Имя Фамилия<span></span></div></td>
                <td width="200">Страна</td>
                <td width="140">Посещение</td>
                <td width="140">Регистрация</td>
                <td width="120">Файлы</td>
                <td width="100">Действие</td>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="8">
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
                            <li><a href="#" title="">5</a></li>
                            <li><a href="#" title="">6</a></li>
                            <li>...</li>
                            <li><a href="#" title="">20</a></li>
                            <li class="next"><a href="#" title=""><span class="icon-arrow-17"></span></a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            </tfoot>
            <tbody>
            {foreach from=$users item=user}
            <tr id="user_{$user.id}">
                <td>{if $user.ban}<span class="label label-important">B</span>{/if}</td>
                <td><a href="{$_url}/upload/avatar/_thumb/{$user.avatar}" title="" class="lightbox"><img src="{$_url}/upload/avatar/_thumb/{$user.avatar}" width="37" height="37" alt="" /></a></td>
                <td class="textL"><a href="/index.php?module=user&action=edit_form&dashboard=1&id={$user.id}">{$user.first_name} {$user.last_name}</a></td>
                <td>{$user.country}</td>
                <td>{$user.last_signin}</td>
                <td>{$user.reg_date}</td>
                <td class="fileInfo"><span><strong>Загружено:</strong> {$user.files}</span><span><strong>Размер:</strong> 0 GB</span></td>
                <td class="tableActs">
                    <a href="/index.php?module=user&action=edit_form&dashboard=1&id={$user.id}" class="tablectrl_small bDefault tipS" title="Редактировать"><span class="iconb" data-icon="&#xe1db;"></span></a>
                    <a href="#" class="tablectrl_small bDefault tipS removeButton" title="Удалить" data-id="{$user.id}"><span class="iconb" data-icon="&#xe136;"></span></a>
                </td>
            </tr>
            {/foreach}
            </tbody>
        </table>
    </div>

    <div id="userConfirmDialog" class="dialog" title="Удалить пользователя">Вы уверены, что хотите безвозвратно удалить этого пользователя?</div>
{/block}