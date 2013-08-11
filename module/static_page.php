<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Anton
 * Date: 8/11/13
 * Time: 7:02 PM
 * To change this template use File | Settings | File Templates.
 */

if (!defined('CHECK')) {
    exit;
}

if(empty($_GET['staticPageId']) || !is_numeric($_GET['staticPageId']))
    throw new Exception(gettext('Такой страницы не существует'));

$staticPageId = intval($_GET['staticPageId']);

$selectSql = <<<SQL
SELECT
  hf_static_pages.*, hf_user.first_name as authorFirstName, hf_user.last_name as authorLastName
FROM
  hf_static_pages
INNER JOIN hf_user ON hf_user.id = hf_static_pages.author_id
WHERE
  hf_static_pages.id = $staticPageId
SQL;

$selectStmt = $pdo->query($selectSql);
if($selectStmt->rowCount() != 1){
    throw new Exception(gettext('Такой страницы не существует'));
}

$smarty->assign('staticPage', $selectStmt->fetch());
$smarty->display('static_page.tpl');

