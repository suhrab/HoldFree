<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


/**
 * Имя пользователя БД
 */
define('DB_USER', 'root');

/**
 * Пароль пользователя БД
 */
define('DB_PASS', '232323');

/**
 * Хось БД
 */
define('DB_HOST', '127.0.0.1');

/**
 * Порт БД
 */
define('DB_PORT', '8889');

/**
 * Имя БД
 */
define('DB_NAME', 'holdfree');

/**
 * URL сайта
 */
define('URL', 'http://holdfree.com');

/**
 * Путь до корневой директории
 */
define('DIR_ROOT', dirname(__FILE__) . '/');

/**
 * Путь до директории с классами
 */
define('DIR_CLASS', DIR_ROOT . 'class/');

/**
 * Путь до директории с модулями
 */
define('DIR_MODULE', DIR_ROOT . 'module/');

/**
 * Путь к директории куда сохраняются загружаемые файлы
 */
define('DIR_UPLOAD', DIR_ROOT . 'upload/');

define('GEARMAN_JOB_SERVER_HOST', 'localhost');