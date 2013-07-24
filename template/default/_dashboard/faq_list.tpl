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
            <tr>
                <td>Row 1</td>
                <td>Row 2</td>
            </tr>
            </tbody>
        </table>
    </div>
{/block}