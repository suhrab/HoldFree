<?php
if (!defined('CHECK')) {
    exit;
}

$faqs = array();

$qh = $pdo->query('SELECT question, answer FROM hf_faq');
$faqs = $qh->fetchAll();
$qh = null;

$smarty->assign('faqs', $faqs);
$smarty->display('faq.tpl');

