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

    public function addFile($system_defined_name, $user_defined_name, $user_id, $file_size = 0)
    {
        $file_data['system_defined_name']    = $system_defined_name;
        $file_data['user_defined_name']      = $user_defined_name;
        $file_data['created']                = time();
        $file_data['file_size']              = $file_size;
        $file_data['parent']                 = 0;
        $file_data['user_id']                = $user_id;
        $file_data['files']                  = '';
        $file_data['complete_status']        = 0;
        $file_data['status_message']         = '';

        $sth = $this->pdo->prepare('SELECT id FROM hf_file WHERE user_defined_name = :user_defined_name AND user_id = :user_id LIMIT 1');
        $sth->bindParam(':user_defined_name', $file_data['user_defined_name']);
        $sth->bindParam(':user_id', $file_data['user_id']);
        $sth->execute();

        if ($sth->rowCount()) {
            $file_path_info = pathinfo($file_data['user_defined_name']);
            $file_data['user_defined_name'] = $file_path_info['filename'] . '_' . date('d-m-Y_H-i-s') . '.' . $file_path_info['extension'];
        }

        $sth = $this->pdo->prepare('INSERT INTO hf_file SET system_defined_name = :system_defined_name, user_defined_name = :user_defined_name, created = :created, file_size = :file_size, parent = :parent, user_id = :user_id, files = :files, complete_status = :complete_status, status_message = :status_message');
        $sth->execute($file_data);

        return $this->pdo->lastInsertId();
    }

    public function getFileInfo($file_id)
    {
        $file_data['file_id'] = (int) $file_id;

        $sth = $this->pdo->prepare('SELECT * FROM hf_file WHERE id = :file_id');
        $sth->execute($file_data);
        $received_file_data = $sth->fetch();

        return $received_file_data ? $received_file_data : array();
    }

    public function getFilesInfoByUserId($user_id)
    {
        $sth = $this->pdo->prepare('SELECT * FROM hf_file WHERE user_id = :user_id AND complete_status = 100 ORDER BY created DESC');
        $sth->bindParam(':user_id', $user_id);
        $sth->execute();
        $files_info = $sth->rowCount() ? $sth->fetchAll() : array();

        foreach ($files_info as &$file)
        {
            $file['created'] = date('d.m.y');

            if (mb_strlen($file['user_defined_name']) > 40) {
                $file['user_defined_name'] = mb_substr($file['user_defined_name'], 0, 15) . '...' . mb_substr($file['user_defined_name'], -10, 10);
            }

            $file['file_size'] = $this->formatFileSize($file['file_size']);
        }

        return $files_info;
    }

    public function getFilesInfoInProgressByUserId($user_id)
    {
        $sth = $this->pdo->prepare('SELECT * FROM hf_file WHERE user_id = :user_id AND complete_status < 100 AND type = "file" ORDER BY created DESC');
        $sth->bindParam(':user_id', $user_id);
        $sth->execute();
        $files_info = $sth->rowCount() ? $sth->fetchAll() : array();

        return $files_info;
    }

    public function renameFile($file_id, $file_name)
    {
        $file_data['file_id'] = (int) $file_id;
        $file_data['user_defined_name'] = $file_name;

        $sth = $this->pdo->prepare('UPDATE hf_file SET user_defined_name = :user_defined_name WHERE id = :file_id');
        $sth->execute($file_data);

        return $sth->rowCount();
    }

    public function deleteFile($file_id)
    {
        $file_data['file_id'] = (int) $file_id;

        $file_info = $this->getFileInfo($file_data['file_id']);

        if ($file_info['type'] == 'file' && ($file_info['complete_status'] != 100 && empty($file_info['status_message']))) {
            throw new Exception('Файл “'. $file_info['user_defined_name'] .'” не может быть удален так как находится в процессе обрабатывания.');
        }

        if ($file_info['type'] == 'dir' && !$this->isEmptyDir($file_data['file_id'])) {
            throw new Exception('Директория “'. $file_info['user_defined_name'] .'” не может быть удалена, так как содержит файлы или папки.');
        }

        if ($file_info['files'] && $file_info['type'] == 'file') {
            // TODO нужно удалять файл физически
        }

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

        $sth = $this->pdo->prepare('SELECT id FROM hf_file WHERE user_defined_name = :user_defined_name AND user_id = :user_id');
        $sth->bindParam(':user_defined_name', $file_data['user_defined_name']);
        $sth->bindParam(':user_id', $file_data['user_id']);
        $sth->execute();

        if ($sth->rowCount()) {
            throw new Exception('The name “'. $file_data['user_defined_name'] .'” is already taken. Please choose a different name.');
        }

        $sth = $this->pdo->prepare('INSERT INTO hf_file SET user_defined_name = :user_defined_name, created = :created, parent = :parent, user_id = :user_id, type = :type');
        $sth->execute($file_data);

        return $this->pdo->lastInsertId();
    }

    public function deleteDir($dir_id)
    {
        $this->deleteFile($dir_id);
    }

    public function isDirExists($dir_id)
    {
        $file_data['dir_id'] = $dir_id;

        $sth = $this->pdo->prepare('SELECT id FROM hf_file WHERE id = :dir_id');
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

    public function moveFile($file_id, $target_dir_id)
    {
        $file_data['file_id'] = (int) $file_id;
        $file_data['target_dir_id'] = (int) $target_dir_id;

        if (!$this->isDirExists($file_data['target_dir_id'])) {
            throw new Exception('Directory not exists');
        }

        $sth = $this->pdo->prepare('UPDATE hf_file SET parent = :target_dir_id WHERE id = :file_id');
        $sth->execute($file_data);

        return $sth->rowCount();
    }

    public function moveDir($dir_id, $target_dir_id)
    {
        $this->moveFile($dir_id, $target_dir_id);
    }

    public static function formatFileSize($file_size) {
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $file_size > 0 ? floor(log($file_size, 1024)) : 0;
        return number_format($file_size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
}