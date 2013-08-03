<?php
if (!defined('CHECK')) {
    exit;
}

$file = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : '';

if (!is_uploaded_file($file)) {
    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "File not uploaded!"}, "id" : "id"}');
}

$tmp_file = tempnam(DIR_UPLOAD . 'tmp', 'tmp');

if (!move_uploaded_file($file, $tmp_file)) {
    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Move uploaded file failed!"}, "id" : "id"}');
}

$dbFileId = $_fileManager->addFile(pathinfo($tmp_file, PATHINFO_BASENAME), $_FILES['file']['name'], $_user->getId(), $_FILES['file']['size']);

$storage_server = $pdo->query('SELECT url FROM hf_storage_server ORDER BY RAND() LIMIT 1')->fetchColumn();

if (!$storage_server) {
    throw new Exception('Недоступен сервер хранения файлов');
}

$payload = [
    'source_file_url'   => URL . '/upload/tmp/' .  pathinfo($tmp_file, PATHINFO_BASENAME),
    'row_id'            => $dbFileId,
    'storage_server'    => $storage_server,
    'video_sizes'       => ['480p' => true]
];

$gearman_client = new GearmanClient();
$gearman_client->addServer('91.213.233.143');
$task = $gearman_client->addTaskBackground('convert_v2', serialize($payload));
$gearman_client->runTasks();

echo json_encode([
    'jsonrpc' => '2.0',
    'result' => 'null',
    'id' => $dbFileId,
]);
die;