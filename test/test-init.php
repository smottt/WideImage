<?php

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

define('TEST_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('IMG_PATH', TEST_PATH . 'images' . DIRECTORY_SEPARATOR);

// check for xdebug scream
if (ini_get('xdebug.scream')) {
    ini_set('xdebug.scream', 0);
}
