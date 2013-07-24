<?php

if (!defined('CHECK')) {
    exit;
}


if ($action == 'optimize') {
    $DB_NAME = DB_NAME;
    $DB_PORT = DB_PORT == '' ? '3306' : DB_PORT;
    $DB_HOST = DB_HOST;
    $DB_USER = DB_USER;
    $MYSQLCHECK_PASS_ARG = DB_PASS == '' ? '' : '-p"' . DB_PASS . '"';

    $command = <<<CMD
    mysqlcheck -h "$DB_HOST" --port="$DB_PORT" -u "$DB_USER" $MYSQLCHECK_PASS_ARG -o $DB_NAME 2>&1
CMD;
    ob_start();
    passthru($command, $return_code);
    $mysqlcheck_output = ob_get_contents();

    ob_end_clean();

    if($return_code == 0) {
        die('{"success": "true"}');
    }
    else {
        throw new Exception('Ошибка mysqlcheck: ' . $mysqlcheck_output);
    }
}
elseif ($action == 'repair') {
    $DB_NAME = DB_NAME;
    $DB_PORT = DB_PORT == '' ? '3306' : DB_PORT;
    $DB_HOST = DB_HOST;
    $DB_USER = DB_USER;
    $MYSQLCHECK_PASS_ARG = DB_PASS == '' ? '' : '-p"' . DB_PASS . '"';

    $command = <<<CMD
mysqlcheck -h "$DB_HOST" --port="$DB_PORT" -u "$DB_USER" $MYSQLCHECK_PASS_ARG -r $DB_NAME 2>&1
CMD;

    ob_start();
    passthru($command, $return_code);
    $mysqlcheck_output = ob_get_contents();
    ob_end_clean();

    if($return_code == 0) {
        die('{"success": "true"}');
    }
    else {
        throw new Exception('Ошибка mysqlcheck: ' . $mysqlcheck_output);
    }
}
elseif ($action == 'dump')
{
    $gzip = isset($_POST['gzip']) ? true : false;

    $DB_NAME = DB_NAME;
    $DB_PORT = DB_PORT == '' ? '3306' : DB_PORT;
    $DB_HOST = DB_HOST;
    $DB_USER = DB_USER;
    $MYSQLDUMP_PASS_ARG = DB_PASS == '' ? '' : '-p"' . DB_PASS . '"';
    $MYSQLDUMP_GZIP_ARG = $gzip == true ? '| gzip' : '';

    $backup_filename_format = '%s_%s%s.sql';
    $backup_filepath = DIR_ROOT . 'backup/' . sprintf($backup_filename_format, DB_NAME, date('Y_m_d'), '');
    while(file_exists($backup_filepath)){
        sleep(1);
        $backup_filepath = DIR_ROOT . 'backup/' . sprintf($backup_filename_format, DB_NAME, date('Y_m_d'), '_' . uniqid());
    }

    if($gzip)
        $backup_filepath = $backup_filepath . '.gz';

    $command = <<<CMD
mysqldump --opt -h "$DB_HOST" --port="$DB_PORT" -u "$DB_USER" $MYSQLDUMP_PASS_ARG "$DB_NAME" $MYSQLDUMP_GZIP_ARG > $backup_filepath  2>&1
CMD;

    ob_start();
    passthru($command, $return_code);
    $mysqldump_output = ob_get_contents();
    ob_end_clean();

    if($return_code == 0) {
        $file = pathinfo($backup_filepath, PATHINFO_BASENAME);
        die('{"success": "true", "file": "'. $file .'"}');
    }
    else {
        throw new Exception('Ошибка mysqlcheck: ' . $mysqlcheck_output);
    }
}
elseif ($action == 'load')
{
    if (!isset($_POST['file'])) {
        throw new Exception('Необходимо передать имя файла');
    }

    $filepath = DIR_ROOT . 'backup/' . $_POST['file'];

    $DB_NAME = DB_NAME;
    $DB_PORT = DB_PORT == '' ? '3306' : DB_PORT;
    $DB_HOST = DB_HOST;
    $DB_USER = DB_USER;
    $MYSQLIMPORT_PASS_ARG = DB_PASS == '' ? '' : '-p"' . DB_PASS . '"';

    $file_extension = pathinfo($filepath, PATHINFO_EXTENSION);
    $gzip = strcasecmp($file_extension, 'gz') == 0 ? true : false;

    $command = '';
    if($gzip){
        $command = <<<CMD
gunzip < "$filepath" | mysql -h "$DB_HOST" --port="$DB_PORT" -u "$DB_USER" $MYSQLIMPORT_PASS_ARG $DB_NAME
CMD;
    } else {
        $command = <<<CMD
mysql -h "$DB_HOST" --port="$DB_PORT" -u "$DB_USER" $MYSQLIMPORT_PASS_ARG $DB_NAME < "$filepath"
CMD;
    }

    ob_start();
    passthru($command, $return_code);
    $command_output = ob_get_contents();
    ob_end_clean();

    if($return_code == 0) {
        die('{"success": "true"}');
    }
    else {
        throw new Exception('Ошибка команды: ' . $command_output);
    }
}

if ($is_ajax) {
    die('{"success": "true"}');
}

$dir = new DirectoryIterator(DIR_ROOT . 'backup');
$file_list = array();

foreach ($dir as $fileinfo) {
    if ($fileinfo->getExtension() == 'sql' || $fileinfo->getExtension() == 'gz') {
        $file_list[] = $fileinfo->getFileName();
    }
}

$smarty->assign('file_list', $file_list);
$smarty->display('_dashboard/database.tpl');