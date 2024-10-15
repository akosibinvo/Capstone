<?php

// Database configuration
//define('DB_HOST', 'localhost'); 
//define('DB_USER', 'u712098628_bevs_user');
//define('DB_PASSWORD', '@Blockchain_evs15');
//define('DB_NAME', 'u712098628_bbevsDB');
define('DB_HOST', 'localhost'); 
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'blockchain_based_evsDB');

//Base URL Configuration
//define('BASE_URL', 'https://blockchain-basedevs.online/');
define('BASE_URL', 'http://localhost/public_html/');

//Root Path
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/public_html/');

// File upload configuration
define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'] . '/uploads/');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png']);

// Encryption key
define('ENCRYPTION_KEY', '969244ad6ff4e2a56d9c9b81bd1760307af90e48e47d99a31a64516e5f8110d6');

?>