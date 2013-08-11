<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Anton
 * Date: 7/25/13
 * Time: 1:17 PM
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


if(empty($_POST['action']))
    throw new Exception (gettext('Действие не определено'));

$action = $_POST['action'];

$pm_json = array();
switch($action){
    case 'delete':
        $message_ids = !empty($_POST['message_ids']) ? $_POST['message_ids'] : array();
        if(empty($message_ids) OR !is_array($message_ids))
            throw new ErrorException(_('Сообщения не найдены'));

        foreach($message_ids as $message_id){
            if(!is_numeric($message_id))
                throw new ErrorException(_('Сообщения не найдены'));

            $message_id = intval($message_id);
        }
        $message_ids_comma_separated = implode(',', $message_ids);

        $message_folder = isset($_POST['message_folder']) ? $_POST['message_folder'] : '';
        switch($message_folder){
            case 'inbox':
                $deleteSql = <<<SQL
DELETE FROM
  hf_user_pm_recv
WHERE
  id IN ({$message_ids_comma_separated}) AND `to` = {$_user->getId()}
SQL;
                $pdo->exec($deleteSql);
                $pm_json['success'] = 'true';
                break;
            case 'outbox':
                $deleteSql = <<<SQL
DELETE FROM
  hf_user_pm_sent
WHERE
  id IN ({$message_ids_comma_separated}) AND `from` = {$_user->getId()}
SQL;
                $pdo->exec($deleteSql);
                $pm_json['success'] = 'true';
                break;
            default:
                throw new ErrorException ('message_folder should be inbox or outbox');
        }
        break;
    case 'create':
        $to_arr = !empty($_POST['pm_to']) ? explode(',', $_POST['pm_to']) : array();
        if(empty($to_arr))
            throw new ErrorException(_("Укажите хотябы одного получателя"));
        foreach($to_arr as $to){
            if(!is_numeric($to))
                throw new ErrorException(_("Неверный ID пользователя: " . $to));
            $to = intval($to);
        }
        $to_arr_count = count($to_arr);
        if($to_arr_count > 3)
            throw new ErrorException(_("Максимум 3 получателя"));

        $pm_subject = !empty($_POST['pm_subject']) ? trim($_POST['pm_subject']) : '';
        if(mb_strlen($pm_subject) > 50)
            throw new ErrorException(_("Максимум 50 символов"));
        $pm_text = !empty($_POST['pm_text']) ? trim($_POST['pm_text']) : '';
        if(empty($pm_text))
            throw new ErrorException(_("Напишите что-нибудь"));
        if(mb_strlen($pm_text) > 65535)
            throw new ErrorException(_("Максимум 65535 символов"));

        // проверим все ли получатели есть в базе
        // можем не экранировать $to_arr, все его значения были приведены к int
        $id_list = implode(',', $to_arr);
        $selectSql = <<<SQL
SELECT
  1
FROM
  hf_user
WHERE
  id IN ({$id_list})
SQL;
        $stmt = $pdo->query($selectSql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if($stmt->rowCount() == 0 || $stmt->rowCount() != $to_arr_count)
            throw new ErrorException(_("Неверный ID пользователя"));

        // сохраняем в базу
        $saveto_sent_sql = <<<SQL
INSERT INTO
  hf_user_pm_recv
SET
  `from` = :from,
  `to` = :to,
  subject = :subject,
  message = :message,
  addTime = NOW()
SQL;
        $saveto_recv_stmt = $pdo->prepare($saveto_sent_sql);
        $saveto_sent_sql = <<<SQL
INSERT INTO
  hf_user_pm_sent
SET
  `from` = :from,
  `to` = :to,
  subject = :subject,
  message = :message,
  addTime = NOW()
SQL;

        $saveto_sent_stmt = $pdo->prepare($saveto_sent_sql);

        foreach($to_arr as $to){
            $saveto_recv_stmt->execute(array(
                'from' => $_user->getId(),
                'to' => $to,
                'subject' => $pm_subject,
                'message' => $pm_text,
            ));
        }

        $saveto_sent_stmt->execute(array(
            'from' => $_user->getId(),
            'to' => json_encode($to_arr),
            'subject' => $pm_subject,
            'message' => $pm_text
        ));

        $pm_json['success'] = 'true';
        break;
    default:
        throw new Exception (gettext('Неизвестное действие'));
}

echo json_encode($pm_json);