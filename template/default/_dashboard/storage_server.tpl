{extends "_dashboard/index.tpl"}

{block name="pageTitle"}<span class="icon-user-2"></span>Сервера хранения файлов{/block}

{block name="breadcrumbs"}<li><a href="/?module=dashboard&dashboard=1">Панель управления</a></li><li class="current"><a href="/?module=storage_server&dashboard=1">Сервера хранения файлов</a></li>{/block}

{block name="content"}
    <div class="widget check">
        <div class="whead">
            <h6>Список серверов</h6>
            <div class="clear"></div>
        </div>
        <table cellpadding="0" cellspacing="0" width="100%" class="tDefault checkAll tMedia" id="checkAll">
            <thead>
            <tr>
                <td width="40"><img src="{$_dashboard}/images/elements/other/tableArrows.png" alt="" /></td>
                <td>Имя сервера</td>
                <td width="400">URL</td>
                <td width="150">Доступного места</td>
                <td width="150">Общее места</td>
                <td width="150">Количество файлов</td>
                <td width="100">Действие</td>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="8">

                </td>
            </tr>
            </tfoot>
            <tbody>
            {foreach from=$servers item=server}
            <tr id="server_{$server.id}">
                <td>{$server.id}</td>
                <td>{$server.name}</td>
                <td>{$server.url}</td>
                <td>{$server.free_space}</td>
                <td>{$server.total_space}</td>
                <td>{$server.num_files}</td>
                <td>
                    <a href="#" class="tablectrl_small bDefault tipS removeStorageButton" title="Удалить" data-id="{$server.id}"><span class="iconb" data-icon="&#xe136;"></span></a>
                </td>
            </tr>
            {/foreach}
            </tbody>
        </table>
    </div>

    <div class="fluid">
        <form id="usualValidate" class="main" method="post" action="index.php?module=storage_server&dashboard=1">
            <fieldset>
                <div class="widget">
                    <div class="whead"><h6>Добавление нового сервера</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>Имя сервера:<span class="req">*</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="name" /></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>URL:<span class="req">*</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="url" /></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <input type="submit" value="Добавить сервер" class="buttonM bGreen formSubmit">
                        <div class="clear"></div>
                    </div>
                </div>
            </fieldset>
            <input type="hidden" name="action" value="add" />
        </form>
    </div>

    <div id="storageDeleteDialog" class="dialog" title="Удалить сервер хранения">Вы уверены, что хотите удалить этот сервер?</div>
{/block}