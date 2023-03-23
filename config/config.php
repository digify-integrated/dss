<?php
# -------------------------------------------------------------
#
# Name       : date_default_timezone_set
# Purpose    : This sets the default timezone to PH.
#
# -------------------------------------------------------------

date_default_timezone_set('Asia/Manila');

# -------------------------------------------------------------
#
# Name       : Database Connection
# Purpose    : This is the place where your database login constants are saved
#
#              DB_HOST: database host, usually it's '127.0.0.1' or 'localhost', some servers also need port info
#              DB_NAME: name of the database. please note: database and database table are not the same thing
#              DB_USER: user for your database. the user needs to have rights for SELECT, UPDATE, DELETE and INSERT.
#              DB_PASS: the password of the above user
#
# -------------------------------------------------------------

define('DB_HOST', 'localhost');
define('DB_NAME', 'dssdb');
define('DB_USER', 'dis');
define('DB_PASS', 'qKHJpbkgC6t93nQr');

# -------------------------------------------------------------
#
# Name       : Encryption Key
# Purpose    : This is the serves as the encryption and decryption key of RC
#
# -------------------------------------------------------------

define('ENCRYPTION_KEY', 'DmXUT96VLxqENzLZks4M');

# -------------------------------------------------------------
#
# Name       : Default user interface image
# Purpose    : This is the serves as the default images for the user interface.
#
# -------------------------------------------------------------

define('DEFAULT_AVATAR_IMAGE', './assets/images/default/default-avatar.png');
define('DEFAULT_BG_IMAGE', './assets/images/default/default-bg.jpg');
define('DEFAULT_LOGIN_LOGO_IMAGE', './assets/images/default/default-login-logo.png');
define('DEFAULT_MENU_LOGO_IMAGE', './assets/images/default/default-menu-logo.png');
define('DEFAULT_MODULE_ICON_IMAGE', './assets/images/default/default-module-icon.svg');
define('DEFAULT_FAVICON_IMAGE', './assets/images/default/default-favicon.png');
define('DEFAULT_COMPANY_LOGO_IMAGE', './assets/images/default/default-company-logo.png');
define('DEFAULT_PLACEHOLDER_IMAGE', './assets/images/default/default-image-placeholder.png');

# -------------------------------------------------------------
#
# Name       : Default upload file path
# Purpose    : This is the serves as the default upload file path.
#
# -------------------------------------------------------------

define('DEFAULT_IMAGES_FULL_PATH_FILE', '/dss/assets/images/');
define('DEFAULT_IMAGES_RELATIVE_PATH_FILE', './assets/images/');
define('DEFAULT_EMPLOYEE_FULL_PATH_FILE', '/dss/assets/employee/');
define('DEFAULT_EMPLOYEE_RELATIVE_PATH_FILE', './assets/employee/');

?>