/* Create Table */
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

CREATE TABLE technical_module(
	MODULE_ID VARCHAR(100) PRIMARY KEY,
	MODULE_NAME VARCHAR(200) NOT NULL,
	MODULE_VERSION VARCHAR(20) NOT NULL,
	MODULE_DESCRIPION VARCHAR(500),
	MODULE_ICON VARCHAR(500),
	MODULE_CATEGORY VARCHAR(50),
	IS_INSTALLABLE VARCHAR(50),
	IS_APPLICATION VARCHAR(50),
	TRANSACTION_LOG_ID VARCHAR(100),
	ORDER_SEQUENCE INT
);

CREATE TABLE technical_model(
	MODEL_ID VARCHAR(100) PRIMARY KEY,
	MODULE_ID VARCHAR(200) NOT NULL,
	MODEL_NAME VARCHAR(100),
	MODEL_DESCRIPTION VARCHAR(500),
	MODEL LONGTEXT,
	TRANSACTION_LOG_ID VARCHAR(100),
	ORDER_SEQUENCE INT
);

CREATE TABLE technical_menu(
	MENU_ID VARCHAR(100) PRIMARY KEY,
	MODULE_ID VARCHAR(200) NOT NULL,
	MENU VARCHAR(100) NOT NULL,
	MENU_WEB_ICON VARCHAR(500),
	TRANSACTION_LOG_ID VARCHAR(100),
	ORDER_SEQUENCE INT
);

CREATE TABLE technical_submenu(
	MENU_ID VARCHAR(100) NOT NULL,
	SUBMENU_ID VARCHAR(100) NOT NULL
);

CREATE TABLE technical_view(
	VIEW_ID VARCHAR(100) PRIMARY KEY,
	VIEW_NAME VARCHAR(200) NOT NULL,
	VIEW_TYPE VARCHAR(20) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	ORDER_SEQUENCE INT
);

CREATE TABLE technical_view_architecture(
	VIEW_ID VARCHAR(100) PRIMARY KEY,
	VIEW_NAME LONGTEXT NOT NULL
);

/* Index */

CREATE INDEX global_user_account_index ON global_user_account(USERNAME);
CREATE INDEX global_transaction_log_index ON global_transaction_log(TRANSACTION_LOG_ID);
CREATE INDEX technical_module_index ON technical_module(MODULE_ID);
CREATE INDEX technical_model_index ON technical_model(MODEL_ID);
CREATE INDEX technical_menu_index ON technical_menu(MENU_ID);
CREATE INDEX technical_view_index ON technical_view(VIEW_ID);

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

/* Insert Transaction */

INSERT INTO global_user_account (USERNAME, PASSWORD, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_FAILED_LOGIN, TRANSACTION_LOG_ID) VALUES ('ADMIN', '68aff5412f35ed76', 'Administrator', 'Active', '2022-12-30', 0, null, 'TL-1');