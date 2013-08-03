<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Gee
 * Date: 8/2/13
 * Time: 12:41 AM
 * To change this template use File | Settings | File Templates.
 */

/**
 * Class FileManager
 */
class FileManager
{
    protected $pdo = null;

    public function __construct (PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function addFile($file_name)
    {
        $file_data['system_defined_name']    = 'system_name';
        $file_data['user_defined_name']      = $file_name;
        $file_data['created']                = time();
        $file_data['file_size']              = 7480;
        $file_data['parent']                 = 0;
        $file_data['owner']                  = 16;
        $file_data['files']                  = '';
        $file_data['complete_status']        = 0;
        $file_data['status_message']         = '';

        $sth = $this->pdo->prepare('INSERT INTO hf_file SET system_defined_name = :system_defined_name, user_defined_name = :user_defined_name, created = :created, file_size = :file_size, parent = :parent, owner = :owner, files = :files, complete_status = :complete_status, status_message = :status_message');
        $sth->execute($file_data);
        return $this->pdo->lastInsertId();
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
}