<?php

if (!defined('CHECK')) {
    exit;
}


if ($action == 'optimize') {
    die('{"success": "true"}');
}
elseif ($action == 'repair') {

}
elseif ($action == 'repair') {

}
elseif ($action == 'dump') {

}
elseif ($action == 'load') {

}

if ($is_ajax) {
    die('{"success": "true"}');
}


$smarty->display('_dashboard/database.tpl');