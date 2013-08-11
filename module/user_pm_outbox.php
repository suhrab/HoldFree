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

if($_user->getGroup() == 0){
    throw new Exception(gettext('Личные сообщения только для зарегистрированных'));
}

$stmt = $pdo->query("
    SELECT
      PM_TABLE.id, PM_TABLE.subject, PM_TABLE.addTime, PM_TABLE.to
    FROM
      hf_user_pm_sent PM_TABLE
    WHERE
      PM_TABLE.from = {$_user->getId()}
    ORDER BY PM_TABLE.addTime DESC
");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(!empty($messages)){
    $to_id_array = array();
    foreach($messages as &$message){
        if(!empty($message['to'])){
            $to_array = json_decode($message['to']);
            if($to_array != null AND is_array($to_array)){
                $current_message_to_ids = array();
                foreach($to_array as $to_id){
                    $to_id = intval($to_id);
                    $to_id_array[$to_id] = 1;
                    $current_message_to_ids[$to_id] = 1;
                }

                $message['to_ids'] = $current_message_to_ids;
            }
        }
    }

    if(!empty($to_id_array)){
        $to_ids_comma_separated = implode(',', array_keys($to_id_array));
        $stmt = $pdo->query("
            SELECT
              id, `first_name`, `last_name`
            FROM
              `hf_user`
            WHERE
              id IN ($to_ids_comma_separated)
        ");
        $logins_by_id = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $id = intval($row['id']);
            $logins_by_id[$id] = $row['first_name'] . ' ' . $row['last_name'];
        }

        foreach($messages as &$message){
            if(isset($message['to_ids'])){
                foreach($message['to_ids'] as $to_id => &$login){
                    if(isset($logins_by_id[$to_id])){
                        $login = $logins_by_id[$to_id];
                    }
                }
                $message['to_string'] = implode(', ', $message['to_ids']);
            }
        }
    }
}

$smarty->assign('outbox_messages', $messages);

$smarty->display('user_pm_outbox.tpl');