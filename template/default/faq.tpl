{extends 'index.tpl'}

{block name="content"}
    <div class="content page-faq">
        <h1>Часто задаваемые вопросы?</h1>

        <ul>
            {foreach from=$faqs item=faq}
            <li>
                <a href="javascript:;" class="question">{$faq.question}</a>
                <div>
                    {$faq.answer}
                </div>
            </li>
            {/foreach}
        </ul>
    </div>
{/block}