{extends "_dashboard/index.tpl"}

{block name="pageTitle"}<span class="icon-user-2"></span>FAQ{/block}

{block name="breadcrumbs"}<li><a href="/?module=dashboard&dashboard=1">Панель управления</a></li><li><a href="/?module=faq&dashboard=1">FAQ</a></li><li class="current"><a href="#">Редактирование</a></li>{/block}

{block name="content"}
    <div class="fluid">
        <form id="usualValidate" class="main" method="post" action="/index.php?module=faq&action=update&dashboard=1" novalidate="novalidate">
            <fieldset>
                <div class="widget">
                    <div class="whead"><h6>Редактирование</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>Вопрос:<span class="req">*</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="question"></div><div class="clear"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Ответ:<span class="req">*</span></label></div>
                        <div class="grid9"><textarea rows="8" cols="" name="answer" class="auto" class="required"></textarea></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow"><input type="submit" value="Добавить" class="buttonM bBlue formSubmit"><div class="clear"></div></div>
                    <div class="clear"></div>
                </div>
            </fieldset>
            <input type="hidden" name="action" value="insert" />
        </form>
    </div>
{/block}