<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Anton
 * Date: 7/25/13
 * Time: 2:14 PM
 * To change this template use File | Settings | File Templates.
 */

if (!defined('CHECK')) {
    exit;
}

if(!$_user->isLogged()){
    header("Location: /");
    exit;
}

$messageFolder = isset($_GET['messageFolder']) ? $_GET['messageFolder']: '';

if(empty($_GET['messageId']) || !is_numeric($_GET['messageId']))
    throw new ErrorException('Сообщение с таким ID не найдено');
$messageId = intval($_GET['messageId']);

switch($messageFolder){
    case 'inbox':
        $selectMessageSql = <<<SQL
SELECT
  PM_TABLE.id, PM_TABLE.to, PM_TABLE.from, PM_TABLE.subject, PM_TABLE.message, PM_TABLE.addTime, hf_user_from.first_name, hf_user_from.last_name
FROM
  hf_user_pm_recv PM_TABLE
INNER JOIN `hf_user` hf_user_from ON PM_TABLE.from = hf_user_from.id
WHERE PM_TABLE.id = {$messageId} AND PM_TABLE.`to` = {$_user->getId()}
SQL;
        $selectMessageStmt = $pdo->query($selectMessageSql);
        if($selectMessageStmt->rowCount() != 1)
            throw new ErrorException('Сообщение с таким ID не найдено');
        $message = $selectMessageStmt->fetch(PDO::FETCH_ASSOC);
        $message['to_string'] = $message['first_name'] . ' ' . $message['last_name'];
        break;
    case 'outbox':
        $selectMessageSql = <<<SQL
SELECT
  *
FROM
  hf_user_pm_sent
WHERE id = {$messageId} AND `from` = {$_user->getId()}
SQL;
        $selectMessageStmt = $pdo->query($selectMessageSql);
        if($selectMessageStmt->rowCount() != 1)
            throw new ErrorException('Сообщение с таким ID не найдено');
        $message = $selectMessageStmt->fetch(PDO::FETCH_ASSOC);
        break;
    default:
        throw new ErrorException('messageFolder should be inbox or outbox');
}

$smarty->assign('message', $message);
$smarty->display('user_pm_show.tpl');