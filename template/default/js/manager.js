$(function() {
    var cwd = null;
    var fileListNode = $("#fileList");
    var dialogOptionBlue = {
        autoOpen    : false,
        width       : 380,
        resizable   : false,
        modal       : true,
        dialogClass : 'dialogBlue'
    };
    var renameDialog = $('.dialog-dir-rename').dialog(dialogOptionBlue);

    function addFile(id, shortName, fullName, size, created, url) {
        fileListNode.append('<tr> <td><a href="#" data-id="'+ id +'" class="file" title="'+ fullName +'">'+ shortName +'</a></td> <td>'+ size +'</td> <td>'+ created +'</td> <td><a href="'+ url +'"><img src="/template/default/img/icon_24_chain.png" width="24" height="24" alt="Скачать"></a></td></tr>');
    }

    function renameFile(id, name) {
        if (!name.length) {
            return false;
        }

        $.post("/index.php?module=dir&action=rename", { "id": id, "name": name }, function (response) {
            if (response.success) {
                $("#file_id_" + response.id).text(response.name);
            }
        }, "json");
    }

    function move(sourceId, targetId) {
        $.post("/index.php?module=dir&action=move", { "sourceId": sourceId, "targetId": targetId });
    }

    function moveToTrash(id) {
        $.post("/index.php?module=dir&action=move_to_trash", { "id": id }, function (response) {
            if (response.success) {
                $("#row_id_" + id).remove();
            }
        }, "json");
    }

    function loadFiles(dirId) {
        if (dirId == cwd) {
            return false;
        }

        cwd = parseInt(dirId);

        $.post("/index.php?module=dir&action=load_files&is_ajax=1", { "cwd": cwd }, function (response) {
            var row = '';
            var li = '';
            if (response.success) {
                for (i = 0; i < response.files.length; i++) {
                    if (response.files[i].type == 'dir') {
                        row += '<tr id="row_id_'+ response.files[i].id +'"><td colspan="2"><a href="javascript:;" data-id="'+ response.files[i].id +'" class="dir" id="file_id_'+ response.files[i].id +'">'+ response.files[i].user_defined_name +'</a></td><td>'+ response.files[i].created +'</td><td></td></tr>';
                        li += '<li class="dir" data-id="'+ response.files[i].id +'"><a href="javascript:;">'+ response.files[i].cut_user_defined_name +'</a></li>';
                    }
                    else {
                        row += '<tr id="row_id_'+ response.files[i].id +'"><td><a href="javascript:;" data-id="'+ response.files[i].id +'" class="file" id="file_id_'+ response.files[i].id +'">'+ response.files[i].cut_user_defined_name +'</a></td><td>'+ response.files[i].file_size +'</td><td>'+ response.files[i].created +'</td><td><img src="/template/default/img/icon_24_chain.png" width="24" height="24" alt="" /></td></tr>';
                    }
                }

                $("#fileList").html(row);

                if (cwd == -1) {
                    $("#dirListTrash").html(li);
                }
                else {
                    $("#dirList").html(li);
                }
            }
        }, "json");
    }

    loadFiles(0);
//    addFile(45, "Dexter...mp4", "Dexter.S3E5.HD.mp4", "674 KB", "12.08.13", "http://s1.holdfree.com/sdloh32h.mp4");
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
});
