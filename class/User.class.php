<?php

namespace User;

/**
 * Created by JetBrains PhpStorm.
 * User: Gee
 * Date: 6/25/13
 * Time: 8:03 PM
 * To change this template use File | Settings | File Templates.
 */


class User
{
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;

        require_once(DIR_CLASS . 'passwordLib/bootstrap.php');
        $this->passwordLib = new \PasswordLib\PasswordLib();

        $this->updateLastSignIn();
    }

    /**
     * Добавление нового пользователя
     *
     * @param string $email электронный адрес пользователя
     * @param int $id ID страны
     * @param string $password пароль в чистом виде
     * @param string $first_name имя пользователя
     * @param string $last_name фамилия пользователя
     * @throws ExceptionImproved
     * @return int идентификационный номер добавленого объявления
     */
    public function signUp($first_name, $email, $country, $password = '', $last_name = '')
    {
        $email      = filter_var($email, FILTER_VALIDATE_EMAIL);
        $country    = filter_var($country, FILTER_SANITIZE_NUMBER_INT);
        $password   = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $first_name = filter_var($first_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_name  = filter_var($last_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $reg_date   = time();

        if (!$first_name) {
            throw new \ExceptionImproved('Введите Ваше имя, пожалуйста');
        }

        if (!$email) {
            throw new \ExceptionImproved('Введите Ваш email, пожалуйста');
        }

        if (!$country) {
            throw new \ExceptionImproved('Укажите Вашу страну, пожалуйста');
        }

        if (!$country) {
            throw new \ExceptionImproved('Укажите Вашу страну, пожалуйста');
        }

        if ($password) {
            $hash = $this->passwordLib->createPasswordHash($password);
        }

        $sth = $this->pdo->prepare('INSERT INTO hf_user SET email = :email, country = :country, password = :password, first_name = :first_name, last_name = :last_name, reg_date = :reg_date');
        $sth->bindParam(':email', $email);
        $sth->bindParam(':country', $country);
        $sth->bindParam(':password', $hash);
        $sth->bindParam(':first_name', $first_name);
        $sth->bindParam(':last_name', $last_name);
        $sth->bindParam(':reg_date', $reg_date);
        $sth->execute();

        $this->signIn($email, $password);

        return $this->pdo->lastInsertId();
    }

    /**
     * Авторизация пользователя
     *
     * @param $email
     * @param $password
     *
     * @return bool
     * @throws ExceptionImproved
     */
    public function signIn($email, $password)
    {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $password = trim($password);

        if (!$email) {
            throw new \ExceptionImproved('Введите Ваш email, пожалуйста');
        }

        if (!$password) {
            throw new \ExceptionImproved('Введите Ваш пароль, пожалуйста');
        }

        $user_data = $this->getByEmail($email);

        if (!$user_data) {
            throw new \ExceptionImproved('Не верный email или пароль');
        }

        if (!$this->passwordLib->verifyPasswordHash($password, $user_data['password'])) {
            throw new \ExceptionImproved('Не верный email или пароль');
        }

        if ($this->isBanned($user_data['id'])) {
            throw new \ExceptionImproved('Вы забанены!');
        }

        $this->id = $user_data['id'];
        $this->first_name = $user_data['first_name'];
        $this->last_name = $user_data['last_name'];
        $this->email = $user_data['email'];
        $this->country = $user_data['country'];
        $this->avatar = $user_data['avatar'];
        $this->group = (int) $user_data['group'];

        $_SESSION['hash'] = $this->passwordLib->createPasswordHash($_SERVER['SERVER_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

        $sth = $this->pdo->prepare('UPDATE hf_user SET hash = :hash WHERE id = :id LIMIT 1');
        $sth->bindParam(':id', $this->id);
        $sth->bindParam(':hash', $_SESSION['hash']);
        $sth->execute();
        $sth = null;

        $this->updateLastSignIn();

        return true;
    }

    /**
     * Деавторизация пользователя
     *
     * @return bool
     */
    public function signOut()
    {
        if ($this->isLogged()) {
            unset($_SESSION['hash']);
        }

        return true;
    }

    /**
     * Авторизация пользователя по хэшу
     *
     * @param $hash
     *
     * @return bool
     * @throws ExceptionImproved
     */
    public function signInByHash($hash)
    {
        if (!$hash) {
            throw new \ExceptionImproved('Не передан hash');
        }

        $sth = $this->pdo->prepare('SELECT * FROM hf_user WHERE hash = :hash LIMIT 1');
        $sth->bindParam(':hash', $hash);
        $sth->execute();

        if (!$sth->rowCount()) {
            return false;
        }

        $user_data = $sth->fetch();
        $this->id = $user_data['id'];
        $this->first_name = $user_data['first_name'];
        $this->last_name = $user_data['last_name'];
        $this->email = $user_data['email'];
        $this->country = $user_data['country'];
        $this->avatar = $user_data['avatar'];
        $this->group = (int) $user_data['group'];

        $this->updateLastSignIn();

        return true;
    }

    /**
     * Получение информации о пользователе (индентификация по email)
     *
     * @param string $email электронный адрес пользователя
     * @throws Exception если email пуст/некорретен
     * @return array информация о пользователе
     */
    public function getByEmail($email)
    {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (!$email) {
            throw new \ExceptionImproved('Введите корретный email, пожалуйста');
        }

        $sth = $this->pdo->prepare('SELECT * FROM hf_user WHERE email = :email LIMIT 1');
        $sth->bindParam(':email', $email);
        $sth->execute();

        if (!$sth->rowCount()) {
            return array();
        }

        return $sth->fetch();
    }

    /**
     * Получение информации о пользователе (индентификация по ID)
     *
     * @param int $id идентификационный номер пользователя (ID)
     * @return array информация о пользователе
     */
    public function get($id = 0)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        $sth = $this->pdo->prepare('SELECT * FROM hf_user WHERE id = :id LIMIT 1');
        $id ? $sth->bindParam(':id', $id) : $sth->bindParam(':id', $this->id);
        $sth->execute();

        if (!$sth->rowCount()) {
            return array();
        }

        $user_data = $sth->fetch();

        $user_data['reg_date'] = date('d.m.Y, H:i', $user_data['reg_date']);
        $user_data['last_signin'] = date('d.m.Y, H:i', $user_data['last_signin']);

        return $user_data;
    }

    /**
     * Установление имени пользователя
     *
     * @param string $first_name имя пользователя
     * @throws Exception если $first_name пуст
     * @return bool
     */
    public function setFirstName($first_name)
    {
        if (!$this->isLogged()) {
            return false;
        }

        $first_name = filter_var($first_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$first_name) {
            throw new \ExceptionImproved('Введите корректное имя, пожалуйста');
        }

        $sth = $this->pdo->prepare('UPDATE hf_user SET first_name = :first_name WHERE id = :id LIMIT 1');
        $sth->bindParam(':first_name', $first_name);
        $sth->bindParam(':id', $this->id);
        $sth->execute();
        $sth = null;

        return true;
    }

    public function setLastName($last_name)
    {
        if (!$this->isLogged()) {
            return false;
        }

        $last_name = filter_var($last_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$last_name) {
            throw new \ExceptionImproved('Введите корректную фамилию, пожалуйста');
        }

        $sth = $this->pdo->prepare('UPDATE hf_user SET last_name = :last_name WHERE id = :id LIMIT 1');
        $sth->bindParam(':last_name', $last_name);
        $sth->bindParam(':id', $this->id);
        $sth->execute();
        $sth = null;

        return true;
    }

    public function setCountry($country)
    {
        if (!$this->isLogged()) {
            return false;
        }

        $country = filter_var($country, FILTER_SANITIZE_NUMBER_INT);

        if (!$country) {
            throw new \ExceptionImproved('Введите корректную фамилию, пожалуйста');
        }

        $sth = $this->pdo->prepare('UPDATE hf_user SET country = :country WHERE id = :id LIMIT 1');
        $sth->bindParam(':country', $country);
        $sth->bindParam(':id', $this->id);
        $sth->execute();
        $sth = null;

        return true;
    }

    public function setEmail($email)
    {
        if (!$this->isLogged()) {
            return false;
        }

        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (!$email) {
            throw new \ExceptionImproved ('Введите корректный email, пожалуйста');
        }

        $sth = $this->pdo->prepare('UPDATE hf_user SET email = :email WHERE id = :id LIMIT 1');
        $sth->bindParam(':email', $email);
        $sth->bindParam(':id', $this->id);
        $sth->execute();
        $sth = null;

        return true;
    }

    /**
     * Получение ID пользователя
     *
     * @return int идентификационный номер пользователя (ID)
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Отражает статус пользователь авторизован или нет
     *
     * @return bool
     */
    public function isLogged()
    {
        return $this->id ? true : false;
    }

    /**
     * Определяет забанен пользователь или нет
     *
     * @param $uid идентификационный номер пользователя (ID)
     *
     * @return bool
     * @throws \ExceptionImproved
     */
    public function isBanned($uid)
    {
        $uid = filter_var($uid, FILTER_SANITIZE_NUMBER_INT);

        if (!$uid) {
            throw new \ExceptionImproved('Необходимо указать ID пользователя!');
        }

        $is_banned = false;

        $sth = $this->pdo->prepare('SELECT ban, ban_end FROM hf_user WHERE id = :uid');
        $sth->bindParam(':uid', $uid);
        $sth->execute();
        $ban_data = $sth->fetch();
        $sth = null;

        $is_banned = $ban_data['ban'] ? true : false;

        if ($ban_data['ban_end']) {
            $is_banned = time() > $ban_data['ban_end'] ? false : true;
        }

        if (!$is_banned && $ban_data['ban_end']) {
            $sth = $this->pdo->prepare('UPDATE hf_user SET ban = 0, ban_end = 0, ban_reason = "" WHERE id = :uid');
            $sth->bindParam(':uid', $uid);
            $sth->execute();
            $sth = null;
        }

        return $is_banned;
    }

    /**
     * Удаление пользователя
     * @param int идентификационный номер пользователя (ID)
     */
    public function delete($id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        if ($id == 0) {
            throw new \ExceptionImproved('Необходимо указать ID пользователя!');
        }

        if ($this->group !== 1) {
            throw new \ExceptionImproved('У Вас нет прав для данной операции!');
        }
        // TODO Прежде чем удалять пользователя, нужно удалить его аватар, файлы и папки
        $sth = $this->pdo->prepare('DELETE FROM hf_user WHERE id = :id LIMIT 1');
        $sth->bindParam(':id', $id);
        $sth->execute();
        $sth = null;
    }

    public function update($id, array $data)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        unset($data['id']);

        if ($id == 0) {
            throw new \ExceptionImproved('Необходимо указать ID пользователя!');
        }

        if (count($data) == 0) {
            throw new \ExceptionImproved('Не передан массив параметров, которые необходимо обновить');
        }

        foreach ($data as $column => $value) {
            $line = isset($line) ? $line . ', ' : '';
            $line .= '`' . $column . '` = :' . $column;
        }

        $data['id'] = $id;

        $sth = $this->pdo->prepare('UPDATE hf_user SET ' . $line . ' WHERE id = :id');
        $sth->execute($data);
        $sth = null;
    }

    /**
     * Обновление даты последней авторизации/активности пользователя
     *
     * @return bool
     */
    private function updateLastSignIn()
    {
        if (!$this->isLogged()) {
            return false;
        }

        $this->userLog();

        $this->last_signin = time();

        $sth = $this->pdo->prepare('UPDATE hf_user SET last_signin = :last_signin WHERE id = :id LIMIT 1');
        $sth->bindParam(':id', $this->id);
        $sth->bindParam(':last_signin', $this->last_signin);
        $sth->execute();
        $sth = null;

        return true;
    }

    private function userLog()
    {
        if (!$this->id) {
            return false;
        }

        $time = time();
        $uid = $this->id;
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $ip = $_SERVER['REMOTE_ADDR'];
        $browser_data = get_browser(null, true);
        $browser = $browser_data['browser'] . ' ' . $browser_data['version'];
        $os = $browser_data['platform'];
        $engine = isset($browser_data['renderingengine_name']) ? $browser_data['renderingengine_name'] : '';
        $sec_in_hour = 3600;

        $sth = $this->pdo->prepare('SELECT last_signin FROM hf_user WHERE id = :uid');
        $sth->bindParam(':uid', $uid);
        $sth->execute();
        $result = $sth->fetch();

        if ((time() - $result['last_signin']) < $sec_in_hour) {
            return false;
        }

        $sth = $this->pdo->prepare('INSERT INTO hf_user_log SET time = :time, uid = :uid, referer = :referer, ip = :ip, browser = :browser, os = :os, engine = :engine');
        $sth->bindParam(':time', $time);
        $sth->bindParam(':uid', $uid);
        $sth->bindParam(':referer', $referer);
        $sth->bindParam(':ip', $ip);
        $sth->bindParam(':browser', $browser);
        $sth->bindParam(':os', $os);
        $sth->bindParam(':engine', $engine);
        $sth->execute();
        $sth = null;

        return true;
    }

    /**
     * @var int идентификационный номер пользователя
     */
    private $id = 0;

    /**
     * @var string Имя
     */
    private $first_name = '';

    /**
     * @var string Фамилия
     */
    private $last_name = '';

    /**
     * @var string email
     */
    private $email = '';

    /**
     * @var int ID страны
     */
    private $country = 0;

    /**
     * @var string аватар
     */
    private $avatar = '';

    /**
     * @var int время (timestamp) последнего посещения сайта
     */
    private $last_signin = 0;

    /**
     * @var int группа, к которой принадлежит пользователь
     */
    private $group = 0;

    /**
     * @var PDO
     */
    private $pdo = null;

    /**
     * @var null|\PasswordLib\PasswordLib
     */
    private $passwordLib = null;

    /**
     * @return string|bool
     *
     * Возвращает двухзначный код страны или false если ip адрес не найден
     */
    public function get_geoip_country(){
        if(!isset($_SESSION['GEOIP_COUNTRY']))
            $_SESSION['GEOIP_COUNTRY'] = @geoip_country_code_by_name($_SERVER['REMOTE_ADDR']);

        return $_SESSION['GEOIP_COUNTRY'];
    }
}