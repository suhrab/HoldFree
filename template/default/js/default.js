$(function(){
    var date = new Date();

    var dialogOption = {
        autoOpen    : false,
        width       : 480,
        resizable   : false,
        modal       : true,
        dialogClass : 'dialog',
        open        : function () { $('img.captcha').attr('src', 'http://holdfree.com/class/kcaptcha/index.php?t=' + date.getTime()); }
    };

    var dialogOptionBlue = {
        autoOpen    : false,
        width       : 380,
        resizable   : false,
        modal       : true,
        dialogClass : 'dialogBlue'
    };

    var signUpDialog = $('.dialog-signup').dialog(dialogOption);
    var signInDialog = $('.dialog-signin').dialog(dialogOption);
    var feedbackDialog = $('.dialog-feedback').dialog(dialogOption);
    var errorDialog = $('.dialog-error').dialog(dialogOption);

    var newDirDialog = $('.dialog-dir').dialog(dialogOptionBlue);
    var renameDirDialog = $('.dialog-dir-rename').dialog(dialogOptionBlue);

    $('#signup').click(function() {
        signUpDialog.dialog('open');
    });

    $('#signin').click(function() {
        signInDialog.dialog('open');
    });

    $('#feedback').click(function() {
        feedbackDialog.dialog('open');
    });

    $('#error').click(function() {
        errorDialog.dialog('open');
    });




    /* ===== PAGE FAQ ===== */
    $('a.question').click(function() {
        $(this).next('div').slideToggle();
    });


    /* ===== PAGE MANAGER ===== */
    $('#newDir').click(function() {
        newDirDialog.dialog('open');
    });

    $.contextMenu({
        selector: '.dir',
        items: {
            "edit": {
                name: "Переименовать",
                icon: "rename",
                callback: function(key, option) {
                    var dirId = option.$trigger.attr("data-id");
                    var dirName = option.$trigger.text();
                    $('#formDirRename').find('input:eq(0)').val(dirName);
                    $('#dir_id').val(dirId);
                    renameDirDialog.dialog('open');
                    $('#dir_' + dirId).contextMenu("hide");
                    return false;
                }
            },
            "sep1": "---------",
            "delete": {
                name: "Удалить",
                icon: "delete",
                callback: function(key, option)
                {
                    var dirId = option.$trigger.attr("data-id");

                    $.post('index.php?module=dir&action=delete&is_ajax=1', {"id": dirId}, function(response)
                    {
                        if (response.error) {
                            $('.dialog-error .place-holder-message').html('<div class="error-message">'+ response.message +'</div>');
                        }

                        if(response.success) {
                            $('#dir_' + dirId).contextMenu("hide");
                            $("#row_dir_" + dirId).slideUp();
                        }

                    }, 'json');

                    return false;
                }
            }
        }
    });


    $('.dir').disableSelection();


    /* ===== Форма регистрации ===== */
    $('#formSignUp').on('submit', function()
    {
        var formData = $("#formSignUp").serialize();

        $.post('index.php?module=user&action=signup&is_ajax=1', formData, function(response)
        {
            if (response.error) {
                $('.dialog-signup .place-holder-message').html('<div class="error-message">'+ response.message +'</div>');
                $('img.captcha').attr('src', 'http://holdfree.com/class/kcaptcha/index.php?t=' + date.getTime());
                $('#formSignUp').find('.submit').effect("shake", { distance:10, times:2 }, 700);
            }

            if(response.success) {
                $('.dialog-signup .place-holder-message').html('<div class="success-message">Регистрация прошла успешно. Через несколько мгновений страница будет обновлена.</div>');
                $('#formFeedback').trigger('reset');

                setTimeout(function () {
                    location.reload();
                }, 2500);
            }

        }, 'json');

        return false;
    });


    /* ===== Форма авторизации ===== */
    $('#formSignIn').on('submit', function()
    {
        var formData = $("#formSignIn").serialize();

        $.post('index.php?module=user&action=signin&is_ajax=1', formData, function(response)
        {
            if (response.error) {
                $('.dialog-signin .place-holder-message').html('<div class="error-message">'+ response.message +'</div>');
                $('#formSignIn').find('.submit').effect("shake", { distance:10, times:2 }, 700);
            }

            if(response.success) {
                location.reload();
            }

        }, 'json');

        return false;
    });


    /* ===== Форма обратной связи ===== */
    $('#formFeedback').on('submit', function()
    {
        var formData = $("#formFeedback").serialize();

        $.post('index.php?module=feedback&action=send&is_ajax=1', formData, function(response)
        {
            if (response.error) {
                $('.dialog-feedback .place-holder-message').html('<div class="error-message">'+ response.message +'</div>');
                $('#formFeedback').find('.submit').effect("shake", { distance:10, times:2 }, 700);
                $('img.captcha').attr('src', 'http://holdfree.com/class/kcaptcha/index.php?t=' + date.getTime());
            }

            if(response.success) {
                $('.dialog-feedback .place-holder-message').html('<div class="success-message">Ваше сообщение успешно отправлено. Спасибо.</div>');
                $('#formFeedback').trigger('reset');
            }

        }, 'json');

        return false;
    });


    /* ===== Форма ошибки ===== */
    $('#formError').on('submit', function()
    {
        var formData = $("#formError").serialize();

        $.post('index.php?module=feedback&action=send&is_ajax=1', formData, function(response)
        {
            if (response.error) {
                $('.dialog-error .place-holder-message').html('<div class="error-message">'+ response.message +'</div>');
                $('#formError').find('.submit').effect("shake", { distance:10, times:2 }, 700);
                $('img.captcha').attr('src', 'http://holdfree.com/class/kcaptcha/index.php?t=' + date.getTime());
            }

            if(response.success) {
                $('.dialog-error .place-holder-message').html('<div class="success-message">Ваше сообщение успешно отправлено. Спасибо.</div>');
                $('#formError').trigger('reset');
            }

        }, 'json');

        return false;
    });


    /* ===== Форма созданиея директории ===== */
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

                $dir = '<tr id="row_dir_'+ response.dir_id +'">' +
                        '<td colspan="3"><a href="javascript:;" class="dir" id="dir_'+ response.dir_id +'" data-id="'+ response.dir_id +'">'+ response.dir_name +'</a></td>' +
                        '<td><a href="#"><img src="/template/default/img/icon_24_chain.png" width="24" height="24" alt="URL" /></a></td>' +
                    '</tr>';

                $('#fileList').prepend($dir);

                newDirDialog.dialog('close');
            }

        }, 'json');

        return false;
    });

    /* ===== Форма переименовывания директории ===== */
    $('#formDirRename').on('submit', function()
    {
        var formData = $("#formDirRename").serialize();

        $.post('index.php?module=dir&action=rename&is_ajax=1', formData, function(response)
        {
            console.log(response);
            if (response.error) {
                $('.dialog-dir-rename .place-holder-message').html('<div class="error-message">'+ response.message +'</div>');
                $('#formDirRename').find('.submit').effect("shake", { distance:10, times:2 }, 700);
            }

            if(response.success)
            {
                $('#formDirRename').trigger('reset');
                renameDirDialog.dialog('close');
                $('#dir_' + response.dir_id).text(response.dir_name);
            }

        }, 'json');

        return false;
    });




    $.ajaxSetup({
        dataType: 'post',
        error: function(data) {
            alert('Что-то произошло, но Вам не стоить волноваться, так иногда бывает...');
            console.warn(data.responseText);
        }
    });

    $(".select2").select2({
        minimumResultsForSearch: 10
    });
});