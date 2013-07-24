{extends "_dashboard/index.tpl"}

{block name="pageTitle"}<span class="icon-settings"></span>Управление базой данных{/block}

{block name="breadcrumbs"}<li><a href="/?module=dashboard&dashboard=1">Панель управления</a></li><li class="current"><a href="/?module=option&dashboard=1">Настройки</a></li>{/block}

{block name="content"}
    <div class="fluid">
        <form id="dbOptimize" class="main" method="post" action="/index.php?module=database&dashboard=1&is_ajax=1" novalidate="novalidate">
            <fieldset>
                <div class="widget">
                    <div class="whead"><h6>Настройка и оптимизация базы данных</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3">
                            <label><span class="note">Вы можете произвести оптимизацию базы данных, тем самым <br /> будет сэкономлено немного места на диске, а также ускорена работа базы данных. <br /> Рекомендуется использовать данную функцию минимум один раз в неделю.</span>
                            </label>
                        </div>
                        <div class="grid9">
                            <input type="radio" id="radio1" name="action" checked="checked" value="optimize" />
                            <label for="radio1"  class="mr20">Оптимизация базы данных</label>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3">
                            <label><span class="note">При неожиданной остановке MySQL сервера, во время выполнения каких-либо <br /> действий, может произойти повреждение структуры таблиц, использование <br /> этой функции поможет решить вам эту проблему.</span>
                            </label>
                        </div>
                        <div class="grid9">
                            <input type="radio" id="radio2" name="action" value="repair" />
                            <label for="radio2"  class="mr20">Ремонт базы данных</label>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow"><input type="submit" value="Выполнить" class="buttonL bBlue formSubmit" data-loading-text="Загрузка" /><div class="clear"></div></div>
                    <div class="clear"></div>
                </div>
            </fieldset>
        </form>
    </div>

    <div class="fluid">
        <form id="dbDump" class="main" method="post" action="/index.php?module=database&dashboard=1" novalidate="novalidate">
            <fieldset>
                <div class="widget">
                    <div class="whead"><h6>Сохранение резервной копии</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3">
                            <label>Использовать gzip метод сжатия базы данных:</label>
                        </div>
                        <div class="grid9 on_off">
                            <div class="floatL mr10"><input type="checkbox" id="check20" checked="checked" name="gzip" /></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow"><input type="submit" value="Выполнить" class="buttonL bBrown formSubmit" data-loading-text="Загрузка" /><div class="clear"></div></div>
                    <div class="clear"></div>
                </div>
            </fieldset>
            <input type="hidden" name="action" value="dump" />
        </form>
    </div>

    <div class="fluid">
        <form id="dbLoad" class="main" method="post" action="/index.php?module=database&dashboard=1" novalidate="novalidate">
            <fieldset>
                <div class="widget">
                    <div class="whead"><h6>Загрузка резервной копии с диска</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3">
                            <label>Выберите резервную копию базы данных:</label>
                        </div>
                        <div class="grid9">
                            <select name="select2" >
                                <option value="opt1">Выберите, пожалуйста</option>
                                <option value="opt2">Option 2</option>
                                <option value="opt3">Option 3</option>
                                <option value="opt4">Option 4</option>
                                <option value="opt5">Option 5</option>
                                <option value="opt6">Option 6</option>
                                <option value="opt7">Option 7</option>
                                <option value="opt8">Option 8</option>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow"><input type="submit" value="Выполнить" class="buttonL bGreen formSubmit" data-loading-text="Загрузка" /><div class="clear"></div></div>
                    <div class="clear"></div>
                </div>
            </fieldset>
            <input type="hidden" name="action" value="load" />
        </form>
    </div>
{/block}