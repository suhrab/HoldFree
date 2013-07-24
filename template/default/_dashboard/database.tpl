{extends "_dashboard/index.tpl"}

{block name="pageTitle"}<span class="icon-settings"></span>Управление базой данных{/block}

{block name="breadcrumbs"}<li><a href="/?module=dashboard&dashboard=1">Панель управления</a></li><li class="current"><a href="/?module=option&dashboard=1">Настройки</a></li>{/block}

{block name="content"}
    <div class="fluid">
        <form id="usualValidate" class="main" method="post" action="/index.php?module=option&dashboard=1" novalidate="novalidate">
            <fieldset>
                <div class="widget">
                    <div class="whead"><h6>Настройка и оптимизация базы данных</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3">
                            <label><span class="note">Вы можете произвести оптимизацию базы данных, тем самым <br /> будет сэкономлено немного места на диске, а также ускорена работа базы данных. <br /> Рекомендуется использовать данную функцию минимум один раз в неделю.</span>
                            </label>
                        </div>
                        <div class="grid9">
                            <input type="radio" id="radio2" name="optimize" checked="checked" />
                            <label for="radio2"  class="mr20">Оптимизация базы данных</label>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3">
                            <label><span class="note">При неожиданной остановке MySQL сервера, во время выполнения каких-либо <br /> действий, может произойти повреждение структуры таблиц, использование <br /> этой функции поможет решить вам эту проблему.</span>
                            </label>
                        </div>
                        <div class="grid9">
                            <input type="radio" id="radio2" name="fix" />
                            <label for="radio2"  class="mr20">Ремонт базы данных</label>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow"><input type="submit" value="Выполнить" class="buttonL bBlue formSubmit"><div class="clear"></div></div>
                    <div class="clear"></div>
                </div>
            </fieldset>
            <input type="hidden" name="action" value="update" />
        </form>
    </div>
{/block}