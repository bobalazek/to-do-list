<?php

if (! defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (! defined('ROOT_DIR')) {
    define('ROOT_DIR', rtrim(dirname(dirname(dirname(__FILE__))), DS));
}

if (! defined('APP_DIR')) {
    define('APP_DIR', ROOT_DIR.'/app');
}

if (! defined('STORAGE_DIR')) {
    define('STORAGE_DIR', ROOT_DIR.'/var');
}

if (! defined('VENDOR_DIR')) {
    define('VENDOR_DIR', ROOT_DIR.'/vendor');
}

if (! defined('WEB_DIR')) {
    define('WEB_DIR', ROOT_DIR.'/web');
}
