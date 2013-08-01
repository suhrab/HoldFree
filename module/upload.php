<?php
if (!defined('CHECK')) {
    exit;
}

$file = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : '';

if (!is_uploaded_file($file)) {
    throw new Exception('File not uploaded!');
}

$tmp_file = tempnam(DIR_UPLOAD . 'tmp', 'tmp');

if (!move_uploaded_file($file, $tmp_file)) {
    throw new Exception('Move uploaded file failed!');
}

$sth = $pdo->prepare('
    INSERT INTO
        hf_file
    SET
        user_defined_name = :user_defined_name,
        files = :files,
        source_file_url = :source_file_url,
        added = :added,
        type = :type,
        owner = :owner,
        complete_status = 0,
        status_message = ""
');

$file_data['user_defined_name'] = pathinfo($_FILES['file']['name'], PATHINFO_BASENAME);
$file_data['files'] = '';
$file_data['source_file_url'] = URL . '/upload/tmp/' .  pathinfo($tmp_file, PATHINFO_BASENAME);
$file_data['added'] = time();
$file_data['type'] = 'file';
$file_data['owner'] = 16;

$sth->execute($file_data);

$payload = [
    'source_file_url'   => $file_data['source_file_url'],
    'row_id'            => $pdo->lastInsertId(),
    'storage_server'    => 'http://holdfreestorage.com',
    'video_sizes'       => ['480p' => true, '720p' => true]
];

$gearman_client = new GearmanClient();
$gearman_client->addServer('91.213.233.143');
$task = $gearman_client->addTaskBackground('convert_v1', serialize($payload));
$gearman_client->runTasks();