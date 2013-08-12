var cwd = null;
var dragFileId = 0;

function move(sourceId, targetId) {
    $.post("/index.php?module=dir&action=move", { "sourceId": sourceId, "targetId": targetId }, function (response) {
        if (response.success) {
            $(".row_id_" + sourceId).remove();
        }
    }, 'json');
}

function addFile(id, shortName, fullName, size, created, url, type, parent) {
    if ($("#fileList").find(".empty-dir").length) {
        $("#fileList").html("");
    }

    $("#fileList").append(
        '<tr class="row_id_'+ id +'">' +
            '<td data-id="'+ id +'" class="'+ type +' file_id_'+ id +'"><a href="javascript:;" title="'+ fullName +'">'+ shortName +'</a></td>' +
            '<td>'+ (type == 'file' ? size : '&nbsp;') +'</td>' +
            '<td><a href="javascript:;" data-url="'+ url +'" class="playVideo">'+ (type == 'file' ? '<img src="/template/default/img/icon_24_chain.png" width="24" height="24" alt="Скачать">' : '&nbsp;') +'</a></td>' +
            '<td class="align-center">'+ created +'</td>' +
            '</tr>'
    );

    if (type == 'dir')
    {
        // Если выбранная директория "Корзина" – выходим
        if (cwd == -1) {
            return true;
        }

        if (parent > 0) {
            if ($("#dirList").find('ul.child_dir_' + parent).length == 0) {
                $("#dirList").find('li.row_id_' + parent).append('<ul class="child_dir_'+ parent +' child_dir file_id_'+ id +'"></ul>');
            }

            if ($("#dirList").find('.row_id_'+ id).length) {
                return false;
            }

            $("#dirList").find('ul.child_dir_' + parent).append('<li class="row_id_'+ id +' dir" data-id="'+ id +' file_id_'+ id +'"><i class="icon-dir"></i><a href="javascript:;" class="file_id_'+ id +'">'+ shortName  +'</a></li>');
        }
        else {
            $("#dirList").append('<li class="row_id_'+ id +' dir  file_id_'+ id +'" data-id="'+ id +'"><i class="icon-dir"></i><a href="javascript:;">'+ shortName  +'</a></li>');
        }
    }

    initDragAndDrop();
}

function initDragAndDrop()
{
    $(".file, .dir").draggable({
        delay: 200,
        revert: true,
        containment: "#fileList",
        helper: function(event) {
            return $( "<div class='move-file'><i class='icon-allow-drop'></i> Переместить 1 объект</div>" );
        },
        start: function (event, ui) {
            dragFileId = $(event.target).data("id");
        },
        stop: function (event, ui) {
            dragFileId = 0;
        }
    });

    $(".dir").droppable({
        drop: function(event, ui) {
            if ($(this).hasClass("dir") ) {
                var targetId = $(this).data("id");
                var sourceId = ui.draggable.data("id");
                move(sourceId, targetId);
            }
        }
    });
}



function loadFiles(dirId) {
    cwd = parseInt(dirId);
    $("#fileList").html("");

    if (!cwd) {
        $("#dirList").html("");
    }

    if (cwd == -1) {
        $(".buttons").append('<a href="javascript:;" class="button" id="emptyTrashLink">Очистить корзину</a>');
    }
    else {
        $("#emptyTrashLink").remove();
    }

    $.post("/index.php?module=dir&action=load_files&is_ajax=1", { "cwd": cwd }, function (response) {
        console.log(response);
        if (response.success) {
            for (i = 0; i < response.files.length; i++) {
                var file = '';

                if (response.files[i].type == "file") {
                    files = $.parseJSON(response.files[i].files);
                    file = files["480p"];
                }

                addFile(
                    response.files[i].id,
                    response.files[i].cut_user_defined_name,
                    response.files[i].user_defined_name,
                    response.files[i].file_size,
                    response.files[i].created,
                    file,
                    response.files[i].type,
                    response.files[i].parent
                );
            }

            if (!response.files.length) {
                $("#fileList").html('<tr class="empty-dir"><td colspan="4" class="dark-blue align-center">Пусто...</td></tr>');
            }

            initDragAndDrop();
        }
    }, "json");
}



$(function() {
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

    function renameFile(id, name) {
        if (!name.length) {
            return false;
        }

        $.post("/index.php?module=dir&action=rename", { "id": id, "name": name }, function (response) {
            if (response.success) {
                $(".file_id_" + response.id).find('a:eq(0)').text(response.name);
            }
        }, "json");
    }

    function moveToTrash(id) {
        $.post("/index.php?module=dir&action=move_to_trash", { "id": id }, function (response) {
            if (response.success) {
                $(".row_id_" + id).remove();
            }
        }, "json");
    }

    loadFiles(0);
//    loadAllDirs();
//    renameFile(68, "new_name_file_8.mp4");

    var fileRename = $.contextMenu({
        selector: ".file",
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
        selector: ".dir",
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
    $(document).on("click", ".dir", function(event) {
        dirId = parseInt($(this).data("id"));
        loadFiles(dirId);
        event.stopPropagation();
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
                addFile(response.dir_id, response.dir_name, "", "", "", "", "dir", 0);

                newDirDialog.dialog('close');
            }

        }, 'json');

        return false;
    });

    $('#newDir').click(function() {
        var parentDirId = cwd >= 0 ? cwd : 0;

        $("#parentDirId").val(parentDirId);
        newDirDialog.dialog('open');
    });

    $(document).on("click", "#emptyTrashLink", function() {

        $.post('/index.php?module=dir&action=empty_trash&is_ajax=1', null, function (response) {
            if (response.success) {
                fileListNode.html('<tr class="empty-dir"><td colspan="4" class="dark-blue align-center">Пусто...</td></tr>');
            }
        }, 'json');
    });

    $(document).on("click", ".playVideo", function() {
        var videoUrl = $(this).data("url");
        $(".fileSrc").val("m=video&file=" + videoUrl);
        $("#videoInfo").modal({
            opacity: 80,
            overlayCss: { backgroundColor:"#121d27" },
            modal: true
        });
    });

    $("*").hover(function() {
        hoverElement = $(this);
    });

    initDragAndDrop();
});
