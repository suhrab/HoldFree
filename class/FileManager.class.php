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

    public function addFile($file_name, $user_id)
    {
        $file_data['system_defined_name']    = 'system_name';
        $file_data['user_defined_name']      = $file_name;
        $file_data['created']                = time();
        $file_data['file_size']              = 7480;
        $file_data['parent']                 = 0;
        $file_data['user_id']                = $user_id;
        $file_data['files']                  = '';
        $file_data['complete_status']        = 0;
        $file_data['status_message']         = '';

        $sth = $this->pdo->prepare('INSERT INTO hf_file SET system_defined_name = :system_defined_name, user_defined_name = :user_defined_name, created = :created, file_size = :file_size, parent = :parent, user_id = :user_id, files = :files, complete_status = :complete_status, status_message = :status_message');
        $sth->execute($file_data);

        return $this->pdo->lastInsertId();
    }

    public function getFileById($file_id)
    {
        $file_data['file_id'] = $file_id;

        $sth = $this->pdo->prepare('SELECT * FROM hf_file WHERE id = :file_id');
        $sth->execute($file_data);
        $received_file_data = $sth->fetch();

        return $received_file_data ? $received_file_data : array();
    }

    public function deleteFile($file_id)
    {
        $file_data['file_id'] = $file_id;

        $sth = $this->pdo->prepare('DELETE FROM hf_file WHERE id = :file_id');
        $sth->execute($file_data);

        return $sth->rowCount();
    }

    public function addDir($dir_name, $user_id, $parent = 0)
    {
        $file_data['user_defined_name']      = $dir_name;
        $file_data['created']                = time();
        $file_data['parent']                 = $parent;
        $file_data['user_id']                = $user_id;
        $file_data['type']                   = 'dir';

        if ($this->isDirExists($file_data['user_defined_name'], $user_id)) {
            throw new Exception('The name “'. $file_data['user_defined_name'] .'” is already taken. Please choose a different name.');
        }

        $sth = $this->pdo->prepare('INSERT INTO hf_file SET user_defined_name = :user_defined_name, created = :created, parent = :parent, user_id = :user_id, type = :type');
        $sth->execute($file_data);

        return $this->pdo->lastInsertId();
    }

    public function deleteDir($dir_name, $user_id)
    {
        $file_data['user_defined_name'] = $dir_name;
        $file_data['user_id'] = (int) $user_id;

        if (!$this->isDirExists($file_data['user_defined_name'], $user_id)) {
            throw new Exception('A directory “'. $file_data['user_defined_name'] .'” is not exists.');
        }

        $sth = $this->pdo->prepare('DELETE FROM hf_file WHERE user_defined_name = :user_defined_name AND user_id = :user_id');
        $sth->execute($file_data);

        return $sth->rowCount();
    }

    public function isDirExists($dir_name, $user_id)
    {
        $file_data['user_defined_name'] = $dir_name;
        $file_data['user_id'] = (int) $user_id;

        $sth = $this->pdo->prepare('SELECT id FROM hf_file WHERE user_defined_name = :user_defined_name AND user_id = :user_id');
        $sth->execute($file_data);

        return $sth->rowCount() ? true : false;
    }

    public function isEmptyDir($dir_id)
    {
        $file_data['id'] = (int) $dir_id;

        $sth = $this->pdo->prepare('SELECT id FROM hf_file WHERE parent = :id');
        $sth->execute($file_data);

        return $sth->rowCount() ? false : true;
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
}