{extends "_dashboard/index.tpl"}

{block name="pageTitle"}<span class="icon-settings"></span>Настройки{/block}

{block name="breadcrumbs"}<li><a href="/?module=dashboard&dashboard=1">Панель управления</a></li><li class="current"><a href="/?module=option&dashboard=1">Настройки</a></li>{/block}

{block name="content"}
    <div class="fluid">
        <form id="usualValidate" class="main" method="post" action="/index.php?module=option&dashboard=1" novalidate="novalidate">
            <fieldset>
                <div class="widget">
                    <div class="whead"><h6>Общие настройки</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>Название сайта:<span class="note">Например: "Моя домашняя страница"</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="homepage_title" value="{$config.homepage_title}"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Имя основного домена:<span class="note">Имя основного домена на котором располагается ваш сайт.</span></label></div>
                        <div class="grid9"><input type="text" disabled="disabled" name="site_url" value="{$_url}"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Используемая кодировка на сайте:</label></div>
                        <div class="grid9"><input type="text" class="required" name="charset" value="{$config.charset}"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Описание (Description) сайта:<span class="note">Краткое описание, не более 200 символов</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="homepage_description" value="{$config.homepage_description}"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Ключевые слова (Keywords) для сайта:<span class="note">Введите через запятую основные ключевые слова для вашего сайта</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="homepage_keywords" value="{$config.homepage_keywords}"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Шаблон сайта:<span class="note">Укажите шаблон, который будет использоваться на сайте</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="template" value="{$config.template}"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Выключить сайт:<span class="note">Перевести сайт в состояние offline, для проведения технических работ</span></label></div>
                        <div class="grid9 on_off">
                            <div class="floatL mr10"><input type="checkbox" id="check20" {if $config.site_offline}checked="checked"{/if} name="site_offline" /></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Причина отключения сайта:<span class="note">Сообщение для отображения в режиме отключенного сайта</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="offline_reason" value="{$config.offline_reason}"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Включить gzip сжатие:<span class="note">Позволяет сжать выходной код, и тем самым сэкономить на траффике</span></label></div>
                        <div class="grid9 on_off">
                            <div class="floatL mr10"><input type="checkbox" id="check20" {if $config.gzip}checked="checked"{/if} name="gzip" /></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Максимально допустимый размер файла для гостей:<span class="note">Размер файла указывать в Мегабайтах</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="max_allowed_file_size_guest" value="{$config.max_allowed_file_size_guest}"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Максимально допустимый размер файла для зарегестрированных:<span class="note">Размер файла указывать в Мегабайтах</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="max_allowed_file_size_user" value="{$config.max_allowed_file_size_user}"></div><div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Как долго хранить файлы гостей:<span class="note">Укажите количество дней. 0 - бесконечно.</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="file_keep_guest" value="{$config.file_keep_guest}"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Как долго хранить файлы пользователей:<span class="note">Укажите количество дней. 0 - бесконечно.</span></label></div>
                        <div class="grid9"><input type="text" class="required" name="file_keep_user" value="{$config.file_keep_user}"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Фильтр почтовых провайдеров:<span class="note">Укажите email провайдера. Например: mail.ru</span></label></div>
                        <div class="grid9"><input type="text" name="email_filter" class="tags" id="tags" value="{$config.email_filter}"></div>
                        <div class="clear"></div>
                    </div>

                    <div class="formRow"><input type="submit" value="Сохранить" class="buttonM bBlack formSubmit"><div class="clear"></div></div>
                    <div class="clear"></div>
                </div>
            </fieldset>
            <input type="hidden" name="action" value="update" />
        </form>
    </div>
{/block}