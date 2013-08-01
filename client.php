<?php

$client = new GearmanClient();
$client->addServer('91.213.233.143');
$unique = uniqid();

$result = $client->doNormal('second_task', $unique);

for ($i = 0; $i < 5; $i++) {
    sleep(1);
    list($numerator, $denominator) = $client->doStatus();
    echo 'Status: ' . $numerator . '/' . $denominator . '<br />';
}

echo $result;
