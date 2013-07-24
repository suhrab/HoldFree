<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Anton
 * Date: 7/23/13
 * Time: 5:52 PM
 * To change this template use File | Settings | File Templates.
 */

$language_code = isset($_GET['l']) ? trim($_GET['l']) : '';

if (!$language_code) {
    throw new Exception('Language code is not defined.', 1);
}

if (!array_key_exists($language_code, $available_languages)) {
    throw new Exception('Unknown language code.', 2);
}

setcookie('language', $language_code, time() + (60 * 60 * 24 * 30 * 12));

$ref = '/';
if(!empty($_SERVER['HTTP_REFERER'])){
    $ref = $_SERVER['HTTP_REFERER'];
} elseif(!empty($_GET['ref'])) {
    $ref = $_GET['ref'];
}

header('Location: ' . $ref);
exit;