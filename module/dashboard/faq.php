<?php

if (!defined('CHECK')) {
    exit;
}

if ($action == 'edit_form')
{
    $qid = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

    $sth = $pdo->prepare('SELECT * FROM hf_faq WHERE id = :qid');
    $sth->bindParam(':qid', $qid);
    $sth->execute();
    $faq_data = $sth->fetch();

    $smarty->assign('faq_data', $faq_data);
    $smarty->display('_dashboard/faq_edit_form.tpl');
    exit;
}
elseif ($action == 'update')
{
    $qid = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $question = htmlentities($_POST['question']);
    $answer = htmlentities($_POST['answer']);

    $sth = $pdo->prepare('UPDATE hf_faq SET question = :question, answer = :answer WHERE id = :qid');
    $sth->bindParam(':qid', $qid);
    $sth->bindParam(':question', $question);
    $sth->bindParam(':answer', $answer);
    $sth->execute();

    header('Location: /index.php?module=faq&dashboard=1');
    exit;
}
elseif ($action == 'delete') {
    $qid = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $sth = $pdo->prepare('DELETE FROM hf_faq WHERE id = :qid LIMIT 1');
    $sth->bindParam(':qid', $qid);
    $sth->execute();

    header('Location: /index.php?module=faq&dashboard=1');
    exit;
}
elseif ($action == 'add_form') {
    $smarty->display('_dashboard/faq_add_form.tpl');
}
elseif ($action == 'insert') {
    $question = htmlentities($_POST['question']);
    $answer = htmlentities($_POST['answer']);

    $sth = $pdo->prepare('INSERT INTO hf_faq SET question = :question, answer = :answer');
    $sth->bindParam(':question', $question);
    $sth->bindParam(':answer', $answer);
    $sth->execute();

    header('Location: /index.php?module=faq&dashboard=1');
    exit;
}
else {
    $faq_list = [];

    $qh = $pdo->query('SELECT id, question, answer FROM hf_faq');

    while ($row = $qh->fetch()) {
        $faq_list[] = $row;
    }

    $smarty->assign('faq_list', $faq_list);
    $smarty->display('_dashboard/faq_list.tpl');
}

exit;