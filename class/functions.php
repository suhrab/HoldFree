<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Anton
 * Date: 7/24/13
 * Time: 3:04 PM
 * To change this template use File | Settings | File Templates.
 */



function backup_database($gzip = false, &$mysqldump_output = null){
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
mysqldump --opt -h "$DB_HOST" --port="$DB_PORT" -u "$DB_USER" $MYSQLDUMP_PASS_ARG "$DB_NAME" $MYSQLDUMP_GZIP_ARG > $backup_filepath
CMD;

    ob_start();
    passthru($command, $return_code);
    $mysqldump_output = ob_get_contents();
    ob_end_clean();

    if($return_code == 0)
        return $backup_filepath;

    return false;
}

function restore_database_fromfile($filepath, &$mysqlimport_output = null){
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
    $mysqlimport_output = ob_get_contents();
    ob_end_clean();

    if($return_code == 0)
        return true;

    return false;
}

function optimize_database(&$mysqlcheck_output = null){
    $DB_NAME = DB_NAME;
    $DB_PORT = DB_PORT == '' ? '3306' : DB_PORT;
    $DB_HOST = DB_HOST;
    $DB_USER = DB_USER;
    $MYSQLCHECK_PASS_ARG = DB_PASS == '' ? '' : '-p"' . DB_PASS . '"';

    $command = <<<CMD
mysqlcheck -h "$DB_HOST" --port="$DB_PORT" -u "$DB_USER" $MYSQLCHECK_PASS_ARG -o $DB_NAME
CMD;

    ob_start();
    passthru($command, $return_code);
    $mysqlcheck_output = ob_get_contents();
    ob_end_clean();

    if($return_code == 0)
        return true;

    return false;
}

function repair_database(&$mysqlcheck_output = null){
    $DB_NAME = DB_NAME;
    $DB_PORT = DB_PORT == '' ? '3306' : DB_PORT;
    $DB_HOST = DB_HOST;
    $DB_USER = DB_USER;
    $MYSQLCHECK_PASS_ARG = DB_PASS == '' ? '' : '-p"' . DB_PASS . '"';

    $command = <<<CMD
mysqlcheck -h "$DB_HOST" --port="$DB_PORT" -u "$DB_USER" $MYSQLCHECK_PASS_ARG -r $DB_NAME
CMD;

    ob_start();
    passthru($command, $return_code);
    $mysqlcheck_output = ob_get_contents();
    ob_end_clean();

    if($return_code == 0)
        return true;

    return false;
}