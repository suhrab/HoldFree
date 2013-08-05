{extends 'index.tpl'}

{block name="content"}
    <script type="text/javascript" src="{$_template}/js/noty/jquery.noty.js"></script>
    <script type="text/javascript" src="{$_template}/js/noty/layouts/topRight.js"></script>
    <script type="text/javascript" src="{$_template}/js/noty/themes/default.js"></script>

    <script type="text/javascript" src="{$_template}/js/context-menu/jquery.contextMenu.js"></script>
    <script type="text/javascript" src="{$_template}/js/context-menu/jquery.ui.position.js"></script>
    <link type="text/css" href="{$_template}/js/context-menu/jquery.contextMenu.css" rel="stylesheet" />

    <script type="text/javascript" src="{$_dashboard}/js/plugins/uploader/plupload.js"></script>
    <script type="text/javascript" src="{$_dashboard}/js/plugins/uploader/plupload.html4.js"></script>
    <script type="text/javascript" src="{$_dashboard}/js/plugins/uploader/plupload.html5.js"></script>

    <script type="text/javascript" src="{$_template}/js/plupload/plupload.js"></script>
    <script type="text/javascript" src="{$_template}/js/plupload/plupload.flash.js"></script>
    <script type="text/javascript" src="{$_template}/js/plupload/plupload.html4.js"></script>
    <script type="text/javascript" src="{$_template}/js/plupload/plupload.html5.js"></script>

    <script type="text/javascript">

        function getBytesWithUnit (bytes)
        {
            if( isNaN( bytes ) ){ return; }
            var units = [ ' bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB' ];
            var amountOf2s = Math.floor( Math.log( +bytes )/Math.log(2) );
            if( amountOf2s < 1 ){
                amountOf2s = 0;
            }
            var i = Math.floor( amountOf2s / 10 );
            bytes = +bytes / Math.pow( 2, 10*i );

            if( bytes.toString().length > bytes.toFixed(1).toString().length ){
                bytes = bytes.toFixed(1);
            }
            return bytes + units[i];
        }

        var filesToMonitor = { };
        function AddFileToMonitor(dbFileId, $fileQueueRow){
            filesToMonitor[dbFileId] = $fileQueueRow;
        }

        function RemoveFileFromMonitor(dbFileId){
            if(filesToMonitor.hasOwnProperty(dbFileId))
                delete filesToMonitor[dbFileId]
        }

        $(function()
        {
            $('#FileQueue tr.dbRow').each(function(i, el){
                var dbFileId = $(el).data('id')
                AddFileToMonitor(dbFileId, $(el))
            })

            function PollFilesToMonitor(){
                var fileIds = []
                for(var key in filesToMonitor){
                    fileIds.push(key)
                }
                if (fileIds.length) {
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/?module=file_status&is_ajax=1',
                        data: {
                            fileIds: fileIds
                        },
                        dataType: 'json',
                        success: function(r){
                            $(r).each(function(i, dbFileInfo){
                                if(!filesToMonitor.hasOwnProperty(dbFileInfo['id']))
                                    return;
                                var $fileQueueRow = filesToMonitor[dbFileInfo['id']];
                                if(dbFileInfo['status_message'] != ''){
                                    $fileQueueRow.find(" td.status").text(dbFileInfo['status_message']);
                                } else {
                                    if(dbFileInfo['complete_status'] != 0){
                                        $fileQueueRow.find('td.status').html('<div class="progress-bar"><div class="progress-status-yellow" style="width: 0%"></div></div><div class="progress-text">{"Конвертация:"|gettext} <i class="progress-percent">0</i>%</div>');
                                        $fileQueueRow.find('td.status').find("div.progress-status-yellow").css("width", dbFileInfo['complete_status'] + "%");
                                        $fileQueueRow.find('i.progress-percent').text(dbFileInfo['complete_status']);

                                        if(dbFileInfo['complete_status'] == 100){
                                            RemoveFileFromMonitor(dbFileInfo['id'])
                                            $fileQueueRow.css('opacity', '0.6')

                                            if(0==0){
                                                var convertedFileUrls = jQuery.parseJSON(dbFileInfo['files'])
                                                var convertedFileLinks = $('<div></div>')
                                                for(var size in convertedFileUrls){
                                                    convertedFileLinks.append('<a href="'+convertedFileUrls[size]+'"><img src="http://holdfree.com/template/default/img/icon_24_chain.png" width="24" height="24" alt="'+size+'" title="'+size+'"></a>')
                                                    break;
                                                }

                                                $('<tr><td><a href="#" class="file">'+dbFileInfo['user_defined_name']+'</a></td><td>'+dbFileInfo['file_size']+'</td><td>'+dbFileInfo['created']+'</td><td>'+convertedFileLinks[0].outerHTML+'</td></tr>').appendTo('#fileList')
                                            }
                                        }
                                    }
                                }
                            })

                            setTimeout(PollFilesToMonitor, 2*1000);
                        },
                        error: function(jqXHR){
                            setTimeout(PollFilesToMonitor, 2*1000);
                        }
                    });
                }
            }

            PollFilesToMonitor();

            var uploader = new plupload.Uploader({
                runtimes : 'html5,flash',
                browse_button : 'UploadFileButton',
                container : 'FileQueueContainer',
                max_file_size : '2gb',
                url : '/?module=upload&is_ajax=1',
                flash_swf_url       : '{$_template}/js/plupload/plupload.flash.swf',
                filters : [
                    { title : "Video files", extensions : "mp4,avi,mkv" }
                ]
            });

            uploader.bind('Init', function(up, params) {
                $('#filelist').html("<div>Current runtime: " + params.runtime + "</div>");
            });

            $('#uploadfiles').click(function(e) {
                uploader.start();
                e.preventDefault();
            });

            uploader.init();

            uploader.bind('FilesAdded', function(up, files) {
                $('#FileQueueContainer').show()
                $.each(files, function(i, file) {
                    $('<tr id="'+file.id+'"><td>'+file.name+'</td><td>'+getBytesWithUnit(file.size)+'</td><td class="status">В очереди на загрузку</td><td class="uploadSpeed"></td></tr>').appendTo('#FileQueue');
                });

                up.refresh(); // Reposition Flash/Silverlight
                up.start();
            });

            uploader.bind('UploadProgress', function(up, file) {
                var $fileRow = $('#' + file.id)
                $fileRow.find(" td.uploadSpeed").text(getBytesWithUnit(up.total.bytesPerSec) + "/s");
                $fileRow.find(" td.status").html('<div class="progress-bar"><div class="progress-status-green" style="width: '+ file.percent +'%"></div></div><div class="progress-text">Загружено: <i class="progress-percent">'+ file.percent +'</i>%</div>');
            });

            uploader.bind('FileUploaded', function(up, file, response) {
                var r = jQuery.parseJSON(response.response);
                var $fileRow = $('#' + file.id);
                AddFileToMonitor(r.id, $fileRow);
                $fileRow.find(" td.uploadSpeed").text("");
                $fileRow.find(" td.status").html("В очереди на конвертацию");
            });
        });
    </script>

    <div class="content page-manager">
        <style type="text/css">
            .swfupload {
                display: inline-block;
                padding: 5px 20px 7px 20px;
                font: bold 14px Roboto, arial, helvetica, sans-serif;
                text-decoration: none;
                background-color: #293640;
                border-radius: 2px;
            }
        </style>

        <h2>Менеджер видео</h2>

        <a href="javascript:;" class="button" id="UploadFileButton">Загрузить файл</a>
        <a href="javascript:;" class="button" id="newDir">Создать папку</a>

        <div class="manager">
            <div class="dir-tree">
                <ul>
                    <li class="dir-root">
                        <a href="/" class="parent">Менеджер файлов</a>

                        <ul>
                            {if count($dirs)}
                                {foreach from=$dirs item=dir}
                                    <li class="dir"><a href="/index.php?dir={$dir.id}">{$dir.user_defined_name}</a></li>
                                {/foreach}
                            {/if}
                        </ul>

                    </li>

                    <li class="dir-trash">
                        <a href="/index.php?trash=1" class="parent">Удаленные файлы</a>
                    </li>
                </ul>
            </div>

            <div class="file-panel">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td>Имя</td>
                            <td width="140">Размер</td>
                            <td width="140">Дата</td>
                            <td>URL</td>
                        </tr>
                    </thead>
                    <tbody id="fileList">
                        {foreach from=$files_and_dirs item=file}
                            {if $file.type == 'dir'}
                            <tr id="row_dir_{$file.id}">
                                <td colspan="2"><a href="/index.php?dir={$file.id}" class="dir" id="dir_{$file.id}" data-id="{$file.id}">{$file.user_defined_name}</a></td>
                                <td>{$file.created}</td>
                                <td></td>
                            </tr>
                            {elseif $file.type == 'file'}
                                <tr>
                                    <td><a href="#" class="file" title="{$file.user_defined_name}">{$file.cut_user_defined_name}</a></td>
                                    <td>{$file.file_size}</td>
                                    <td>{$file.created}</td>

                                    <td>
                                        {if !empty($file.files)}
                                            {assign 'convertedFileUrls' $file.files|json_decode:true}
                                            {foreach $convertedFileUrls as $size => $convertedFileUrl}
                                                <a href="{$convertedFileUrl}"><img src="{$_template}/img/icon_24_chain.png" width="24" height="24" alt="{$size}" title="{$size}" /></a>
                                            {/foreach}
                                        {/if}
                                    </td>
                                </tr>
                            {/if}
                        {/foreach}

                        {if count($file) == 0}
                            <tr>
                                <td colspan="4" class="dark-blue" align="center">В данной директории файлов нет.</td>
                            </tr>
                        {/if}
                    </tbody>
                </table>
            </div>

            <div class="cleaner"></div>
            <style type="text/css">
                .progress-bar {
                    width: 90%;
                    height: 6px;
                    border: solid 1px #183e5e;
                    padding: 1px;
                    position: relative;
                }
                .progress-status-yellow {
                    background-color: #FFCC00;
                    height: 6px;
                }
                .progress-status-green {
                    background-color: #006400;
                    height: 6px;
                }
                .progress-text {
                    width: 90%;
                    text-align: center;
                    font-size: 11px;
                }
            </style>
            <div class="loading-panel" id="FileQueueContainer" style="{if empty($filesInProgress)}display:none;{/if}">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <td>Имя</td>
                        <td width="140">Размер</td>
                        <td width="200">Статус</td>
                        <td width="140">Скорость</td>
                    </tr>
                    </thead>
                    <tbody id="FileQueue">
                    {foreach $filesInProgress as $file}
                        <tr class="dbRow" data-id="{$file.id}">
                            <td>{$file.user_defined_name}</td>
                            <td>{$file.file_size}</td>
                            <td class="status"></td>
                            <td class="uploadSpeed"></td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dialog dialog-dir" title="Создать новую папку">
            <div class="place-holder-message"></div>
            <form action="index.php?module=dir&action=add" method="post" id="formDir">
                <input name="dir_name" type="text" class="input-text" placeholder="Имя новой папки" />
                <input type="submit" value="Создать папку" class="submit" />
            </form>
        </div>

        <div class="dialog dialog-dir-rename" title="Переименовать папку">
            <div class="place-holder-message"></div>
            <form action="index.php?module=dir&action=rename" method="post" id="formDirRename">
                <input name="dir_name" type="text" class="input-text" placeholder="Имя папки" />
                <input name="id" type="hidden" value="0" id="dir_id" />
                <input type="submit" value="Переименовать" class="submit" />
            </form>
        </div>
    </div>
{/block}