<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Anton
 * Date: 8/9/13
 * Time: 5:31 PM
 * To change this template use File | Settings | File Templates.
 */


if (!defined('CHECK')) {
    exit;
}

if(!$_user->isLogged()){
    header("Location: /");
    exit;
}

$staticPageMessages = array();
$smarty->assignByRef('staticPageErrors', $staticPageMessages);

if(!empty($_POST['action'])){
    $postAction = $_POST['action'];
    switch($postAction){
        case 'add':
            if(empty($_POST['staticPageTitle']))
                $staticPageMessages[] = 'Заполните название страницы';
            if(empty($_POST['staticPageContent']))
                $staticPageMessages[] = 'Заполните содержимое страницы';
            if(!empty($staticPageMessages))
                break;

            $insertSql = <<<SQL
INSERT INTO
  hf_static_pages
SET
  author_id = :author_id,
  title = :title,
  content = :content,
  create_time = NOW(),
  update_time = NOW()
SQL;
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->execute([
                'author_id' => $_user->getId(),
                'title' => $_POST['staticPageTitle'],
                'content' => $_POST['staticPageContent'],
            ]);
            $staticPageMessages[] = 'Страница успешно добавлена';
            break;
        case 'edit':
            if(empty($_POST['staticPageEditId']) || !is_numeric($_POST['staticPageEditId']))
                throw new Exception('Неверный ID страницы для редактирования');

            $staticPageEditId = intval($_POST['staticPageEditId']);

            if(empty($_POST['staticPageTitle']))
                $staticPageMessages[] = 'Заполните название страницы';
            if(empty($_POST['staticPageContent']))
                $staticPageMessages[] = 'Заполните содержимое страницы';
            if(!empty($staticPageMessages))
                break;

            $saveSql = <<<SQL
UPDATE
  hf_static_pages
SET
  author_id = :author_id,
  title = :title,
  content = :content,
  update_time = NOW()
WHERE
  id = $staticPageEditId
SQL;
            $saveStmt = $pdo->prepare($saveSql);
            $saveStmt->execute([
                'author_id' => $_user->getId(),
                'title' => $_POST['staticPageTitle'],
                'content' => $_POST['staticPageContent'],
            ]);

            if($saveStmt->rowCount() == 0)
                throw new Exception('Неверный ID страницы для редактирования либо страница была удалена');

            $staticPageMessages[] = 'Страница успешно сохранена';
            break;
        case 'delete':
            if(empty($_POST['staticPageDeleteId']) || !is_numeric($_POST['staticPageDeleteId']))
                throw new Exception('Неверный ID страницы для удаления');

            $staticPageDeleteId = intval($_POST['staticPageDeleteId']);
            $deleteSql = <<<SQL
DELETE FROM
  hf_static_pages
WHERE
  id = $staticPageDeleteId
SQL;
            $affectedRows = $pdo->exec($deleteSql);
            if($affectedRows == 0)
                throw new Exception('Неверный ID страницы для удаления либо страница уже удалена');

            $staticPageMessages[] = 'Страница успешно удалена';
            break;
        default:
            throw new Exception('Неизвестное действие');
    }
}

$selectAllSql = <<<SQL
SELECT
  hf_static_pages.*, hf_user.first_name, hf_user.last_name
FROM
  hf_static_pages
INNER JOIN hf_user ON hf_user.id = hf_static_pages.author_id
SQL;

$selectAllStmt = $pdo->query($selectAllSql);

if(!empty($_GET['staticPageEditId']) && is_numeric($_GET['staticPageEditId'])){
    $staticPageEditId = intval($_GET['staticPageEditId']);
    $selectEditSql = <<<SQL
SELECT
  *
FROM
  hf_static_pages
WHERE
  id = $staticPageEditId
SQL;
    $selectEditStmt = $pdo->query($selectEditSql);
    if($selectEditStmt->rowCount() == 1){
        $smarty->assign('staticPageEditData', $selectEditStmt->fetch());
    }
}

$smarty->assign('staticPages', $selectAllStmt->fetchAll());
$smarty->display('_dashboard/static_pages.tpl');
