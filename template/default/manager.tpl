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

    <script type="text/javascript" src="{$_template}/js/jquery.simplemodal.1.4.4.min.js"></script>

    <script type="text/javascript" src="{$_template}/js/manager.js"></script>

    <style type="text/css">
        body.NotLoggedIn {
            background: #101a22 url("/template/default/img/bg_index.png") no-repeat top center;
        }
        #UploadButtonNotLoggedIn {
            display: block;
            width:  326px;
            height: 89px;
            margin: 300px auto 0 auto;
            text-align: center;
            font: bold 26px Arial, Helvetica, sans-serif;
            text-decoration: none;
            color: #16232f;
            padding-top: 55px;
            background: url("/template/default/img/button_upload.png") no-repeat top center;
        }
        #UploadButtonNotLoggedIn:hover {
            background: url("/template/default/img/button_upload.png") no-repeat bottom center;
        }
        #BonusInfoButton {
            display: block;
            width:  400px;
            text-align: center;
            font: bold 17px Arial, Helvetica, sans-serif;
            color: #FFCC00;
            margin: 50px auto 0 auto;
            border:  dashed 3px #4eb9ff;
            padding:  15px 0;
            border-radius: 30px;
            text-decoration: none;
        }
        #BonusInfoButton:hover {
            color: #FFFFFF;
        }
    </style>

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

        var fileMonitorPollInterval = 5 * 1000;
        var isLoggedIn = {if isset($_user.id)}true{else}false{/if};
        $(function()
        {
            if(!isLoggedIn){
                $('body').addClass('NotLoggedIn')
            }

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
                                        $fileQueueRow.find('td.status').html('<div class="progress-bar"><div class="progress-status-yellow" style="width: 0%"></div></div><div class="progress-text">{"Конвертация:"|gettext|escape} <i class="progress-percent">0</i>%</div>');
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

                                                loadFiles(cwd);
                                            }
                                        }
                                    }
                                }
                            })

                            setTimeout(PollFilesToMonitor, fileMonitorPollInterval);
                        },
                        error: function(jqXHR){
                            setTimeout(PollFilesToMonitor, fileMonitorPollInterval);
                        }
                    });
                }
                else {
                    setTimeout(PollFilesToMonitor, fileMonitorPollInterval);
                }
            }

            PollFilesToMonitor();

            var uploader = new plupload.Uploader({
                runtimes : 'html5,flash',
                browse_button : '0',
                container : 'FileQueueContainer',
                max_file_size : '2gb',
                url : '/?module=upload&is_ajax=1',
                flash_swf_url       : '{$_template}/js/plupload/plupload.flash.swf',
                filters : [
                    { title : "Video files", extensions : "mp4,avi,mkv,flv" }
                ]
            });

            $('.pickfiles').mouseenter(function(e)
            {
                var id = $(this).attr('id')
                if(uploader.settings.browse_button == id){
                    return;
                }

                uploader.destroy();

                uploader.settings.browse_button = id;


                uploader.bind('Init', function(up, params) {
                    //$('#FileQueueContainer').html("<div>Current runtime: " + params.runtime + "</div>");
                });

                $('#uploadfiles').click(function(e) {
                    uploader.start();
                    e.preventDefault();
                });

                uploader.init();

                uploader.bind('FilesAdded', function(up, files) {
                    $('#FileQueueContainer').show()
                    $.each(files, function(i, file) {
                        $('<tr id="'+file.id+'"><td>'+file.name+'</td><td>'+getBytesWithUnit(file.size)+'</td><td class="status">{"В очереди на загрузку"|gettext|escape}</td><td class="uploadSpeed"></td></tr>').appendTo('#FileQueue');
                    });

                    up.refresh(); // Reposition Flash/Silverlight
                    up.start();
                });

                uploader.bind('UploadProgress', function(up, file) {
                    var $fileRow = $('#' + file.id)
                    $fileRow.find(" td.uploadSpeed").text(getBytesWithUnit(up.total.bytesPerSec) + "/s");
                    $fileRow.find(" td.status").html('<div class="progress-bar"><div class="progress-status-green" style="width: '+ file.percent +'%"></div></div><div class="progress-text">{"Загружено"|gettext|escape}: <i class="progress-percent">'+ file.percent +'</i>%</div>');
                });

                uploader.bind('FileUploaded', function(up, file, response) {
                    var r = jQuery.parseJSON(response.response);
                    var $fileRow = $('#' + file.id);
                    AddFileToMonitor(r.id, $fileRow);
                    $fileRow.find(" td.uploadSpeed").text("");
                    $fileRow.find(" td.status").html("{"В очереди на конвертацию"|gettext|escape}");
                });
            });

            $('#UploadButtonNotLoggedIn').click(function(e){
                $('body').removeClass('NotLoggedIn')
                $('#content-NotLoggedIn').hide();
                $('#content-LoggedIn').show();
            })

            $('#BonusInfoButton').click(function(e){
                $("#RegisterBonusInfoDialog").modal({
                    opacity: 80,
                    overlayCss: { "background-color":"#182632" },
                    containerCss: { "background-color": "transparent" },
                    modal: true
                });
            })

            $('#BonusInfoRegisterButton').click(function(){
                $.modal.close();
                $("#signup").trigger('click');
            })
        });
    </script>
    <div class="content page-manager" id="content-LoggedIn" style="display: {if isset($_user.id)}block{else}none{/if};">
    <h2>{"Менеджер видео"|gettext|escape}</h2>

    <div class="buttons">
        <a href="javascript:;" class="button pickfiles" id="UploadFileButton">{"Загрузить файл"|gettext|escape}</a>
        <a href="javascript:;" class="button" id="newDir">{"Создать папку"|gettext|escape}</a>
    </div>

        <div class="manager">
            <div class="dir-tree">
                <ul>
                    <li class="dir-root">
                        <a href="javascript:;" class="parent dir" data-id="0">{"Менеджер файлов"|gettext|escape}</a>
                        <ul id="dirList"></ul>
                    </li>
                    <li class="dir-trash">
                        <a href="javascript:;" class="parent dir" data-id="-1">{"Удаленные файлы"|gettext|escape}</a>
                        <ul id="dirListTrash"></ul>
                    </li>
                </ul>
            </div>

            <div class="file-panel">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <td>{"Имя"|gettext|escape}</td>
                        <td width="100">{"Размер"|gettext|escape}</td>
                        <td width="50">URL</td>
                        <td width="100" class="align-center">{"Дата"|gettext|escape}</td>
                    </tr>
                    </thead>
                    <tbody id="fileList"></tbody>
                </table>
            </div>

            <div class="cleaner"></div>

            <div class="loading-panel" id="FileQueueContainer" style="{if empty($filesInProgress)}display:none;{/if}">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <td>{"Имя"|gettext|escape}</td>
                        <td width="140">{"Размер"|gettext|escape}</td>
                        <td width="200">{"Статус"|gettext|escape}</td>
                        <td width="140">{"Скорость"|gettext|escape}</td>
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

        <div class="dialog dialog-dir" title="{"Создать новую папку"|gettext|escape}">
            <div class="place-holder-message"></div>
            <form action="javascript:;" method="post" id="formDir">
                <input name="dir_name" type="text" class="input-text" placeholder="{"Имя новой папки"|gettext|escape}"/>
                <input type="hidden" name="parent" value="0" id="parentDirId" />
                <input type="submit" value="{"Создать папку"|gettext|escape}" class="submit"/>
            </form>
        </div>

        <div class="dialog dialog-dir-rename" title="{"Переименовать файл"|gettext|escape}">
            <div class="place-holder-message"></div>
            <form action="" method="post" id="formRename">
                <input name="name" type="text" class="input-text" placeholder="{"Имя"|gettext|escape}"/>
                <input name="id" type="hidden" value="0" id="id"/>
                <input type="submit" value="{"Переименовать"|gettext|escape}" class="submit"/>
            </form>
        </div>

        <div id="videoInfo">
            <object id="" type="application/x-shockwave-flash" data="{$_template}/js/uppod/uppod.swf" width="500" height="375">
                <param name="bgcolor" value="#ffffff" />
                <param name="allowFullScreen" value="true" />
                <param name="allowScriptAccess" value="always" />
                <param name="wmode" value="window" />
                <param name="movie" value="{$_template}/js/uppod/uppod.swf" />
                <param name="flashvars" value="" class="fileSrc" />
            </object>
            <div class="cleaner"></div>
            <label>{"HTML Код для вставки плеера в свой сайт"|gettext|escape}</label>
            <textarea name="embed_code">
                <object id="" type="application/x-shockwave-flash" data="http://holdfree.com/template/default/js/uppod/uppod.swf" width="500" height="375">
                    <param name="bgcolor" value="#ffffff">
                    <param name="allowFullScreen" value="true">
                    <param name="allowScriptAccess" value="always">
                    <param name="wmode" value="window">
                    <param name="movie" value="http://holdfree.com/template/default/js/uppod/uppod.swf">
                    <param name="flashvars" value="" class="fileSrc">
                </object>
            </textarea>
        </div>
    </div>

    <div class="content" id="content-NotLoggedIn" style="display: {if isset($_user.id)}none{else}block{/if};">
        <a href="javascript:;" class="pickfiles" id="UploadButtonNotLoggedIn">{"Загрузить"|gettext|escape}</a>
        <a href="javascript:;" id="BonusInfoButton">{"Узнать больше о бонусах регистрации"|gettext|escape}</a>
        <div style="display: none;background: transparent;position: relative;" id="RegisterBonusInfoDialog">
            <img src="{$_template}/img/bonus-popup.png">
            {* Заголовки 2 колонок *}
            <span style="position: absolute;top: 70px;left: 30px;color: #000;font: 900 25px Roboto, arial, helvetica, sans-serif;">{"Не зарегистрированный"|gettext|escape}</span>
            <span style="position: absolute;top: 20px;left: 415px;color: #000;font: 900 25px Roboto, arial, helvetica, sans-serif;">{"Зарегистрированный"|gettext|escape}</span>
            {* Левая колонка *}
            <span style="position: absolute;top: 145px;left: 65px;color: #000;font: 300 16px Roboto, arial, helvetica, sans-serif;">{"Максимальный объем файлов до 2гб"|gettext|escape}</span>
            <span style="position: absolute;top: 195px;left: 65px;color: #000;font: 300 16px Roboto, arial, helvetica, sans-serif;">{"Ограниченная скорость загрузки"|gettext|escape}</span>
            <span style="position: absolute;top: 245px;left: 65px;color: #000;font: 300 16px Roboto, arial, helvetica, sans-serif;">{"Большая очередь на конвертацию"|gettext|escape}</span>
            <span style="position: absolute;top: 295px;left: 65px;color: #000;font: 300 16px Roboto, arial, helvetica, sans-serif;">{"Срок хранения файлов 30 дней"|gettext|escape}</span>
            <span style="position: absolute;top: 345px;left: 65px;color: #000;font: 300 16px Roboto, arial, helvetica, sans-serif;">{"Скорость отдачи 1 мб"|gettext|escape}</span>
            {* Правая колонка *}
            <span style="position: absolute;top: 90px;left: 465px;color: #000;font: 600 16px Roboto, arial, helvetica, sans-serif;">{"Размер файлов до 10 гб"|gettext|escape}</span>
            <span style="position: absolute;top: 140px;left: 465px;color: #000;font: 600 16px Roboto, arial, helvetica, sans-serif;">{"Неограниченная скорость загрузки"|gettext|escape}</span>
            <span style="position: absolute;top: 190px;left: 465px;color: #000;font: 600 16px Roboto, arial, helvetica, sans-serif;">{"Конвертация происходит по приоритету"|gettext|escape}</span>
            <span style="position: absolute;top: 240px;left: 465px;color: #000;font: 600 16px Roboto, arial, helvetica, sans-serif;">{"Возможность создавать категории"|gettext|escape}</span>
            <span style="position: absolute;top: 290px;left: 465px;color: #000;font: 600 16px Roboto, arial, helvetica, sans-serif;">{"Возможность создавать плейлисты"|gettext|escape}</span>
            {* Кнопка закрытия *}
            <a href="javascript:" class="simplemodal-close"><img src="{$_template}/img/x.png" style="position: absolute;top: 10px;right: 10px;"></a>
            {* Кнопка зарегистрироваться *}
            <a href="javascript:" id="BonusInfoRegisterButton" class="blueButton296x56" style="position: absolute;bottom: 50px;right: 70px;">{"Зарегистрироваться"|gettext|escape}</a>
        </div>
    </div>
{/block}
