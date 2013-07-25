{extends 'index.tpl'}
{block name="content"}
    <script type="text/javascript">
        var pm_inbox_msg1 = "{"Выберите хотябы одно сообщение"|gettext}";
        var pm_inbox_msg2 = "{"Сообщения успешно удалены"|gettext}";
        $(function(){
            $('#pm_delete_button').click(function(e){
                var message_ids = []
                $('#pm_inbox_block input.messageCheckbox:checked').each(function(i, el){
                    message_ids.push($(el).data('id'))
                })
                if(message_ids.length == 0){
                    $('#pm_delete_button_message_block').text(pm_inbox_msg1).css('color', 'red')
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: '/?module=pm_ajax&is_ajax=1',
                    cache: false,
                    dataType: "json",
                    data: {
                        action: 'delete',
                        message_folder: 'outbox',
                        message_ids: message_ids
                    },
                    success: function(d){
                        if('error' in d){
                            $('#pm_delete_button_message_block').text(d['message']).css('color', 'red')
                        } else if('success' in d){
                            for(var i in message_ids){
                                $('#msg_row_'+message_ids[i]).remove()
                            }
                            $('#pm_delete_button_message_block').text(pm_inbox_msg2).css('color', 'green')
                        }
                    },
                    error: function(jqXHR){

                    }
                })
            })
        })
    </script>
    <div class="content page-messages" id="pm_inbox_block">
        <h2>{"Исходящие сообщения"|gettext}</h2>
        <a href="#" class="button" id="pm_delete_button">{"Удалить"|gettext}</a>
        <span style="color: red;margin-left: 5px;" id="pm_delete_button_message_block"></span>
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
            <thead>
                <tr>
                    <td style="width: 20px;"></td>
                    <td width="230">{"Получатель"|gettext}</td>
                    <td>{"Заголовок"|gettext}</td>
                    <td width="180">{"Дата"|gettext}</td>
                </tr>
            </thead>
            <tbody>
                {foreach $outbox_messages as $message}
                    <tr id="msg_row_{$message.id}">
                        <td><input type="checkbox" class="messageCheckbox" data-id="{$message.id}"></td>
                        <td>{$message.to_string}</td>
                        <td class="strong"><a href="/?module=user_pm_show&messageFolder=outbox&messageId={$message.id}">{$message.subject}</a></td>
                        <td>{$message.addTime}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
{/block}