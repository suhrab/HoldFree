<?php

if (!defined('CHECK')) {
    exit;
}

switch($action){
    case 'add':
        $name = trim($_POST['name']);
        $url = trim($_POST['url']);

        $url = filter_var($url, FILTER_VALIDATE_URL);


        if (!$url) {
            throw new Exception('Неверный адрес сервера');
        }

        $url_parsed = parse_url($url);

        $url = $url_parsed['scheme'] . '://' . $url_parsed['host'] . ':' . (empty($url_parsed['port']) ? 80 : $url_parsed['port']);

        $test_url = $url . '/api/info';

        $r = @file_get_contents($test_url);

        if (!$r) {
            throw new Exception('Недоступный сервер');
        }

        $r = json_decode($r, true);

        if (!$r) {
            throw new Exception('Сервер вернул неверный ответ');
        }

        if (!isset($r['disk_total_space']) || !isset($r['disk_free_space']) || !isset($r['files_total'])) {
            throw new Exception('Сервер вернул неверный ответ');
        }

        $sth = $pdo->prepare('INSERT INTO hf_storage_server SET name = :name, url = :url, free_space = :free_space, total_space = :total_space, num_files = :num_files');
        $sth->bindParam(':name', $name);
        $sth->bindParam(':url', $url);
        $sth->bindParam(':free_space', $r['disk_free_space']);
        $sth->bindParam(':total_space', $r['disk_total_space']);
        $sth->bindParam(':num_files', $r['files_total']);
        $sth->execute();
        $sth->null;

        header('Location: index.php?module=storage_server&dashboard=1');
        break;
    case 'delete':
        if(empty($_POST['serverId']) || !is_numeric($_POST['serverId']))
            throw new Exception('Неверный ID сервера');
        $serverId = intval($_POST['serverId']);
        $deleteServerSql = <<<SQL
DELETE FROM
  hf_storage_server
WHERE
  id = $serverId
SQL;
        $deleteServerStmt = $pdo->query($deleteServerSql);
        if($deleteServerStmt->rowCount() == 0)
            throw new Exception('Сервер с таким ID не найден');

        echo json_encode(['success' => true]);
        die;
        break;
}

$query = $pdo->query('SELECT * FROM hf_storage_server ORDER BY id DESC');
$servers = $query->fetchAll();

foreach ($servers as &$server) {
    $server['free_space'] = $_fileManager::formatFileSize($server['free_space']);
    $server['total_space'] = $_fileManager::formatFileSize($server['total_space']);
}

$smarty->assign('servers', $servers);
$smarty->display('_dashboard/storage_server.tpl');