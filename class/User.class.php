<?php

namespace User;


class User
{
    public $currentCountryId;
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;

        require_once(DIR_CLASS . 'password.php');

        $this->updateLastSignIn();

        $this->currentCountryId = self::get_countryId_by_code2(self::get_geoip_country());
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
     * @return int идентификационный номер добавленого или обновленно пользователя
     */
    public function signUp($first_name, $email, $country = 0, $password = '', $last_name = '')
    {
        $email      = filter_var($email, FILTER_VALIDATE_EMAIL);
        $country    = filter_var($country, FILTER_SANITIZE_NUMBER_INT);
        $first_name = filter_var($first_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_name  = filter_var($last_name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $reg_date   = time();

        if (!$first_name) {
            throw new \ExceptionImproved('Введите Ваше имя, пожалуйста');
        }

        if (!$email) {
            throw new \ExceptionImproved('Введите Ваш email, пожалуйста');
        }

        if ($password) {
            if (function_exists('password_hash')) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
            }
            else {
                $hash = $this->passwordLib->createPasswordHash($password);
            }
        }
        else {
            $hash = '';
        }


        if($this->isLogged() && $this->group == 0){
            $updateSql = <<<SQL
UPDATE
  hf_user
SET
  email = :email,
  country = :country,
  password = :password,
  first_name = :first_name,
  last_name = :last_name,
  reg_date = :reg_date,
  `group` = 2
WHERE
  id = :id
SQL;
            $updateStmt = $this->pdo->prepare($updateSql);
            $updateStmt->execute([
                'email' => $email,
                'country' => $country,
                'password' => $hash,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'reg_date' => $reg_date,
                'id' => $this->id,
            ]);

            $ret =  $this->id;

        } else {
            $sth = $this->pdo->prepare('INSERT INTO hf_user SET email = :email, country = :country, password = :password, first_name = :first_name, last_name = :last_name, reg_date = :reg_date, group = 2');
            $sth->bindParam(':email', $email);
            $sth->bindParam(':country', $country);
            $sth->bindParam(':password', $hash);
            $sth->bindParam(':first_name', $first_name);
            $sth->bindParam(':last_name', $last_name);
            $sth->bindParam(':reg_date', $reg_date);
            $sth->execute();

            $ret = $this->pdo->lastInsertId();
        }

        $this->signIn($email, $password);
        return $ret;
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
    public function signIn($email, $password = '')
    {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (!$email) {
            throw new \ExceptionImproved('Введите Ваш email, пожалуйста');
        }

        $user_data = $this->getByEmail($email);

        if (!$user_data) {
            throw new \ExceptionImproved('Не верный email или пароль');
        }

        if (function_exists('password_verify')) {
            $password_verify = password_verify($password, $user_data['password']);
        }
        else {
            $password_verify = $this->passwordLib->verifyPasswordHash($password, $user_data['password']);
        }


        if ($password && !$password_verify) {
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

        if (function_exists('password_hash')) {
            $hash = password_hash($_SERVER['SERVER_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . uniqid() . $this->getId(), PASSWORD_DEFAULT);
        }
        else {
            $hash = $this->passwordLib->createPasswordHash($_SERVER['SERVER_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . uniqid());
        }
        setcookie('hash', $hash, time() + (60 * 60 * 24 * 365));

        $sth = $this->pdo->prepare('UPDATE hf_user SET hash = :hash WHERE id = :id LIMIT 1');
        $sth->bindParam(':id', $this->id);
        $sth->bindParam(':hash', $hash);
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
            setcookie('hash', '', time() - 3600);
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

        $sth = $this->pdo->prepare('SELECT u.*, c.name country_name FROM hf_user u LEFT JOIN hf_country c ON u.country = c.id WHERE u.id = :id LIMIT 1');
        $id ? $sth->bindParam(':id', $id) : $sth->bindParam(':id', $this->id);
        $sth->execute();

        if (!$sth->rowCount()) {
            return array();
        }

        $user_data = $sth->fetch();

        $user_data['reg_date'] = date('d.m.Y, H:i', $user_data['reg_date']);
        $user_data['last_signin'] = date('d.m.Y, H:i', $user_data['last_signin']);

        $user_data['currentCountryId'] = $this->currentCountryId;

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

    public function setAvatar($file_name)
    {
        if (!$this->isLogged()) {
            throw new \ExceptionImproved('Данная операция требует авторизации');
        }

        $uid = $this->getId();

        $sth = $this->pdo->prepare('UPDATE hf_user SET avatar = :file_name WHERE id = :uid LIMIT 1');
        $sth->bindParam(':file_name', $file_name);
        $sth->bindParam(':uid', $uid);
        $sth->execute();
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
        if (!$this->getId()) {
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
     * @var \PDO
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
    public static function get_geoip_country(){
        if(!isset($_SESSION['GEOIP_COUNTRY']))
            $_SESSION['GEOIP_COUNTRY'] = @geoip_country_code_by_name($_SERVER['REMOTE_ADDR']);

        return $_SESSION['GEOIP_COUNTRY'];
    }

    public static function get_countryId_by_code2($code2){
        global $pdo;
        $sql = <<<SQL
SELECT
  id
FROM
  hf_country
WHERE
  code = :code
LIMIT 1
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['code' => $code2]);

        if($stmt->rowCount() == 1){
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return intval($row['id']);
        } else {
            return 1;
        }
    }

    public static function createGuestAccount(&$createdHash = null){
        global $pdo;

        $createGuestSql = <<<SQL
INSERT INTO
  hf_user
SET
  reg_date = UNIX_TIMESTAMP()
SQL;
        $pdo->exec($createGuestSql);
        $newGuestId = $pdo->lastInsertId();

        $updateHashSql = <<<SQL
UPDATE
  hf_user
SET
  hash = :hash
WHERE
  id = :id
SQL;
        $updateHashStmt = $pdo->prepare($updateHashSql);
        $updateHashStmt->execute([
            'hash' => ($createdHash = password_hash($_SERVER['SERVER_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . uniqid() . $newGuestId, PASSWORD_DEFAULT)),
            'id' => $newGuestId
        ]);

        setcookie('hash', $createdHash, time() + (60 * 60 * 24 * 365));
    }

    public function getGroup(){
        return $this->group;
    }

    /**
     * @param $provider
     * @param $userProfile
     * @param bool $wasCreated
     * @return mixed
     * @throws \Exception|\PDOException
     */
    public static function SignUpOrGetByProvider($provider, $userProfile, &$wasInserted = false){
        global $pdo, $_user;

        $selectByProviderSql = <<<SQL
SELECT
  *
FROM
  hf_user
WHERE
  provider = :provider AND provider_identifier = :provider_identifier
SQL;

        $emailDbValue = !empty($userProfile['emailVerified']) ? $pdo->quote(trim($userProfile['emailVerified'])) : 'NULL';
        $insertSql = <<<SQL
INSERT INTO
  hf_user
SET
  email = $emailDbValue,
  first_name = :first_name,
  last_name = :last_name,
  reg_date = UNIX_TIMESTAMP(),
  `group` = 2,
  country = :country,
  provider = :provider,
  provider_identifier = :provider_identifier
SQL;
        $insertStmt = $pdo->prepare($insertSql);

        if($_user->isLogged() && $_user->group == 0){

            // убедимся что в базе нет пользователя с такими данными провайдера
            $userFound = self::GetByProvider($provider, $userProfile);
            if(!empty($userFound))
                return $userFound;

            $updateSql = <<<SQL
UPDATE
  hf_user
SET
  email = $emailDbValue,
  country = :country,
  first_name = :first_name,
  last_name = :last_name,
  reg_date = UNIX_TIMESTAMP(),
  `group` = 2,
  provider = :provider,
  provider_identifier = :provider_identifier
WHERE
  id = :id
SQL;
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([
                'country' => self::get_countryId_by_code2(self::get_geoip_country()),
                'first_name' => !empty($userProfile['firstName']) ? trim($userProfile['firstName']) : '',
                'last_name' => !empty($userProfile['lastName']) ? trim($userProfile['lastName']) : '',
                'provider' => $provider,
                'provider_identifier' => $userProfile['identifier'],
                'id' => $_user->getId(),
            ]);

            if (!empty($userProfile['photoURL'])) {
                $photo = file_get_contents($userProfile['photoURL']);
                $tmp_name = uniqid();
                file_put_contents(DIR_UPLOAD . '/tmp/' . $tmp_name, $photo);
                require_once DIR_CLASS . 'phpthumb/ThumbLib.inc.php';
                $phpThumb = \PhpThumbFactory::create(DIR_UPLOAD . '/tmp/' . $tmp_name);
                $phpThumb->adaptiveResize(150, 150)->save(DIR_UPLOAD . 'avatar/avatar-' . $_user->getId() . '.jpg', 'jpg');
                $phpThumb->adaptiveResize(32, 32)->save(DIR_UPLOAD . 'avatar/_thumb/avatar-' . $_user->getId() . '.jpg', 'jpg');

                $setAvatarStmt = $pdo->prepare('UPDATE hf_user SET avatar = :file_name WHERE id = :uid LIMIT 1');
                $setAvatarStmt->execute([
                    'file_name' => 'avatar-' . $_user->getId() . '.jpg',
                    'uid' => $_user->getId()
                ]);
                unlink(DIR_UPLOAD . '/tmp/' . $tmp_name);
            }

            $selectByIdStmt = $pdo->query("SELECT * FROM hf_user WHERE id = " . $_user->getId());
            return $selectByIdStmt->fetch();

        } else {
            try{
                $insertStmt->execute([
                    'first_name' => !empty($userProfile['firstName']) ? trim($userProfile['firstName']) : '',
                    'last_name' => !empty($userProfile['lastName']) ? trim($userProfile['lastName']) : '',
                    'country' => self::get_countryId_by_code2(self::get_geoip_country()),
                    'provider' => $provider,
                    'provider_identifier' => $userProfile['identifier'],
                ]);

                $newUserId = $pdo->lastInsertId();
                $wasInserted = true;

                if (!empty($userProfile['photoURL'])) {
                    $photo = file_get_contents($userProfile['photoURL']);
                    $tmp_name = uniqid();
                    file_put_contents(DIR_UPLOAD . '/tmp/' . $tmp_name, $photo);
                    require_once DIR_CLASS . 'phpthumb/ThumbLib.inc.php';
                    $phpThumb = \PhpThumbFactory::create(DIR_UPLOAD . '/tmp/' . $tmp_name);
                    $phpThumb->adaptiveResize(150, 150)->save(DIR_UPLOAD . 'avatar/avatar-' . $newUserId . '.jpg', 'jpg');
                    $phpThumb->adaptiveResize(32, 32)->save(DIR_UPLOAD . 'avatar/_thumb/avatar-' . $newUserId . '.jpg', 'jpg');

                    $setAvatarStmt = $pdo->prepare('UPDATE hf_user SET avatar = :file_name WHERE id = :uid LIMIT 1');
                    $setAvatarStmt->execute([
                        'file_name' => 'avatar-' . $newUserId . '.jpg',
                        'uid' => $newUserId
                    ]);
                    unlink(DIR_UPLOAD . '/tmp/' . $tmp_name);
                }

                $selectByIdSql = <<<SQL
SELECT * FROM hf_user WHERE id = $newUserId
SQL;
                $selectByIdStmt = $pdo->query($selectByIdSql);
                return $selectByIdStmt->fetch();

            }catch(\PDOException $e){
                if($e->errorInfo[1] != 1062) // Duplicate entry code
                throw $e;
            }
        }

        $selectByProviderStmt = $pdo->prepare($selectByProviderSql);
        $selectByProviderStmt->execute([
            'provider' => $provider,
            'provider_identifier' => $userProfile['identifier']
        ]);

        return $selectByProviderStmt->fetch();
    }

    public function SignInByProvider($provider, $userProfile){
        $selectByProviderSql = <<<SQL
SELECT
  *
FROM
  hf_user
WHERE
  provider = :provider AND provider_identifier = :provider_identifier
SQL;
        $selectByProviderStmt = $this->pdo->prepare($selectByProviderSql);
        $selectByProviderStmt->execute([
            'provider' => $provider,
            'provider_identifier' => $userProfile['identifier']
        ]);

        $user_data = $selectByProviderStmt->fetch();
        if(empty($user_data))
            return false;

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

        if (function_exists('password_hash')) {
            $hash = password_hash($_SERVER['SERVER_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . uniqid() . $this->getId(), PASSWORD_DEFAULT);
        }
        else {
            $hash = $this->passwordLib->createPasswordHash($_SERVER['SERVER_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . uniqid());
        }
        setcookie('hash', $hash, time() + (60 * 60 * 24 * 365));

        $sth = $this->pdo->prepare('UPDATE hf_user SET hash = :hash WHERE id = :id LIMIT 1');
        $sth->bindParam(':id', $this->id);
        $sth->bindParam(':hash', $hash);
        $sth->execute();
        $sth = null;

        $this->updateLastSignIn();

        return true;
    }

    public static function GetByProvider($provider, $userProfile){
        global $pdo;

        $selectByProviderSql = <<<SQL
SELECT
  *
FROM
  hf_user
WHERE
  provider = :provider AND provider_identifier = :provider_identifier
SQL;
        $selectByProviderStmt = $pdo->prepare($selectByProviderSql);
        $selectByProviderStmt->execute([
            'provider' => $provider,
            'provider_identifier' => $userProfile['identifier']
        ]);

        return $selectByProviderStmt->fetch();
    }
}