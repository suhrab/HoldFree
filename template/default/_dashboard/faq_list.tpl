{extends "_dashboard/index.tpl"}

{block name="pageTitle"}<span class="icon-settings"></span>FAQ{/block}

{block name="breadcrumbs"}<li><a href="/?module=dashboard&dashboard=1">Панель управления</a></li><li class="current"><a href="/?module=faq&dashboard=1">FAQ</a></li>{/block}

{block name="content"}
    <div class="widget">
        <div class="whead"><h6>FAQ</h6><div class="clear"></div></div>

        <table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
            <thead>
            <tr>
                <td>Вопрос</td>
                <td width="200">Действие</td>
            </tr>
            </thead>
            <tbody>
            {foreach from=$faq_list item=faq}
            <tr>
                <td><a href="/index.php?module=faq&action=edit_form&dashboard=1&id={$faq.id}">{$faq.question}</a></td>
                <td class="tableActs">
                    <a href="/index.php?module=faq&action=edit_form&dashboard=1&id={$faq.id}" class="tablectrl_small bDefault tipS" title="Редактировать"><span class="iconb" data-icon="&#xe1db;"></span></a>
                    <a href="/index.php?module=faq&action=delete&dashboard=1&id={$faq.id}" class="tablectrl_small bDefault tipS removeButton" title="Удалить" data-id="{$faq.id}"><span class="iconb" data-icon="&#xe136;"></span></a>
                </td>
            </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
{/block}