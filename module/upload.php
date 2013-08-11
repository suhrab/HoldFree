<?php
if (!defined('CHECK')) {
    exit;
}

if(!$_user->isLogged()){
    // create new guest user
    User\User::createGuestAccount($loginHash);
    $_user->signInByHash($loginHash);
}

$file = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : '';

if (!is_uploaded_file($file)) {
    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "File not uploaded!"}, "id" : "id"}');
}

$tmp_file = tempnam(DIR_UPLOAD . 'tmp', 'tmp');

if (!move_uploaded_file($file, $tmp_file)) {
    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Move uploaded file failed!"}, "id" : "id"}');
}

$storageServerStmt = $pdo->query('SELECT url FROM hf_storage_server ORDER BY RAND() LIMIT 1');

if ($storageServerStmt->rowCount() == 0) {
    die(json_encode([
        'jsonrpc' => '2.0',
        'error' => [
            'code' => 103,
            'message' => gettext('Нет доступных серверов хранения файлов')
        ],
        'id' => 'id'
    ]));
}

$dbFileId = $_fileManager->addFile(pathinfo($tmp_file, PATHINFO_BASENAME), $_FILES['file']['name'], $_user->getId(), $_FILES['file']['size']);

$storage_server = $storageServerStmt->fetch(PDO::FETCH_ASSOC)['url'];

$payload = [
    'source_file_url'   => URL . '/upload/tmp/' .  pathinfo($tmp_file, PATHINFO_BASENAME),
    'row_id'            => $dbFileId,
    'storage_server'    => $storage_server,
    'video_sizes'       => ['480p' => true]
];

$gearman_client = new GearmanClient();
$gearman_client->addServer(GEARMAN_JOB_SERVER_HOST);
$task = $gearman_client->addTaskBackground('convert_v3', serialize($payload));
$gearman_client->runTasks();

echo json_encode([
    'jsonrpc' => '2.0',
    'result' => 'null',
    'id' => $dbFileId,
]);
die;