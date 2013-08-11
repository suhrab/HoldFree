{extends "_dashboard/index.tpl"}

{block name="pageTitle"}<span class="icon-user-2"></span>Статические страницы{/block}

{block name="breadcrumbs"}<li><a href="/?module=dashboard&dashboard=1">Панель управления</a></li><li class="current"><a href="/?module=storage_server&dashboard=1">Статические страницы</a></li>{/block}

{block name="content"}
    <script>
        $(function(){
            var staticPageErrors = {$staticPageErrors|json_encode};
            for(var i in staticPageErrors){
                $.jGrowl(staticPageErrors[i], {
                    header: '',
                    position: 'bottom-right'
                });
            }

            $("#staticPageDeleteDialog").dialog({
                autoOpen: false,
                width: 400,
                modal: true,
                buttons: {
                    "Да": function () {
                        $('#staticPageDeleteId').val($(this).data("staticPageDeleteId"))
                        $('#staticPageDeleteForm').submit()

                        $(this).dialog("close");
                    },
                    "Нет": function () {
                        $(this).dialog("close");
                    }
                }
            });

            $(".removeStaticPageButton").on("click", function() {
                $('#staticPageDeleteDialog').data("staticPageDeleteId", $(this).data("id")).dialog('open');
                return false;
            });
        })
    </script>
    <div class="widget check">
        <div class="whead">
            <h6>Список страниц</h6>
            <div class="clear"></div>
        </div>
        <table cellpadding="0" cellspacing="0" width="100%" class="tDefault checkAll tMedia" id="checkAll">
            <thead>
            <tr>
                <td width="40"><img src="{$_dashboard}/images/elements/other/tableArrows.png" alt="" /></td>
                <td>Название страницы</td>
                <td width="400">Автор</td>
                <td width="150">Время создания</td>
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
            {foreach $staticPages as $page}
            <tr id="page_{$page.id}">
                <td>{$page.id}</td>
                <td>{$page.title}</td>
                <td>{if $_user.id == $page.author_id}Вы{else}{$page.first_name} {$page.last_name}{/if}</td>
                <td>{$page.create_time}</td>
                <td>
                    <a href="/?module=static_page&staticPageId={$page.id}" class="tablectrl_small bDefault tipS" title="URL"><span class="iconb" data-icon="&#xe14f;"></span></a>
                    <a href="/index.php?module=static_pages&dashboard=1&staticPageEditId={$page.id}" class="tablectrl_small bDefault tipS" title="Редактировать"><span class="iconb" data-icon="&#xe1db;"></span></a>
                    <a href="#" class="tablectrl_small bDefault tipS removeStaticPageButton" title="Удалить" data-id="{$page.id}"><span class="iconb" data-icon="&#xe136;"></span></a>
                </td>
            </tr>
            {/foreach}
            </tbody>
        </table>
    </div>

    <div class="fluid">
        <form id="usualValidate" class="main" method="post" action="/?{$smarty.server.QUERY_STRING}">
            <fieldset>
                <div class="widget">
                    <div class="whead"><h6>{if !empty($smarty.get.staticPageEditId)}Редактирование страницы ID{$smarty.get.staticPageEditId}{else}Добавление новой страницы{/if}</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>Имя страницы:<span class="req">*</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="staticPageTitle" value="{if !empty($staticPageEditData)}{$staticPageEditData.title}{/if}" /></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Содержимое страницы:<span class="req">*</span></label></div>
                        <div class="grid9"><textarea id="StaticPageContentTextarea" name="staticPageContent">{if !empty($staticPageEditData)}{$staticPageEditData.content}{/if}</textarea></text></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <input type="submit" value="{if !empty($smarty.get.staticPageEditId)}Сохранить изменения{else}Добавить страницу{/if}" class="buttonM bGreen formSubmit">
                        <div class="clear"></div>
                    </div>
                </div>
            </fieldset>
            <input type="hidden" name="action" value="{if !empty($smarty.get.staticPageEditId)}edit{else}add{/if}" />
            {if !empty($smarty.get.staticPageEditId)}<input type="hidden" name="staticPageEditId" value="{$smarty.get.staticPageEditId}">{/if}
        </form>
    </div>

    <div id="staticPageDeleteDialog" class="dialog" title="Удалить страницу">
        <div>
            Вы уверены, что хотите удалить эту страницу?
        </div>
        <form class="main" method="post" action="/?{$smarty.server.QUERY_STRING}" id="staticPageDeleteForm">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="staticPageDeleteId" id="staticPageDeleteId">
        </form>
    </div>
{/block}