{extends 'index.tpl'}

{block name="content"}
    <script type="text/javascript">
        var pm_message_folder = "{$smarty.get.messageFolder}";
        var pm_inbox_msg1 = "{"Сообщение успешно удалено. Сейчас вы будете перемещены"|gettext}";
        $(function(){
            $('#pm_delete_button').click(function(e){
                var message_ids = []
                message_ids.push($(this).data('messageid'))
                $.ajax({
                    type: "POST",
                    url: '/?module=pm_ajax&is_ajax=1',
                    cache: false,
                    dataType: "json",
                    data: {
                        action: 'delete',
                        message_folder: pm_message_folder,
                        message_ids: message_ids
                    },
                    success: function(d){
                        if('error' in d){
                            $('#pm_delete_button_message_block').text(d['message']).css('color', 'red')
                        } else if('success' in d){
                            $('#pm_delete_button_message_block').text(pm_inbox_msg1).css('color', 'green')
                            setTimeout(function(){
                                window.location.href = '/?module=user_pm_'+pm_message_folder
                            }, 2000)
                        }
                    },
                    error: function(jqXHR){

                    }
                })
            })
        })
    </script>
    <div class="content page-message">
        <h2>{if $smarty.get.messageFolder == 'inbox'}{"Входящее сообщение"|gettext}{else}{"Исходящее сообщение"|gettext}{/if}</h2>

        <div class="message">
            <div class="header">
                <div class="sender">{$message.subject}</div>
                <div class="date">{$message.addTime}</div>
            </div>
            <div class="body">
                <p>{$message.message}</p>
            </div>
        </div>
        {if $smarty.get.messageFolder == 'inbox'}<a href="#" class="button" id="pm_reply_button">{"Ответить"|gettext}</a>{/if}
        <a href="#" class="button pm_delete_button" data-messageid="{$message.id}" id="pm_delete_button">{"Удалить это сообщение"|gettext}</a>
        <span style="color: red;margin-left: 5px;" id="pm_delete_button_message_block"></span>
    </div>
    {if $smarty.get.messageFolder == 'inbox'}
    <div class="dialog dialog-pm-create" title="{"Ответить"|gettext}">
        <div class="place-holder-message"></div>
        <input type="text" class="input-text" value="{"Кому"|gettext}: {$message.to_string}" disabled="disabled"/>
        <input name="email" type="text" class="input-text" placeholder="{"Заголовок сообщения"|gettext}" id="pm-create-subject" value="{if !empty($message.subject)}RE: {$message.subject}{/if}" />
        <textarea  name="message" class="input-textarea" placeholder="{"Текст сообщения"|gettext}" id="pm-create-message"></textarea>
        <input type="hidden" value="{$message.from}" id="pm-create-toid" />
        <div class="line"></div>
        <input type="button" value="{"Отправить"|gettext}" class="submit" id="pm-create-submit" />
    </div>
    {/if}
{/block}