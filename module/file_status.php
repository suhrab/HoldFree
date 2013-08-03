<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Anton
 * Date: 8/3/13
 * Time: 4:24 PM
 * To change this template use File | Settings | File Templates.
 */

if (!defined('CHECK')) {
    exit;
}

$files = array();
if(empty($_POST['fileIds'])){
    echo json_encode($files);
    die;
}

$fileIds = $_POST['fileIds'];
if(!is_array($fileIds))
    throw new Exception('fileIds не массив');


if(empty($fileIds)){
    echo json_encode($files);
    die;
}

foreach($fileIds as &$fileId){
    if(!is_numeric($fileId))
        throw new Exception('fileId: ' . $fileId . ' не число');

    $fileId = intval($fileId);
}

$fileIdsCommaSeparated = implode(',', $fileIds);

$selectSql = <<<SQL
SELECT
  id, complete_status, status_message, files, user_defined_name, file_size, created
FROM
  hf_file
WHERE
  id IN ($fileIdsCommaSeparated)
SQL;

$selectStmt = $pdo->query($selectSql);

while($row = $selectStmt->fetch(PDO::FETCH_ASSOC)){
    $row['created'] = date('d.m.Y', $row['created']);
    $row['file_size'] = FileManager::formatFileSize($row['file_size']);
    $files[] = $row;
}

echo json_encode($files);
die;
