{extends 'index.tpl'}

{block name="content"}
    <div class="content page-error">
        <h4>{$error.title}</h4>
        <h5>{$error.message}</h5>
        <div class="description">
            <p>Вернитесь на <a href="{$_url}">главную страницу</a>. Если эта ошибка будет повторятся, сообщите нам адрес с которого вы сюда попали, на <a href="mailto:{$_contact_email}">{$_contact_email}</a></p>
        </div>
    </div>
{/block}