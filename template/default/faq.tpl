{extends 'index.tpl'}

{block name="content"}
    <div class="content page-faq">
        <h1>{"Часто задаваемые вопросы?"|gettext}</h1>

        <ul>
            {foreach from=$faqs item=faq}
            <li>
                <a href="javascript:;" class="question">{$faq.question}</a>
                <div>
                    {$faq.answer|nl2br}
                </div>
            </li>
            {/foreach}
        </ul>
    </div>
{/block}