{extends 'index.tpl'}

{block name="content"}
    {if isset($_user.id)}
    <script type="text/javascript" src="{$_dashboard}/js/plugins/uploader/plupload.js"></script>
    <script type="text/javascript" src="{$_dashboard}/js/plugins/uploader/plupload.html4.js"></script>
    <script type="text/javascript" src="{$_dashboard}/js/plugins/uploader/plupload.html5.js"></script>

        <script type="text/javascript" src="{$_template}/js/plupload/plupload.js"></script>
        <script type="text/javascript" src="{$_template}/js/plupload/plupload.flash.js"></script>
        <script type="text/javascript" src="{$_template}/js/plupload/plupload.html4.js"></script>
        <script type="text/javascript" src="{$_template}/js/plupload/plupload.html5.js"></script>

        <script type="text/javascript">
        var profileUpdatedMessage = "{"Профиль успешно обновлен, через пару мгновений страница будет обновлена."|gettext}";
        $(document).ready(function()
        {
            var uploader = new plupload.Uploader({
                runtimes            : 'html5,flash,html4',
                browse_button       : 'photo',
                max_file_size       : '3mb',
                url                 : '/index.php?module=user&action=upload_photo&is_ajax=1',
                flash_swf_url       : '{$_template}/js/plupload/plupload.flash.swf',
                filters             : [ { title : "Image files", extensions : "jpg,gif,png" } ],
                multi_selection     : false,
                multipart_params    : { "uid" : "{$_user['id']}" }
            });

            uploader.init();

            uploader.bind('FilesAdded', function(up, files) {
                uploader.start();
            });

            uploader.bind('UploadComplete', function(up, files) {
                location.reload();
            });

            var dialogOption = {
                autoOpen    : false,
                width       : 480,
                resizable   : false,
                modal       : true,
                dialogClass : 'dialog'
            };

            var editProfileDialog = $('.dialog-edit-profile').dialog(dialogOption);

            $('#edit-profile').click(function() {
                editProfileDialog.dialog('open');
            });

            $('#formEditProfile').on('submit', function()
            {
                var formData = $("#formEditProfile").serialize();

                $.post('index.php?module=user&action=edit&is_ajax=1', formData, function(response)
                {
                    console.log(response);
                    if (response.error) {
                        $('.dialog-edit-profile .place-holder-message').html('<div class="error-message">'+ response.message +'</div>');
                        $('#formEditProfile').find('.submit').effect("shake", { distance:10, times:2 }, 700);
                    }

                    if(response.success) {
                        $('.dialog-edit-profile .place-holder-message').html('<div class="success-message">'+profileUpdatedMessage+'</div>');

                        setTimeout(function () {
                            location.reload();
                        }, 2500);
                    }

                }, 'json');

                return false;
            });
        });
    </script>
    {/if}

    <div class="content page-profile">
        <h2>{"Личные данные"|gettext}</h2>

        {if isset($_user.id) && isset($smarty.get.id) && $_user.id == $smarty.get.id}
            <a href="javascript:;" class="button" id="edit-profile">{"Редактировать профиль"|gettext}</a>
            <a href="/?module=user_pm_inbox" class="button">{"Входящие сообщения"|gettext}</a>
            <a href="/?module=user_pm_outbox" class="button">{"Исходящие сообщения"|gettext}</a>
        {/if}

        {if isset($smarty.get.id) && is_numeric($smarty.get.id) && $smarty.get.id != $_user.id}<a href="javascript:" class="button" id="pm_new_message_button" data-toid="{$smarty.get.id}">{"Написать сообщение"|gettext}</a>{/if}

        <div class="profile">
            <div class="col-avatar" id="photo" style="background: url('{$_url}/upload/avatar/{$user_data.avatar}') no-repeat;"></div>

            <ul class="col-title">
                <li>{"Имя"|gettext}:</li>
                <li>{"Фамилия"|gettext}:</li>
                <li>{"Страна"|gettext}:</li>
                <li>{"Email"|gettext}:</li>
                <li>{"Файлов"|gettext}:</li>
            </ul>

            <ul class="col-value">
                <li>{$user_data.first_name|default:"---"}</li>
                <li>{$user_data.last_name|default:"---"}</li>
                <li>{$user_data.country_name}</li>
                <li><a href="mailto:{$user_data.email}">{$user_data.email}</a></li>
                <li>{$user_data.files}</li>
            </ul>

            <ul class="col-data">
                <li>{"Дата регистрации"|gettext} <p>{$user_data.reg_date}</p></li>
                <li>{"Последнее посещение"|gettext} <p>{$user_data.last_signin}</p></li>
            </ul>
        </div>
    </div>

    {if isset($_user.id)}
    <div class="dialog dialog-edit-profile" title="{"Редактировать профиль"|gettext}">
        <div class="place-holder-message"></div>
        <form action="index.php?module=user&action=edit" method="post" id="formEditProfile">
            <input name="first_name" type="text" class="input-text" placeholder="{"Введите Ваше имя"|gettext}" value="{$user_data.first_name}" />
            <input name="last_name" type="text" class="input-text" placeholder="{"Введите Вашу фимилию"|gettext}" value="{$user_data.last_name}" />

            <select name="country" class="select2">
                <option value="0">{"Выберите страну"|gettext}</option>
                {foreach from=$countries item=country}
                    <option value="{$country.id}" {if $user_data.country == $country.id}selected="selected"{/if}>{$country.name}</option>
                {/foreach}
            </select>

            <input name="email" type="text" class="input-text" placeholder="{"Введите Ваш email"|gettext}" value="{$user_data.email}" />

            <div class="line"></div>
            <input type="submit" value="{"Сохранить"|gettext}" class="submit">
            <input name="id" value="{$user_data.id}" type="hidden" />
        </form>
    </div>
    {/if}
    <div class="dialog dialog-pm-create" title="{"Написать сообщение"|gettext}">
        <div class="place-holder-message"></div>
        <input type="text" class="input-text" value="{"Кому"|gettext}: {$user_data.first_name} {$user_data.last_name}" disabled="disabled"/>
        <input name="email" type="text" class="input-text" placeholder="{"Заголовок сообщения"|gettext}" id="pm-create-subject" />
        <textarea  name="message" class="input-textarea" placeholder="{"Текст сообщения"|gettext}" id="pm-create-message"></textarea>
        <input type="hidden" value="{$user_data.id}" id="pm-create-toid" />
        <div class="line"></div>
        <input type="button" value="{"Отправить"|gettext}" class="submit" id="pm-create-submit" />
    </div>
{/block}