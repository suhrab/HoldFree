<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Anton
 * Date: 7/25/13
 * Time: 12:53 PM
 * To change this template use File | Settings | File Templates.
 */

if (!defined('CHECK')) {
    exit;
}

if(!$_user->isLogged()){
    header("Location: /");
    exit;
}

$stmt = $pdo->query("
    SELECT
      PM_TABLE.id, PM_TABLE.subject, PM_TABLE.was_read, PM_TABLE.addTime, user_from.first_name as from_first_name, user_from.last_name as from_last_name
    FROM
      hf_user_pm_recv PM_TABLE
    INNER JOIN `hf_user` user_from ON user_from.id = PM_TABLE.from
    WHERE
      PM_TABLE.to = {$_user->getId()}
    ORDER BY PM_TABLE.addTime DESC
");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('inbox_messages', $messages);

$smarty->display('user_pm_inbox.tpl');