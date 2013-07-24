{extends 'index.tpl'}

{block name="content"}
    <div class="content page-messages">
        <h2>{"Сообщения"|gettext}</h2>

        <a href="#" class="button">{"Написать сообщение"|gettext}</a>
        <a href="#" class="button">{"Удалить"|gettext}</a>

        <table width="100%" border="0" cellspacing="0" cellpadding="4">
            <thead>
                <tr>
                    <td width="230">{"Отправитель"|gettext}</td>
                    <td>{"Сообщение"|gettext}</td>
                    <td width="180">{"Дата"|gettext}</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{"Администрация"|gettext}</td>
                    <td class="strong"><a href="">{"Ваш файл был удален"|gettext}</a></td>
                    <td>23 Января 2013, 8:44</td>
                </tr>
                <tr>
                    <td>{"Администрация"|gettext}</td>
                    <td class="strong"><a href="">{"Ваш файл был удален"|gettext}</a></td>
                    <td>23 Января 2013, 8:44</td>
                </tr>
                <tr>
                    <td>{"Администрация"|gettext}</td>
                    <td class="strong"><a href="">{"Ваш файл был удален"|gettext}</a></td>
                    <td>23 Января 2013, 8:44</td>
                </tr>
                <tr>
                    <td>{"Администрация"|gettext}</td>
                    <td class="strong"><a href="">{"Ваш файл был удален"|gettext}</a></td>
                    <td>23 Января 2013, 8:44</td>
                </tr>
            </tbody>
        </table>
    </div>
{/block}