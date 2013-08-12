{extends 'index.tpl'}

{block name="content"}
    <div class="content page-faq">
        <h2>{$staticPage.title}</h2>

        <div>
            {$staticPage.content}
        </div>

        <div style="margin-top: 10px;">
            <!--a href="/?module=user&action=profile&id={$staticPage.author_id}">{"Автор: "|gettext}{$staticPage.authorLastName} {$staticPage.authorFirstName}</a> {$staticPage.update_time} -->
        </div>
    </div>
{/block}