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
    var createPmDialog = $('.dialog-pm-create').dialog(dialogOption);

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

    $('#pm_new_message_button, #pm_reply_button').click(function(){
        $('.dialog-pm-create .place-holder-message').html('')
        $('#pm-create-submit').show()
        createPmDialog.dialog('open')
        if($('#pm-create-subject').val() != '')
            $('#pm-create-message').focus()
    })
    $('#pm-create-submit').click(function(){
        var to_ids = []
        to_ids.push($('#pm-create-toid').val())
        console.log(to_ids)

        $.ajax({
            type: 'POST',
            dataType: 'json',
            cache: false,
            url: '/?module=pm_ajax&is_ajax=1',
            data:{
                action: 'create',
                pm_to: to_ids.join(','),
                pm_subject: $('#pm-create-subject').val(),
                pm_text: $('#pm-create-message').val()
            },
            success: function(d){
                console.log(d)
                if('success' in d){
                    $('#pm-create-submit').hide()
                    $('.dialog-pm-create .place-holder-message').html('<div class="success-message">Сообщение отправлено</div>');
                }
                else if('error' in d){
                    $('.dialog-pm-create .place-holder-message').html('<div class="error-message">'+d['message']+'</div>');
                }
            }
        })
    })




    /* ===== PAGE FAQ ===== */
    $('a.question').click(function() {
        $(this).next('div').slideToggle();
    });


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
