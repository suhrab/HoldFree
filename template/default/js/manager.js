$(function() {
    var cwd = null;
    var fileListNode = $("#fileList");
    var dirListNode = $("#dirList");
    var dirListNodeTrash = $("#dirListTrash");
    var dialogOptionBlue = {
        autoOpen    : false,
        width       : 380,
        resizable   : false,
        modal       : true,
        dialogClass : 'dialogBlue'
    };
    var renameDialog = $('.dialog-dir-rename').dialog(dialogOptionBlue);
    var newDirDialog = $('.dialog-dir').dialog(dialogOptionBlue);

    function addFile(id, shortName, fullName, size, created, url, type) {
        fileListNode.append(
            '<tr class="row_id_'+ id +'">' +
                '<td><a href="javascript:;" data-id="'+ id +'" class="'+ type +' file_id_'+ id +'" title="'+ fullName +'">'+ shortName +'</a></td>' +
                '<td>'+ (type == 'file' ? size : '&nbsp;') +'</td>' +
                '<td>'+ created +'</td>' +
                '<td><a href="'+ url +'"><img src="/template/default/img/icon_24_chain.png" width="24" height="24" alt="Скачать"></a></td>' +
            '</tr>'
        );

        if (type == 'dir') {
            if (cwd == -1) {
                return true;
            }

            dirListNode.append('<li class="row_id_'+ id +' dir"><a href="javascript:;" class="file_id_'+ id +'">'+ shortName  +'</a></li>');
        }
    }

    function renameFile(id, name) {
        if (!name.length) {
            return false;
        }

        $.post("/index.php?module=dir&action=rename", { "id": id, "name": name }, function (response) {
            if (response.success) {
                $(".file_id_" + response.id).text(response.name);
            }
        }, "json");
    }

    function move(sourceId, targetId) {
        $.post("/index.php?module=dir&action=move", { "sourceId": sourceId, "targetId": targetId });
    }

    function moveToTrash(id) {
        $.post("/index.php?module=dir&action=move_to_trash", { "id": id }, function (response) {
            if (response.success) {
                $(".row_id_" + id).remove();
            }
        }, "json");
    }

    function loadFiles(dirId) {
        if (dirId == cwd) {
            return false;
        }

        cwd = parseInt(dirId);
        fileListNode.html("");

        if (!cwd) {
            dirListNode.html("");
        }

        if (cwd == -1) {
            $(".buttons").append('<a href="javascript:;" class="button" id="emptyTrashLink">Очистить корзину</a>');
        }
        else {
            $("#emptyTrashLink").remove();
        }

        $.post("/index.php?module=dir&action=load_files&is_ajax=1", { "cwd": cwd }, function (response) {
            if (response.success) {
                for (i = 0; i < response.files.length; i++) {
                    addFile(
                        response.files[i].id,
                        response.files[i].cut_user_defined_name,
                        response.files[i].user_defined_name,
                        response.files[i].file_size,
                        response.files[i].created,
                        response.files[i].files[0],
                        response.files[i].type
                    );
                }

                if (!response.files.length) {
                    fileListNode.html('<tr><td colspan="4" class="dark-blue align-center">Пусто...</td></tr>');
                }
            }
        }, "json");
    }

    loadFiles(0);
//    renameFile(68, "new_name_file_8.mp4");

    var fileRename = $.contextMenu({
        selector: "a.file",
        items: {
            rename: {
                name: "Переименовать",
                icon: "rename",
                callback: function (key, opt) {
                    var fileId = opt.$trigger.data("id");
                    var fileName = opt.$trigger.text();
                    $("#formRename").find("[name = name]").val(fileName);
                    $("#formRename").find("[name = id]").val(fileId);
                    renameDialog.dialog("open");
                }
            },
            delete: {
                name: "Удалить",
                icon: "delete",
                callback: function (key, opt) {
                    var fileId = opt.$trigger.data("id");
                    moveToTrash(fileId);
                }
            }
        }
    });

    var dirRename = $.contextMenu({
        selector: "a.dir",
        items: {
            rename: {
                name: "Переименовать",
                icon: "rename",
                callback: function (key, opt) {
                    var fileId = opt.$trigger.data("id");
                    var fileName = opt.$trigger.text();
                    $("#formRename").find("[name = name]").val(fileName);
                    $("#formRename").find("[name = id]").val(fileId);
                    renameDialog.dialog("open");
                }
            },
            delete: {
                name: "Удалить",
                icon: "delete",
                callback: function (key, opt) {
                    var fileId = opt.$trigger.data("id");
                    moveToTrash(fileId);
                }
            }
        }
    });

    /* ===== Events ===== */
    $(".dir").on("click", function() {
        dirId = parseInt($(this).data("id"));
        loadFiles(dirId);
    });

    $("#formRename").on("submit", function() {
        var fileName = $(this).find("[name = name]").val();
        var fileId = $(this).find("[name = id]").val();
        renameFile(fileId, fileName);
        renameDialog.dialog("close");
        return false;
    });

    $('#formDir').on('submit', function()
    {
        var formData = $("#formDir").serialize();

        $.post('index.php?module=dir&action=add&is_ajax=1', formData, function(response)
        {
            if (response.error) {
                $('.dialog-dir .place-holder-message').html('<div class="error-message">'+ response.message +'</div>');
                $('#formDir').find('.submit').effect("shake", { distance:10, times:2 }, 700);
            }

            if(response.success)
            {
                $('#formDir').trigger('reset');
                addFile(response.dir_id, response.dir_name, "", "", "", "", "dir");

                newDirDialog.dialog('close');
            }

        }, 'json');

        return false;
    });

    $('#newDir').click(function() {
        newDirDialog.dialog('open');
    });

    $("#emptyTrashLink").on("click", function () {
        $.post('/index.php?module=dir&action=empty_trash&is_ajax=1', null, function (response) {
            if (response.success) {
                loadFiles(-1);
            }
        }, 'json');
    });

});
