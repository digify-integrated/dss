/* Create Table */
CREATE TABLE technical_module(
	MODULE_ID VARCHAR(100) PRIMARY KEY,
	MODULE_NAME VARCHAR(200) NOT NULL,
	MODULE_VERSION VARCHAR(20) NOT NULL,
	MODULE_DESCRIPION VARCHAR(500),
	MODULE_ICON VARCHAR(500),
	MODULE_CATEGORY VARCHAR(50),
	IS_INSTALLABLE TINYINT(1) NOT NULL,
	IS_APPLICATION TINYINT(1) NOT NULL,
	IS_INSTALLED TINYINT(1) NOT NULL,
	INSTALLATION_DATE DATETIME,
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	ORDER_SEQUENCE INT
);

CREATE TABLE technical_module_access_rights(
	MODULE_ID VARCHAR(100) PRIMARY KEY,
	ROLE_ID VARCHAR(100) NOT NULL
);

CREATE TABLE technical_model(
	MODEL_ID VARCHAR(100) PRIMARY KEY,
	MODULE_ID VARCHAR(200) NOT NULL,
	MODEL_NAME VARCHAR(100) NOT NULL,
	MODEL_DESCRIPTION VARCHAR(500),
	MODEL LONGTEXT,
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	ORDER_SEQUENCE INT
);

CREATE TABLE technical_menu(
	MENU_ID VARCHAR(100) PRIMARY KEY,
	MODULE_ID VARCHAR(200) NOT NULL,
	PARENT_MENU VARCHAR(100),
	MENU VARCHAR(100) NOT NULL,
	MENU_ICON VARCHAR(50),
	MENU_WEB_ICON VARCHAR(500),
	IS_LINK TINYINT(1) NOT NULL,
	MENU_LINK VARCHAR(500),
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	ORDER_SEQUENCE INT
);

CREATE TABLE technical_menu_view(
	MENU_ID VARCHAR(100) PRIMARY KEY,
	VIEW_ID VARCHAR(100) NOT NULL
);

CREATE TABLE technical_menu_access_rights(
	MENU_ID VARCHAR(100) PRIMARY KEY,
	ROLE_ID VARCHAR(100) NOT NULL
);

CREATE TABLE technical_view(
	VIEW_ID VARCHAR(100) PRIMARY KEY,
	VIEW_NAME VARCHAR(200) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	ORDER_SEQUENCE INT
);

CREATE TABLE technical_view_access_rights(
	VIEW_ID VARCHAR(100) PRIMARY KEY,
	ROLE_ID VARCHAR(100) NOT NULL
);

CREATE TABLE technical_view_architecture(
	VIEW_ID VARCHAR(100) PRIMARY KEY,
	ARCHITECHTURE LONGTEXT NOT NULL
);

CREATE TABLE technical_view_javascript(
	VIEW_ID VARCHAR(100) PRIMARY KEY,
	JAVASCRIPT LONGTEXT NOT NULL
);

CREATE TABLE technical_view_css(
	VIEW_ID VARCHAR(100) PRIMARY KEY,
	JAVASCRIPT LONGTEXT NOT NULL
);

CREATE TABLE global_system_code(
	SYSTEM_TYPE VARCHAR(20) NOT NULL,
	SYSTEM_CODE VARCHAR(20) NOT NULL,
	SYSTEM_DESCRIPTION VARCHAR(100) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_role(
	ROLE_ID VARCHAR(50) PRIMARY KEY,
	ROLE VARCHAR(100) NOT NULL,
	ROLE_DESCRIPTION VARCHAR(200) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_role_user_account(
	ROLE_ID VARCHAR(50) NOT NULL,
	USERNAME VARCHAR(50) NOT NULL
);

CREATE TABLE global_user_account(
	USERNAME VARCHAR(50) PRIMARY KEY,
	PASSWORD VARCHAR(200) NOT NULL,
	FILE_AS VARCHAR(300) NOT NULL,
	USER_STATUS VARCHAR(10) NOT NULL,
	PASSWORD_EXPIRY_DATE DATE NOT NULL,
	FAILED_LOGIN INT(1) NOT NULL,
	LAST_FAILED_LOGIN DATETIME,
	LAST_CONNECTION_DATE DATETIME,
    TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_transaction_log( 
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	USERNAME VARCHAR(50) NOT NULL,
	LOG_TYPE VARCHAR(100) NOT NULL,
	LOG_DATE DATETIME NOT NULL,
	LOG VARCHAR(4000)
);

/* Index */

CREATE INDEX global_user_account_index ON global_user_account(USERNAME);
CREATE INDEX global_transaction_log_index ON global_transaction_log(TRANSACTION_LOG_ID);
CREATE INDEX technical_module_index ON technical_module(MODULE_ID);
CREATE INDEX technical_model_index ON technical_model(MODEL_ID);
CREATE INDEX technical_menu_index ON technical_menu(MENU_ID);
CREATE INDEX technical_view_index ON technical_view(VIEW_ID);
CREATE INDEX global_system_code_index ON global_system_code(SYSTEM_TYPE, SYSTEM_CODE);
CREATE INDEX global_role_index ON global_role(ROLE_ID);

/* Stored Procedure */

CREATE PROCEDURE check_user_account_exist(IN username VARCHAR(50))
BEGIN
	SET @username = username;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_user_account WHERE BINARY USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_user_account_details(IN username VARCHAR(50))
BEGIN
	SET @username = username;

	SET @query = 'SELECT PASSWORD, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_FAILED_LOGIN, LAST_CONNECTION_DATE, TRANSACTION_LOG_ID, RECORD_LOG FROM global_user_account WHERE USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_login_attempt(IN username VARCHAR(50), login_attemp INT(1), last_failed_attempt_date DATETIME)
BEGIN
	SET @username = username;
	SET @login_attemp = login_attemp;
	SET @last_failed_attempt_date = last_failed_attempt_date;

    IF @login_attemp > 0 THEN
		SET @query = 'UPDATE global_user_account SET FAILED_LOGIN = @login_attemp, LAST_FAILED_LOGIN = @last_failed_attempt_date WHERE USERNAME = @username';
	ELSE
		SET @query = 'UPDATE global_user_account SET FAILED_LOGIN = @login_attemp WHERE USERNAME = @username';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_user_last_connection(IN username VARCHAR(50), last_connection_date DATETIME)
BEGIN
	SET @username = username;
	SET @last_connection_date = last_connection_date;

	SET @query = 'UPDATE global_user_account SET LAST_CONNECTION_DATE = @last_connection_date WHERE USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_user_account_password(IN username VARCHAR(50), password VARCHAR(200), password_expiry_date DATE)
BEGIN
	SET @username = username;
	SET @password = password;
	SET @password_expiry_date = password_expiry_date;

	SET @query = 'UPDATE global_user_account SET PASSWORD = @password, PASSWORD_EXPIRY_DATE = @password_expiry_date WHERE USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_transaction_log(IN transaction_log_id VARCHAR(100), IN username VARCHAR(50), log_type VARCHAR(100), log_date DATETIME, log VARCHAR(4000))
BEGIN
	SET @transaction_log_id = transaction_log_id;
	SET @username = username;
	SET @log_type = log_type;
	SET @log_date = log_date;
	SET @log = log;

	SET @query = 'INSERT INTO global_transaction_log (TRANSACTION_LOG_ID, USERNAME, LOG_TYPE, LOG_DATE, LOG) VALUES(@transaction_log_id, @username, @log_type, @log_date, @log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_access_rights_count(IN role_id VARCHAR(50), IN access_right_id VARCHAR(100), IN access_type VARCHAR(10))
BEGIN
	SET @role_id = role_id;
	SET @access_right_id = access_right_id;
	SET @access_type = access_type;

	IF @access_type = 'module' THEN
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_module_access_rights WHERE MODULE_ID = @access_right_id AND ROLE_ID = @role_id';
	ELSEIF @access_type = 'menu' THEN
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_menu_access_rights WHERE MENU_ID = @access_right_id AND ROLE_ID = @role_id';
	ELSE
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_view_access_rights WHERE VIEW_ID = @access_right_id AND ROLE_ID = @role_id';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

/* Insert Transaction */

INSERT INTO global_user_account (USERNAME, PASSWORD, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_FAILED_LOGIN, TRANSACTION_LOG_ID) VALUES ('ADMIN', '68aff5412f35ed76', 'Administrator', 'Active', '2022-12-30', 0, null, 'TL-1');
INSERT INTO global_role (ROLE_ID, ROLE, ROLE_DESCRIPTION, TRANSACTION_LOG_ID) VALUES ('1', 'Administrator', 'Administrator', 'TL-2');
INSERT INTO technical_module (MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPION, MODULE_CATEGORY, IS_INSTALLABLE, IS_APPLICATION, TRANSACTION_LOG_ID, ORDER_SEQUENCE) VALUES ('1', 'Technical', '1.0.0', 'Administrator Module', 'TECHNICAL', '1', '1', 'TL-3', '99');
INSERT INTO global_system_code (SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID) VALUES ('SYSTYPE', 'SYSTYPE', 'System Code', 'TL-4');
INSERT INTO global_system_code (SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID) VALUES ('SYSTYPE', 'MODULECAT', 'Module Category', 'TL-5');
INSERT INTO global_system_code (SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID) VALUES ('MODULECAT', 'TECHNICAL', 'Technical', 'TL-6');
INSERT INTO technical_menu (MENU_ID, MODULE_ID, MENU, MENU_ICON, TRANSACTION_LOG_ID, ORDER_SEQUENCE) VALUES ('1', '1', 'Settings', 'bx bx-cog', 'TL-7', '1');
INSERT INTO technical_menu (MENU_ID, MODULE_ID, PARENT_MENU, MENU, MENU_ICON, TRANSACTION_LOG_ID, ORDER_SEQUENCE) VALUES ('2', '1', '1', 'Modules', '', 'TL-8', '1');
INSERT INTO technical_menu (MENU_ID, MODULE_ID, PARENT_MENU, MENU, MENU_ICON, TRANSACTION_LOG_ID, ORDER_SEQUENCE) VALUES ('3', '1', '1', 'Models', '', 'TL-9', '2');
INSERT INTO technical_menu (MENU_ID, MODULE_ID, PARENT_MENU, MENU, MENU_ICON, TRANSACTION_LOG_ID, ORDER_SEQUENCE) VALUES ('4', '1', '1', 'Menu Items', '', 'TL-10', '3');
INSERT INTO technical_menu (MENU_ID, MODULE_ID, PARENT_MENU, MENU, MENU_ICON, TRANSACTION_LOG_ID, ORDER_SEQUENCE) VALUES ('5', '1', '1', 'Views', '', 'TL-11', '4');
INSERT INTO technical_menu (MENU_ID, MODULE_ID, PARENT_MENU, MENU, MENU_ICON, TRANSACTION_LOG_ID, ORDER_SEQUENCE) VALUES ('6', '1', '1', 'Plugins', '', 'TL-12', '5');
INSERT INTO technical_submenu (MENU_ID, SUBMENU_ID) VALUES ('1', '2');
INSERT INTO technical_submenu (MENU_ID, SUBMENU_ID) VALUES ('1', '3');
INSERT INTO technical_submenu (MENU_ID, SUBMENU_ID) VALUES ('1', '4');
INSERT INTO technical_submenu (MENU_ID, SUBMENU_ID) VALUES ('1', '5');
INSERT INTO technical_submenu (MENU_ID, SUBMENU_ID) VALUES ('1', '6');
INSERT INTO technical_module_access_rights (MODULE_ID, ROLE_ID) VALUES ('1', '1');
INSERT INTO technical_menu_access_rights (MENU_ID, ROLE_ID) VALUES ('1', '1');
INSERT INTO technical_menu_access_rights (MENU_ID, ROLE_ID) VALUES ('2', '1');
INSERT INTO technical_menu_access_rights (MENU_ID, ROLE_ID) VALUES ('3', '1');
INSERT INTO technical_menu_access_rights (MENU_ID, ROLE_ID) VALUES ('4', '1');
INSERT INTO technical_menu_access_rights (MENU_ID, ROLE_ID) VALUES ('5', '1');
INSERT INTO technical_menu_access_rights (MENU_ID, ROLE_ID) VALUES ('6', '1');
INSERT INTO global_role_user_account (ROLE_ID, USERNAME) VALUES ('1', 'ADMIN');