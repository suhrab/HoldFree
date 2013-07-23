{extends 'index.tpl'}

{block name="content"}
    <script type="text/javascript" src="{$_template}/js/noty/jquery.noty.js"></script>
    <script type="text/javascript" src="{$_template}/js/noty/layouts/topRight.js"></script>
    <script type="text/javascript" src="{$_template}/js/noty/themes/default.js"></script>

    <script type="text/javascript" src="{$_template}/js/context-menu/jquery.contextMenu.js"></script>
    <script type="text/javascript" src="{$_template}/js/context-menu/jquery.ui.position.js"></script>
    <link type="text/css" href="{$_template}/js/context-menu/jquery.contextMenu.css" rel="stylesheet" />

    <script type="text/javascript">
        $(function()
        {
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
            };
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

        <a href="javascript:;" class="button">Загрузить файл</a>
        <a href="javascript:;" class="button" id="newDir">Создать папку</a>

        <div class="manager">
            <div class="dir-tree">
                <ul>
                    <li class="dir-root">
                        <a href="#" class="parent">Менеджер файлов</a>
                        <ul>
                            <li class="dir"><a href="#">Dexter</a></li>
                            <li class="dir"><a href="#">House M.D.</a></li>
                            <li class="dir"><a href="#">How to make it in America</a></li>
                        </ul>
                    </li>

                    <li class="dir-trash">
                        <a href="#" class="parent">Удаленные файлы</a>
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
                            <td width="50">URL</td>
                        </tr>
                    </thead>
                    <tbody id="fileList">
                        {foreach from=$dir_list item=dir}
                            <tr id="row_dir_{$dir.id}">
                                <td colspan="3"><a href="javascript:;" class="dir" id="dir_{$dir.id}" data-id="{$dir.id}">{$dir.name}</a></td>
                                <td><a href="#"><img src="{$_template}/img/icon_24_chain.png" width="24" height="24" alt="URL" /></a></td>
                            </tr>
                        {/foreach}

                        <tr>
                            <td><a href="#" class="file">Dexter_s1_e1.mp4</a></td>
                            <td>347.1 MB</td>
                            <td>17.09.2012, 18:01</td>
                            <td><a href="#"><img src="{$_template}/img/icon_24_chain.png" width="24" height="24" alt="URL" /></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="cleaner"></div>

            <div class="loading-panel">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <td>Имя</td>
                        <td width="140">Размер</td>
                        <td width="200">Статус</td>
                        <td width="140">Скорость</td>
                    </tr>
                    </thead>
                    <tbody id="uploading-files">
                        <tr>
                            <td>Dexter_s1_e1.mp4</td>
                            <td>292 MB</td>
                            <td>В очереди</td>
                            <td>742 Kb/s</td>
                        </tr>
                        <tr>
                            <td>Dexter_s1_e1.mp4</td>
                            <td>292 MB</td>
                            <td class="dark-blue">В очереди</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Dexter_s1_e1.mp4</td>
                            <td>292 MB</td>
                            <td class="dark-blue">В очереди</td>
                            <td></td>
                        </tr>
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