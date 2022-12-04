/* Technical Module */
CREATE TABLE technical_module(
	MODULE_ID VARCHAR(100) PRIMARY KEY,
	MODULE_NAME VARCHAR(200) NOT NULL,
	MODULE_VERSION VARCHAR(20) NOT NULL,
	MODULE_DESCRIPTION VARCHAR(500),
	MODULE_ICON VARCHAR(500),
	MODULE_CATEGORY VARCHAR(50),
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	RECORD_LOG VARCHAR(100) NOT NULL,
	ORDER_SEQUENCE INT
);

CREATE TABLE technical_module_access_rights(
	MODULE_ID VARCHAR(100) NOT NULL,
	ROLE_ID VARCHAR(100) NOT NULL
);

CREATE INDEX technical_module_index ON technical_module(MODULE_ID);

INSERT INTO technical_module (MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_CATEGORY, TRANSACTION_LOG_ID, ORDER_SEQUENCE) VALUES ('1', 'Technical', '1.0.0', 'Administrator Module', 'TECHNICAL', 'TL-3', '99');
INSERT INTO technical_module (MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_CATEGORY, TRANSACTION_LOG_ID, ORDER_SEQUENCE) VALUES ('2', 'Technical2', '1.0.0', 'Administrator Module 2', 'TECHNICAL2', 'TL-3', '99');

INSERT INTO technical_module_access_rights (MODULE_ID, ROLE_ID) VALUES ('1', '1');

CREATE PROCEDURE get_module_details(IN module_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;

	SET @query = 'SELECT MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_ICON, MODULE_CATEGORY, TRANSACTION_LOG_ID, RECORD_LOG, ORDER_SEQUENCE FROM technical_module WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_module_exist(IN module_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_module WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_module(IN module_id VARCHAR(100), IN module_name VARCHAR(200), IN module_version VARCHAR(20), IN module_description VARCHAR(500), IN module_category VARCHAR(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @module_id = module_id;
	SET @module_name = module_name;
	SET @module_version = module_version;
	SET @module_description = module_description;
	SET @module_category = module_category;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE technical_module SET MODULE_NAME = @module_name, MODULE_VERSION = @module_version, MODULE_DESCRIPTION = @module_description, MODULE_CATEGORY = @module_category, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_module_icon(IN module_id VARCHAR(100), IN module_icon VARCHAR(500))
BEGIN
	SET @module_id = module_id;
	SET @module_icon = module_icon;

	SET @query = 'UPDATE technical_module SET MODULE_ICON = @module_icon WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_module(IN module_id VARCHAR(100), IN module_name VARCHAR(200), IN module_version VARCHAR(20), IN module_description VARCHAR(500), IN module_category VARCHAR(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @module_id = module_id;
	SET @module_name = module_name;
	SET @module_version = module_version;
	SET @module_description = module_description;
	SET @module_category = module_category;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO technical_module (MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_CATEGORY, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@module_id, @module_name, @module_version, @module_description, @module_category, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_module(IN module_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;

	SET @query = 'DELETE FROM technical_module WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_module_access_exist(IN module_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;
	SET @role_id = role_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_module_access_rights WHERE MODULE_ID = @module_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_module_access(IN module_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;
	SET @role_id = role_id;

	SET @query = 'INSERT INTO technical_module_access_rights (MODULE_ID, ROLE_ID) VALUES(@module_id, @role_id)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_module_access(IN module_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;

	SET @query = 'DELETE FROM technical_module_access_rights WHERE MODULE_ID = @module_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_module_access(IN module_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @module_id = module_id;
	SET @role_id = role_id;

	SET @query = 'DELETE FROM technical_module_access_rights WHERE MODULE_ID = @module_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_module_options()
BEGIN
	SET @query = 'SELECT MODULE_ID, MODULE_NAME FROM technical_module ORDER BY MODULE_NAME';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

/* Technical Action */
CREATE TABLE technical_action(
	ACTION_ID VARCHAR(100) PRIMARY KEY,
	ACTION_NAME VARCHAR(200) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE technical_action_access_rights(
	ACTION_ID VARCHAR(100) PRIMARY KEY,
	ROLE_ID VARCHAR(100) NOT NULL
);

CREATE INDEX technical_action_index ON technical_action(ACTION_ID);

INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('1', 'Add Module', 'TL-7');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('2', 'Update Module', 'TL-8');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('3', 'Delete Module', 'TL-9');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('4', 'Add Module Access Right', 'TL-12');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('5', 'Delete Module Access Right', 'TL-13');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('6', 'Add Page', 'TL-20');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('7', 'Update Page', 'TL-21');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('8', 'Delete Page', 'TL-22');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('9', 'Add Page Access Right', 'TL-23');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('10', 'Delete Page Access Right', 'TL-24');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('11', 'Add Action', 'TL-28');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('12', 'Update Action', 'TL-29');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('13', 'Delete Action', 'TL-30');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('14', 'Add Action Access Right', 'TL-31');
INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID) VALUES ('15', 'Delete Action Access Right', 'TL-32');

INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('1', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('2', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('3', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('4', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('5', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('6', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('7', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('8', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('9', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('10', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('11', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('12', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('13', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('14', '1');
INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES ('15', '1');

CREATE PROCEDURE get_action_details(IN action_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;

	SET @query = 'SELECT ACTION_NAME, TRANSACTION_LOG_ID, RECORD_LOG FROM technical_action WHERE ACTION_ID = @action_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_action_exist(IN action_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_action WHERE ACTION_ID = @action_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_action(IN action_id VARCHAR(100), IN action_name VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @action_id = action_id;
	SET @action_name = action_name;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE technical_action SET ACTION_NAME = @action_name, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ACTION_ID = @action_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_action(IN action_id VARCHAR(100), IN action_name VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @action_id = action_id;
	SET @action_name = action_name;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@action_id, @action_name, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_action(IN action_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;

	SET @query = 'DELETE FROM technical_action WHERE ACTION_ID = @action_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_action_access_exist(IN action_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;
	SET @role_id = role_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_action_access_rights WHERE ACTION_ID = @action_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_action_access(IN action_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;
	SET @role_id = role_id;

	SET @query = 'INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES(@action_id, @role_id)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_action_access(IN action_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;

	SET @query = 'DELETE FROM technical_action_access_rights WHERE ACTION_ID = @action_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_action_access(IN action_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @action_id = action_id;
	SET @role_id = role_id;

	SET @query = 'DELETE FROM technical_action_access_rights WHERE ACTION_ID = @action_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

/* Technical Page */
CREATE TABLE technical_page(
	PAGE_ID VARCHAR(100) PRIMARY KEY,
	PAGE_NAME VARCHAR(200) NOT NULL,
	MODULE_ID VARCHAR(100) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE technical_page_access_rights(
	PAGE_ID VARCHAR(100) PRIMARY KEY,
	ROLE_ID VARCHAR(100) NOT NULL
);

CREATE INDEX technical_page_index ON technical_page(PAGE_ID);

INSERT INTO technical_page (PAGE_ID, PAGE_NAME, MODULE_ID, TRANSACTION_LOG_ID) VALUES ('1', 'Modules', 1, 'TL-10');
INSERT INTO technical_page (PAGE_ID, PAGE_NAME, MODULE_ID, TRANSACTION_LOG_ID) VALUES ('2', 'Module Form', 1, 'TL-11');
INSERT INTO technical_page (PAGE_ID, PAGE_NAME, MODULE_ID, TRANSACTION_LOG_ID) VALUES ('3', 'Pages', 1, 'TL-18');
INSERT INTO technical_page (PAGE_ID, PAGE_NAME, MODULE_ID, TRANSACTION_LOG_ID) VALUES ('4', 'Page Form', 1, 'TL-19');

INSERT INTO technical_page_access_rights (PAGE_ID, ROLE_ID) VALUES ('1', '1');
INSERT INTO technical_page_access_rights (PAGE_ID, ROLE_ID) VALUES ('2', '1');
INSERT INTO technical_page_access_rights (PAGE_ID, ROLE_ID) VALUES ('3', '1');
INSERT INTO technical_page_access_rights (PAGE_ID, ROLE_ID) VALUES ('4', '1');

CREATE PROCEDURE get_page_details(IN page_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;

	SET @query = 'SELECT PAGE_NAME, MODULE_ID, TRANSACTION_LOG_ID, RECORD_LOG FROM technical_page WHERE PAGE_ID = @page_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_page_exist(IN page_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_page WHERE PAGE_ID = @page_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_page(IN page_id VARCHAR(100), IN page_name VARCHAR(200), IN module_id VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @page_id = page_id;
	SET @page_name = page_name;
	SET @module_id= module_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE technical_page SET PAGE_NAME = @page_name, MODULE_ID = @module_id, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE PAGE_ID = @page_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_page(IN page_id VARCHAR(100), IN page_name VARCHAR(200), IN module_id VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @page_id = page_id;
	SET @page_name = page_name;
	SET @module_id= module_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO technical_page (PAGE_ID, PAGE_NAME, MODULE_ID, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@page_id, @page_name, @module_id, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_page(IN page_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;

	SET @query = 'DELETE FROM technical_page WHERE PAGE_ID = @page_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_page_access_exist(IN page_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;
	SET @role_id = role_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_page_access_rights WHERE PAGE_ID = @page_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_page_access(IN page_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;
	SET @role_id = role_id;

	SET @query = 'INSERT INTO technical_page_access_rights (PAGE_ID, ROLE_ID) VALUES(@page_id, @role_id)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_page_access(IN page_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;

	SET @query = 'DELETE FROM technical_page_access_rights WHERE PAGE_ID = @page_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_page_access(IN page_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @page_id = page_id;
	SET @role_id = role_id;

	SET @query = 'DELETE FROM technical_page_access_rights WHERE PAGE_ID = @page_id AND ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

/* Global System Code */
CREATE TABLE global_system_code(
	SYSTEM_TYPE VARCHAR(20) NOT NULL,
	SYSTEM_CODE VARCHAR(20) NOT NULL,
	SYSTEM_DESCRIPTION VARCHAR(100) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX global_system_code_index ON global_system_code(SYSTEM_TYPE, SYSTEM_CODE);

INSERT INTO global_system_code (SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID) VALUES ('SYSTYPE', 'SYSTYPE', 'System Code', 'TL-4');
INSERT INTO global_system_code (SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID) VALUES ('SYSTYPE', 'MODULECAT', 'Module Category', 'TL-5');
INSERT INTO global_system_code (SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID) VALUES ('MODULECAT', 'TECHNICAL', 'Technical', 'TL-6');

CREATE PROCEDURE get_system_code_details(IN system_type VARCHAR(100), IN system_code VARCHAR(100))
BEGIN
	SET @system_type = system_type;
	SET @system_code = system_code;

	SET @query = 'SELECT SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG FROM global_system_code WHERE SYSTEM_TYPE = @system_type AND SYSTEM_CODE = @system_code';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_system_code_options(IN system_type VARCHAR(100))
BEGIN
	SET @system_type = system_type;

	SET @query = 'SELECT SYSTEM_CODE, SYSTEM_DESCRIPTION FROM global_system_code WHERE SYSTEM_TYPE = @system_type ORDER BY SYSTEM_DESCRIPTION';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

/* Global Role */
CREATE TABLE global_role(
	ROLE_ID VARCHAR(50) PRIMARY KEY,
	ROLE VARCHAR(100) NOT NULL,
	ROLE_DESCRIPTION VARCHAR(200) NOT NULL,
	ASSIGNABLE TINYINT(1) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_role_user_account(
	ROLE_ID VARCHAR(50) NOT NULL,
	USERNAME VARCHAR(50) NOT NULL
);

CREATE INDEX global_role_index ON global_role(ROLE_ID);

INSERT INTO global_role (ROLE_ID, ROLE, ROLE_DESCRIPTION, TRANSACTION_LOG_ID) VALUES ('1', 'Administrator', 'Administrator', 'TL-2');

CREATE PROCEDURE get_role_details(IN role_id VARCHAR(100))
BEGIN
	SET @role_id = role_id;

	SET @query = 'SELECT ROLE, ROLE_DESCRIPTION, ASSIGNABLE, TRANSACTION_LOG_ID, RECORD_LOG FROM global_role WHERE ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_role_options()
BEGIN
	SET @query = 'SELECT ROLE_ID, ROLE FROM global_role ORDER BY ROLE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

/* Global User Account */
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

CREATE INDEX global_user_account_index ON global_user_account(USERNAME);

INSERT INTO global_user_account (USERNAME, PASSWORD, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_FAILED_LOGIN, TRANSACTION_LOG_ID) VALUES ('ADMIN', '68aff5412f35ed76', 'Administrator', 'Active', '2022-12-30', 0, null, 'TL-1');

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

/* Global Transaction Log */
CREATE TABLE global_transaction_log(
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	USERNAME VARCHAR(50) NOT NULL,
	LOG_TYPE VARCHAR(100) NOT NULL,
	LOG_DATE DATETIME NOT NULL,
	LOG VARCHAR(4000)
);

CREATE INDEX global_transaction_log_index ON global_transaction_log(TRANSACTION_LOG_ID);

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

/* Global Upload Setting */
CREATE TABLE global_upload_setting(
	UPLOAD_SETTING_ID INT(50) PRIMARY KEY,
	UPLOAD_SETTING VARCHAR(200) NOT NULL,
	DESCRIPTION VARCHAR(200) NOT NULL,
	MAX_FILE_SIZE DOUBLE,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_upload_file_type(
	UPLOAD_SETTING_ID INT(50),
	FILE_TYPE VARCHAR(50) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX global_upload_setting_index ON global_upload_setting(UPLOAD_SETTING_ID);

INSERT INTO global_upload_setting (UPLOAD_SETTING_ID, UPLOAD_SETTING, DESCRIPTION, MAX_FILE_SIZE, TRANSACTION_LOG_ID) VALUES ('1', 'Module Icon', 'Upload setting for module icon.', '5', 'TL-14');
INSERT INTO global_upload_file_type (UPLOAD_SETTING_ID, FILE_TYPE) VALUES ('1', 'jpeg');
INSERT INTO global_upload_file_type (UPLOAD_SETTING_ID, FILE_TYPE) VALUES ('1', 'svg');
INSERT INTO global_upload_file_type (UPLOAD_SETTING_ID, FILE_TYPE) VALUES ('1', 'png');
INSERT INTO global_upload_file_type (UPLOAD_SETTING_ID, FILE_TYPE) VALUES ('1', 'jpg');

CREATE PROCEDURE get_upload_setting_details(IN upload_setting_id INT(50))
BEGIN
	SET @upload_setting_id = upload_setting_id;

	SET @query = 'SELECT UPLOAD_SETTING, DESCRIPTION, MAX_FILE_SIZE, TRANSACTION_LOG_ID, RECORD_LOG FROM global_upload_setting WHERE UPLOAD_SETTING_ID = @upload_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_upload_file_type_details(IN upload_setting_id INT(50))
BEGIN
	SET @upload_setting_id = upload_setting_id;

	SET @query = 'SELECT FILE_TYPE, RECORD_LOG FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = @upload_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

/* Global System Parameter */
CREATE TABLE global_system_parameters(
	PARAMETER_ID INT PRIMARY KEY,
	PARAMETER VARCHAR(100) NOT NULL,
	PARAMETER_DESCRIPTION VARCHAR(100) NOT NULL,
	PARAMETER_EXTENSION VARCHAR(10),
	PARAMETER_NUMBER INT NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX global_system_parameter_index ON global_system_parameters(PARAMETER_ID);

INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID) VALUES ('1', 'System Parameter', 'Parameter for system parameters.', '', 3, 'TL-15');
INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID) VALUES ('2', 'Transaction Log', 'Parameter for transaction logs.', 'TL-', 17, 'TL-16');
INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID) VALUES ('3', 'Module', 'Parameter for modules.', '', 0, 'TL-17');
INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID) VALUES ('4', 'Page', 'Parameter for pages.', '', 4, 'TL-25');
INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID) VALUES ('5', 'Action', 'Parameter for actions.', '', 15, 'TL-33');

CREATE PROCEDURE update_system_parameter_value(IN parameter_id INT, IN parameter_number INT, IN record_log VARCHAR(100))
BEGIN
	SET @parameter_id = parameter_id;
	SET @parameter_number = parameter_number;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_system_parameters SET PARAMETER_NUMBER = @parameter_number, RECORD_LOG = @record_log WHERE PARAMETER_ID = @parameter_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_system_parameter_exist(IN parameter_id INT)
BEGIN
	SET @parameter_id = parameter_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_system_parameters WHERE PARAMETER_ID = @parameter_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_system_parameter(IN parameter_id INT, IN parameter VARCHAR(100), IN parameter_description VARCHAR(100), IN extension VARCHAR(10), IN parameter_number INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @parameter_id = parameter_id;
	SET @parameter = parameter;
	SET @parameter_description = parameter_description;
	SET @extension = extension;
	SET @parameter_number = parameter_number;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_system_parameters SET PARAMETER = @parameter, PARAMETER_DESCRIPTION = @parameter_description, PARAMETER_EXTENSION = @extension, PARAMETER_NUMBER = @parameter_number, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE PARAMETER_ID = @parameter_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_system_parameter(IN parameter_id INT, IN parameter VARCHAR(100), IN parameter_description VARCHAR(100), IN extension VARCHAR(10), IN parameter_number INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @parameter_id = parameter_id;
	SET @parameter = parameter;
	SET @parameter_description = parameter_description;
	SET @extension = extension;
	SET @parameter_number = parameter_number;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@parameter_id, @parameter, @parameter_description, @extension, @parameter_number, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_system_parameter_details(IN parameter_id INT)
BEGIN
	SET @parameter_id = parameter_id;

	SET @query = 'SELECT PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID, RECORD_LOG FROM global_system_parameters WHERE PARAMETER_ID = @parameter_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_system_parameter(IN parameter_id INT)
BEGIN
	SET @parameter_id = parameter_id;

	SET @query = 'DELETE FROM global_system_parameters WHERE PARAMETER_ID = @parameter_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

/* Global Stored Procedure */
CREATE PROCEDURE get_access_rights_count(IN role_id VARCHAR(50), IN access_right_id VARCHAR(100), IN access_type VARCHAR(10))
BEGIN
	SET @role_id = role_id;
	SET @access_right_id = access_right_id;
	SET @access_type = access_type;

	IF @access_type = 'module' THEN
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_module_access_rights WHERE MODULE_ID = @access_right_id AND ROLE_ID = @role_id';
	ELSEIF @access_type = 'page' THEN
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_page_access_rights WHERE PAGE_ID = @access_right_id AND ROLE_ID = @role_id';
	ELSE
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_action_access_rights WHERE ACTION_ID = @access_right_id AND ROLE_ID = @role_id';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //