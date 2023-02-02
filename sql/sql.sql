/* Technical Module */
CREATE TABLE technical_module(
	MODULE_ID VARCHAR(100) PRIMARY KEY,
	MODULE_NAME VARCHAR(200) NOT NULL,
	MODULE_VERSION VARCHAR(20) NOT NULL,
	MODULE_DESCRIPTION VARCHAR(500),
	MODULE_ICON VARCHAR(500),
	MODULE_CATEGORY VARCHAR(50),
	DEFAULT_PAGE VARCHAR(100),
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	RECORD_LOG VARCHAR(100) NOT NULL,
	ORDER_SEQUENCE INT
);

CREATE TABLE technical_module_access_rights(
	MODULE_ID VARCHAR(100) NOT NULL,
	ROLE_ID VARCHAR(100) NOT NULL
);

ALTER TABLE technical_module_access_rights
ADD FOREIGN KEY (MODULE_ID) REFERENCES technical_module(MODULE_ID);

CREATE INDEX technical_module_index ON technical_module(MODULE_ID);

INSERT INTO technical_module (MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_CATEGORY, TRANSACTION_LOG_ID, ORDER_SEQUENCE) VALUES ('1', 'Technical', '1.0.0', 'Administrator Module', 'TECHNICAL', 'TL-3', '99');
INSERT INTO technical_module (MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_CATEGORY, TRANSACTION_LOG_ID, ORDER_SEQUENCE) VALUES ('2', 'Technical2', '1.0.0', 'Administrator Module 2', 'TECHNICAL2', 'TL-3', '99');

INSERT INTO technical_module_access_rights (MODULE_ID, ROLE_ID) VALUES ('1', '1');

CREATE PROCEDURE get_module_details(IN module_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_ICON, MODULE_CATEGORY, DEFAULT_PAGE, TRANSACTION_LOG_ID, RECORD_LOG, ORDER_SEQUENCE FROM technical_module WHERE MODULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id;
	DEALLOCATE PREPARE stmt;
END //


CREATE PROCEDURE get_all_accessible_module_details(IN username VARCHAR(50))
BEGIN
	SET @query = 'SELECT MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_ICON, MODULE_CATEGORY, DEFAULT_PAGE, TRANSACTION_LOG_ID, RECORD_LOG, ORDER_SEQUENCE FROM technical_module WHERE MODULE_ID IN (SELECT MODULE_ID FROM technical_module_access_rights WHERE ROLE_ID IN (SELECT ROLE_ID FROM global_role_user_account WHERE USERNAME = ?)) ORDER BY ORDER_SEQUENCE';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING username;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_module_exist(IN module_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_module WHERE MODULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_module(IN module_id VARCHAR(100), IN module_name VARCHAR(200), IN module_version VARCHAR(20), IN module_description VARCHAR(500), IN module_category VARCHAR(50), IN default_page VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100), IN order_sequence INT)
BEGIN
	SET @query = 'UPDATE technical_module SET MODULE_NAME = ?, MODULE_VERSION = ?, MODULE_DESCRIPTION = ?, MODULE_CATEGORY = ?, DEFAULT_PAGE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ?, ORDER_SEQUENCE = ? WHERE MODULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_name, module_version, module_description, module_category, default_page, transaction_log_id, record_log, order_sequence, module_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_module_icon(IN module_id VARCHAR(100), IN module_icon VARCHAR(500))
BEGIN
	SET @query = 'UPDATE technical_module SET MODULE_ICON = ? WHERE MODULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_icon, module_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_module(IN module_id VARCHAR(100), IN module_name VARCHAR(200), IN module_version VARCHAR(20), IN module_description VARCHAR(500), IN module_category VARCHAR(50), IN default_page VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100), IN order_sequence INT)
BEGIN
	SET @query = 'INSERT INTO technical_module (MODULE_ID, MODULE_NAME, MODULE_VERSION, MODULE_DESCRIPTION, MODULE_CATEGORY, DEFAULT_PAGE, TRANSACTION_LOG_ID, RECORD_LOG, ORDER_SEQUENCE) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id, module_name, module_version, module_description, module_category, default_page, transaction_log_id, record_log, order_sequence;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_module(IN module_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM technical_module WHERE MODULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_module_access_exist(IN module_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_module_access_rights WHERE MODULE_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id, role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_module_access(IN module_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO technical_module_access_rights (MODULE_ID, ROLE_ID) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id, role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_module_access(IN module_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM technical_module_access_rights WHERE MODULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_module_access(IN module_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM technical_module_access_rights WHERE MODULE_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING module_id, role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_role_module_access(IN role_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM technical_module_access_rights WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generate_module_options()
BEGIN
	SET @query = 'SELECT MODULE_ID, MODULE_NAME FROM technical_module ORDER BY MODULE_NAME';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
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

ALTER TABLE technical_action_access_rights
ADD FOREIGN KEY (ACTION_ID) REFERENCES technical_action(ACTION_ID);

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
	SET @query = 'SELECT ACTION_NAME, TRANSACTION_LOG_ID, RECORD_LOG FROM technical_action WHERE ACTION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_action_exist(IN action_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_action WHERE ACTION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_action(IN action_id VARCHAR(100), IN action_name VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE technical_action SET ACTION_NAME = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE ACTION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_name, transaction_log_id, record_log, action_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_action(IN action_id VARCHAR(100), IN action_name VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO technical_action (ACTION_ID, ACTION_NAME, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id, action_name, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_action(IN action_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM technical_action WHERE ACTION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_action_access_exist(IN action_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_action_access_rights WHERE ACTION_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id, role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_action_access(IN action_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO technical_action_access_rights (ACTION_ID, ROLE_ID) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id, role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_action_access(IN action_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM technical_action_access_rights WHERE ACTION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_action_access(IN action_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM technical_action_access_rights WHERE ACTION_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING action_id, role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_role_action_access(IN role_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM technical_action_access_rights WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id;
	DEALLOCATE PREPARE stmt;
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

ALTER TABLE technical_page_access_rights
ADD FOREIGN KEY (PAGE_ID) REFERENCES technical_page(PAGE_ID);

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
	SET @query = 'SELECT PAGE_NAME, MODULE_ID, TRANSACTION_LOG_ID, RECORD_LOG FROM technical_page WHERE PAGE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_page_exist(IN page_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_page WHERE PAGE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_page(IN page_id VARCHAR(100), IN page_name VARCHAR(200), IN module_id VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE technical_page SET PAGE_NAME = ?, MODULE_ID = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE PAGE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_name, module_id, transaction_log_id, record_log, page_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_page(IN page_id VARCHAR(100), IN page_name VARCHAR(200), IN module_id VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO technical_page (PAGE_ID, PAGE_NAME, MODULE_ID, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id, page_name, module_id, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_page(IN page_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM technical_page WHERE PAGE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_page_access_exist(IN page_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_page_access_rights WHERE PAGE_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id, role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_page_access(IN page_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO technical_page_access_rights (PAGE_ID, ROLE_ID) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id, role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_page_access(IN page_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM technical_page_access_rights WHERE PAGE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_page_access(IN page_id VARCHAR(100), IN role_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM technical_page_access_rights WHERE PAGE_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING page_id, role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_role_page_access(IN role_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM technical_page_access_rights WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id;
	DEALLOCATE PREPARE stmt;
END //

/* Global System Code */
CREATE TABLE global_system_code(
	SYSTEM_CODE_ID VARCHAR(100) PRIMARY KEY,
	SYSTEM_TYPE VARCHAR(20) NOT NULL,
	SYSTEM_CODE VARCHAR(20) NOT NULL,
	SYSTEM_DESCRIPTION VARCHAR(100) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX global_system_code_index ON global_system_code(SYSTEM_TYPE, SYSTEM_CODE);

CREATE INDEX global_system_code_index ON global_system_code(SYSTEM_CODE_ID);

INSERT INTO global_system_code (SYSTEM_CODE_ID, SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID) VALUES ('1', 'SYSTYPE', 'SYSTYPE', 'System Code', 'TL-4');
INSERT INTO global_system_code (SYSTEM_CODE_ID, SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID) VALUES ('2', 'SYSTYPE', 'MODULECAT', 'Module Category', 'TL-5');
INSERT INTO global_system_code (SYSTEM_CODE_ID, SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID) VALUES ('3', 'MODULECAT', 'TECHNICAL', 'Technical', 'TL-6');

CREATE PROCEDURE check_system_code_exist(IN system_code_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_system_code WHERE SYSTEM_CODE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING system_code_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_system_code(IN system_code_id VARCHAR(100), IN system_type VARCHAR(20), IN system_code VARCHAR(20), IN system_description VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_system_code SET SYSTEM_TYPE = ?, SYSTEM_CODE = ?, SYSTEM_DESCRIPTION = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE SYSTEM_CODE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING system_type, system_code, system_description, transaction_log_id, record_log, system_code_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_system_code(IN system_code_id VARCHAR(100), IN system_type VARCHAR(20), IN system_code VARCHAR(20), IN system_description VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_system_code (SYSTEM_CODE_ID, SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING system_code_id, system_type, system_code, system_description, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_system_code(IN system_code_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM global_system_code WHERE SYSTEM_CODE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING system_code_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_system_code_details(IN system_code_id VARCHAR(100), IN system_type VARCHAR(100), IN system_code VARCHAR(100))
BEGIN
	SET @query = 'SELECT SYSTEM_CODE_ID, SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG FROM global_system_code WHERE';

	IF system_code_id IS NOT NULL OR system_code_id <> '' THEN
		SET @query = CONCAT(@query, ' SYSTEM_CODE_ID = ?');

		PREPARE stmt FROM @query; 
		EXECUTE stmt USING system_code_id;
	ELSE
		SET @query = CONCAT(@query, ' SYSTEM_TYPE = ? AND SYSTEM_CODE = ?');
		
		PREPARE stmt FROM @query; 
		EXECUTE stmt USING system_type, system_code;
	END IF;

	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generate_system_code_options(IN system_type VARCHAR(100))
BEGIN
	SET @query = 'SELECT SYSTEM_CODE, SYSTEM_DESCRIPTION FROM global_system_code WHERE SYSTEM_TYPE = ? ORDER BY SYSTEM_DESCRIPTION';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING system_type;
	DEALLOCATE PREPARE stmt;
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

ALTER TABLE global_role_user_account
ADD FOREIGN KEY (ROLE_ID) REFERENCES global_role(ROLE_ID);

CREATE INDEX global_role_index ON global_role(ROLE_ID);

INSERT INTO global_role (ROLE_ID, ROLE, ROLE_DESCRIPTION, TRANSACTION_LOG_ID) VALUES ('1', 'Administrator', 'Administrator', 'TL-2');

CREATE PROCEDURE check_system_code_exist(IN role_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT ROLE, ROLE_DESCRIPTION, ASSIGNABLE, TRANSACTION_LOG_ID, RECORD_LOG FROM global_role WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generate_role_options()
BEGIN
	SET @query = 'SELECT ROLE_ID, ROLE FROM global_role ORDER BY ROLE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_role_exist(IN role_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_role WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_role_user_account_exist(IN role_id VARCHAR(100), IN username VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_role_user_account WHERE ROLE_ID = ? AND USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id, username;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_role(IN role_id VARCHAR(100), IN role VARCHAR(100), IN role_description VARCHAR(200), IN assignable TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_role SET ROLE = ?, ROLE_DESCRIPTION = ?, ASSIGNABLE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role, role_description, assignable, transaction_log_id, record_log, role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_role(IN role_id VARCHAR(100), IN role VARCHAR(100), IN role_description VARCHAR(200), IN assignable TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_role (ROLE_ID, ROLE, ROLE_DESCRIPTION, ASSIGNABLE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id, role, role_description, assignable, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_role(IN role_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM global_role WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_role_user_account(IN role_id VARCHAR(100), IN username VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM global_role_user_account WHERE ROLE_ID = ? AND USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id, username;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_role_user_account(IN role_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM global_role_user_account WHERE ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_role_user_account(IN role_id VARCHAR(100), IN username VARCHAR(50))
BEGIN
	SET @query = 'INSERT INTO global_role_user_account (ROLE_ID, USERNAME) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING role_id, username;
	DEALLOCATE PREPARE stmt;
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

DELIMITER //
DROP PROCEDURE check_user_account_exist //

CREATE PROCEDURE check_user_account_exist(IN username VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_user_account WHERE BINARY USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING username;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_user_account_details(IN username VARCHAR(50))
BEGIN
	SET @query = 'SELECT PASSWORD, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_FAILED_LOGIN, LAST_CONNECTION_DATE, TRANSACTION_LOG_ID, RECORD_LOG FROM global_user_account WHERE USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING username;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_login_attempt(IN username VARCHAR(50), login_attemp INT(1), last_failed_attempt_date DATETIME)
BEGIN
	SET @query = 'UPDATE global_user_account SET';

    IF login_attemp > 0 THEN
		SET @query = CONCAT(@query, ' FAILED_LOGIN = ?, LAST_FAILED_LOGIN = ? WHERE USERNAME = ?');

		PREPARE stmt FROM @query;
		EXECUTE stmt USING login_attemp, last_failed_attempt_date, username;
	ELSE
		SET @query = CONCAT(@query, ' FAILED_LOGIN = ? WHERE USERNAME = ?');

		PREPARE stmt FROM @query;
		EXECUTE stmt USING login_attemp, username;
    END IF;

	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_user_last_connection(IN username VARCHAR(50), last_connection_date DATETIME)
BEGIN
	SET @query = 'UPDATE global_user_account SET LAST_CONNECTION_DATE = ? WHERE USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING last_connection_date, username;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_user_account_password(IN username VARCHAR(50), password VARCHAR(200), password_expiry_date DATE)
BEGIN
	SET @query = 'UPDATE global_user_account SET PASSWORD = ?, PASSWORD_EXPIRY_DATE = ? WHERE USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING password, password_expiry_date, username;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_user_account(IN username VARCHAR(50), IN password VARCHAR(200), IN file_as VARCHAR (300), IN password_expiry_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_user_account SET';

	IF password IS NOT NULL OR password <> '' THEN
		SET @query = CONCAT(@query, 'FILE_AS = ?, PASSWORD = ?, PASSWORD_EXPIRY_DATE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE USERNAME = ?');

		PREPARE stmt FROM @query;
		EXECUTE stmt USING file_as, password, password_expiry_date, transaction_log_id, record_log, username;
	ELSE
		SET @query = CONCAT(@query, 'FILE_AS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE USERNAME = ?');

		PREPARE stmt FROM @query;
		EXECUTE stmt USING file_as, transaction_log_id, record_log, username;
    END IF;

	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_user_account(IN username VARCHAR(50), IN password VARCHAR(200), IN file_as VARCHAR (300), IN password_expiry_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_user_account (USERNAME, PASSWORD, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_FAILED_LOGIN, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, "Inactive", ?, 0, DEFAULT, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING username, password, file_as, password_expiry_date, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_user_account(IN username VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM global_user_account WHERE USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING username;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_user_account_role(IN username VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM global_role_user_account WHERE USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING username;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_user_account_lock_status(IN username VARCHAR(50), IN transaction_type VARCHAR(10), IN last_failed_login DATE, IN record_log VARCHAR(100))
BEGIN
	IF transaction_type = 'unlock' THEN
		SET @query = 'UPDATE global_user_account SET FAILED_LOGIN = 0, LAST_FAILED_LOGIN = ?, RECORD_LOG = ? WHERE USERNAME = ?';
	ELSE
		SET @query = 'UPDATE global_user_account SET FAILED_LOGIN = 5, LAST_FAILED_LOGIN = ?, RECORD_LOG = ? WHERE USERNAME = ?';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt USING last_failed_login, record_log, username;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_user_account_status(IN username VARCHAR(50), IN user_status VARCHAR(10), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_user_account SET USER_STATUS = ?, RECORD_LOG = ? WHERE USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING user_status, record_log, username;
	DEALLOCATE PREPARE stmt;
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
	SET @query = 'INSERT INTO global_transaction_log (TRANSACTION_LOG_ID, USERNAME, LOG_TYPE, LOG_DATE, LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING transaction_log_id, username, log_type, log_date, log;
	DEALLOCATE PREPARE stmt;
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
	FILE_TYPE VARCHAR(50) NOT NULL
);

ALTER TABLE global_upload_file_type
ADD FOREIGN KEY (UPLOAD_SETTING_ID) REFERENCES global_upload_setting(UPLOAD_SETTING_ID);

CREATE INDEX global_upload_setting_index ON global_upload_setting(UPLOAD_SETTING_ID);

INSERT INTO global_upload_setting (UPLOAD_SETTING_ID, UPLOAD_SETTING, DESCRIPTION, MAX_FILE_SIZE, TRANSACTION_LOG_ID) VALUES ('1', 'Module Icon', 'Upload setting for module icon.', '5', 'TL-14');
INSERT INTO global_upload_file_type (UPLOAD_SETTING_ID, FILE_TYPE) VALUES ('1', 'jpeg');
INSERT INTO global_upload_file_type (UPLOAD_SETTING_ID, FILE_TYPE) VALUES ('1', 'svg');
INSERT INTO global_upload_file_type (UPLOAD_SETTING_ID, FILE_TYPE) VALUES ('1', 'png');
INSERT INTO global_upload_file_type (UPLOAD_SETTING_ID, FILE_TYPE) VALUES ('1', 'jpg');

CREATE PROCEDURE check_upload_setting_exist(IN upload_setting_id INT(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_upload_setting WHERE UPLOAD_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_upload_setting_file_type_exist(IN upload_setting_id INT(50), IN file_type VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = ? AND FILE_TYPE = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id, file_type;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_upload_setting(IN upload_setting_id INT(50), IN upload_setting VARCHAR(100), IN description VARCHAR(100), IN max_file_size VARCHAR(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_upload_setting SET UPLOAD_SETTING = ?, DESCRIPTION = ?, MAX_FILE_SIZE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE UPLOAD_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting, description, max_file_size, transaction_log_id, record_log, upload_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_upload_setting(IN upload_setting_id INT(50), IN upload_setting VARCHAR(100), IN description VARCHAR(100), IN max_file_size VARCHAR(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_upload_setting (UPLOAD_SETTING_ID, UPLOAD_SETTING, DESCRIPTION, MAX_FILE_SIZE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id, upload_setting, description, max_file_size, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_upload_setting_file_type(IN upload_setting_id INT(50), IN file_type VARCHAR(50))
BEGIN
	SET @query = 'INSERT INTO global_upload_file_type (UPLOAD_SETTING_ID, FILE_TYPE) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id, file_type;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_upload_setting_details(IN upload_setting_id INT(50))
BEGIN
	SET @query = 'SELECT UPLOAD_SETTING, DESCRIPTION, MAX_FILE_SIZE, TRANSACTION_LOG_ID, RECORD_LOG FROM global_upload_setting WHERE UPLOAD_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_upload_file_type_details(IN upload_setting_id INT(50))
BEGIN
	SET @query = 'SELECT FILE_TYPE FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_upload_setting(IN upload_setting_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_upload_setting WHERE UPLOAD_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_upload_setting_file_type(IN upload_setting_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_upload_setting_file_type(IN upload_setting_id INT(50), IN file_type VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = ? AND FILE_TYPE = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING upload_setting_id, file_type;
	DEALLOCATE PREPARE stmt;
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

DELIMITER //
DROP PROCEDURE update_system_parameter_value //

CREATE PROCEDURE update_system_parameter_value(IN parameter_id INT, IN parameter_number INT, IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_system_parameters SET PARAMETER_NUMBER = ?, RECORD_LOG = ? WHERE PARAMETER_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING parameter_number, record_log, parameter_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_system_parameter_exist(IN parameter_id INT)
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_system_parameters WHERE PARAMETER_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING parameter_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_system_parameter(IN parameter_id INT, IN parameter VARCHAR(100), IN parameter_description VARCHAR(100), IN extension VARCHAR(10), IN parameter_number INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_system_parameters SET PARAMETER = ?, PARAMETER_DESCRIPTION = ?, PARAMETER_EXTENSION = ?, PARAMETER_NUMBER = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE PARAMETER_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING parameter, parameter_description, extension, parameter_number, transaction_log_id, record_log, parameter_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_system_parameter(IN parameter_id INT, IN parameter VARCHAR(100), IN parameter_description VARCHAR(100), IN extension VARCHAR(10), IN parameter_number INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING parameter_id, parameter, parameter_description, extension, parameter_number, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_system_parameter_details(IN parameter_id INT)
BEGIN
	SET @query = 'SELECT PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID, RECORD_LOG FROM global_system_parameters WHERE PARAMETER_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING parameter_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_system_parameter(IN parameter_id INT)
BEGIN
	SET @query = 'DELETE FROM global_system_parameters WHERE PARAMETER_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING parameter_id;
	DEALLOCATE PREPARE stmt;
END //

/* Global Company */
CREATE TABLE global_company(
	COMPANY_ID VARCHAR(50) PRIMARY KEY,
	COMPANY_NAME VARCHAR(100) NOT NULL,
	COMPANY_LOGO VARCHAR(500),
	COMPANY_ADDRESS VARCHAR(500),
	EMAIL VARCHAR(50),
	TELEPHONE VARCHAR(20),
	MOBILE VARCHAR(20),
	WEBSITE VARCHAR(100),
	TAX_ID VARCHAR(100),
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX global_company_index ON global_company(COMPANY_ID);

CREATE PROCEDURE check_company_exist(IN company_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_company WHERE COMPANY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING company_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_company(IN company_id VARCHAR(50), IN company_name VARCHAR(100), IN company_address VARCHAR(500), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN website VARCHAR(100), IN tax_id VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_company SET COMPANY_NAME = ?, COMPANY_ADDRESS = ?, EMAIL = ?, TELEPHONE = ?, MOBILE = ?, WEBSITE = ?website, TAX_ID = ?tax_id, TRANSACTION_LOG_ID = ?transaction_log_id, RECORD_LOG = ?record_log WHERE COMPANY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING company_name, company_address, email, telephone, mobile, company_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_company_logo(IN company_id VARCHAR(50), IN company_logo VARCHAR(500))
BEGIN
	SET @query = 'UPDATE global_company SET COMPANY_LOGO = ? WHERE COMPANY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING company_logo, company_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_company(IN company_id VARCHAR(50), IN company_name VARCHAR(100), IN company_address VARCHAR(500), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN website VARCHAR(100), IN tax_id VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_company (COMPANY_ID, COMPANY_NAME, COMPANY_ADDRESS, EMAIL, TELEPHONE, MOBILE, WEBSITE, TAX_ID, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING company_id, company_name, company_address, email, telephone, mobile, website, tax_id, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_company_details(IN company_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COMPANY_NAME, COMPANY_LOGO, COMPANY_ADDRESS, EMAIL, TELEPHONE, MOBILE, WEBSITE, TAX_ID, TRANSACTION_LOG_ID, RECORD_LOG FROM global_company WHERE COMPANY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING company_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_company(IN company_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM global_company WHERE COMPANY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING company_id;
	DEALLOCATE PREPARE stmt;
END //

/* Global Interface Setting */
CREATE TABLE global_interface_setting(
	INTERFACE_SETTING_ID INT(50) PRIMARY KEY,
	INTERFACE_SETTING_NAME VARCHAR(100) NOT NULL,
	DESCRIPTION VARCHAR(200) NOT NULL,
	STATUS TINYINT(1) NOT NULL,
	LOGIN_BACKGROUND VARCHAR(500),
	LOGIN_LOGO VARCHAR(500),
	MENU_LOGO VARCHAR(500),
	FAVICON VARCHAR(500),
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX global_interface_setting_index ON global_interface_setting(INTERFACE_SETTING_ID);

CREATE PROCEDURE check_interface_setting_exist(IN interface_setting_id INT(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_interface_setting WHERE INTERFACE_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING interface_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_interface_setting(IN interface_setting_id INT(50), IN interface_setting_name VARCHAR(100), IN description VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_interface_setting SET INTERFACE_SETTING_NAME = ?, DESCRIPTION = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING interface_setting_name, description, transaction_log_id, record_log, interface_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_interface_settings_images(IN interface_setting_id INT(50), IN file_path VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100), IN request_type VARCHAR(20))
BEGIN
	IF request_type = 'login background' THEN
		SET @query = 'UPDATE global_interface_setting SET LOGIN_BACKGROUND = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID = ?';
	ELSEIF request_type = 'login logo' THEN
		SET @query = 'UPDATE global_interface_setting SET LOGIN_LOGO = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID = ?';
	ELSEIF request_type = 'menu logo' THEN
		SET @query = 'UPDATE global_interface_setting SET MENU_LOGO = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID = ?';
	ELSE
		SET @query = 'UPDATE global_interface_setting SET FAVICON = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID = ?';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt USING file_path, transaction_log_id, record_log, interface_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_interface_setting_status(IN interface_setting_id INT(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_interface_setting SET STATUS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING status, transaction_log_id, record_log, interface_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_other_interface_setting_status(IN interface_setting_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_interface_setting SET STATUS = 2, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE INTERFACE_SETTING_ID != ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING transaction_log_id, record_log, interface_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_interface_setting(IN interface_setting_id INT(50), IN interface_setting_name VARCHAR(100), IN description VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_interface_setting (INTERFACE_SETTING_ID, INTERFACE_SETTING_NAME, DESCRIPTION, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, "2", ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING interface_setting_id, interface_setting_name, description, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_interface_setting_details(IN interface_setting_id INT(50))
BEGIN
	SET @query = 'SELECT INTERFACE_SETTING_NAME, DESCRIPTION, STATUS, LOGIN_BACKGROUND, LOGIN_LOGO, MENU_LOGO, FAVICON, TRANSACTION_LOG_ID, RECORD_LOG FROM global_interface_setting WHERE INTERFACE_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING interface_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_activated_interface_setting_details()
BEGIN
	SET @query = 'SELECT INTERFACE_SETTING_ID, INTERFACE_SETTING_NAME, DESCRIPTION, STATUS, LOGIN_BACKGROUND, LOGIN_LOGO, MENU_LOGO, FAVICON, TRANSACTION_LOG_ID, RECORD_LOG FROM global_interface_setting WHERE STATUS = 1';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_interface_setting(IN interface_setting_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_interface_setting WHERE INTERFACE_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING interface_setting_id;
	DEALLOCATE PREPARE stmt;
END //

/* Global Email Setting */
CREATE TABLE global_email_setting(
	EMAIL_SETTING_ID INT(50) PRIMARY KEY,
	EMAIL_SETTING_NAME VARCHAR(100) NOT NULL,
	DESCRIPTION VARCHAR(200) NOT NULL,
	STATUS TINYINT(1) NOT NULL,
	MAIL_HOST VARCHAR(100) NOT NULL,
	PORT INT NOT NULL,
	SMTP_AUTH INT(1) NOT NULL,
	SMTP_AUTO_TLS INT(1) NOT NULL,
	MAIL_USERNAME VARCHAR(200) NOT NULL,
	MAIL_PASSWORD VARCHAR(200) NOT NULL,
	MAIL_ENCRYPTION VARCHAR(20),
	MAIL_FROM_NAME VARCHAR(200),
	MAIL_FROM_EMAIL VARCHAR(200),
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX global_email_setting_index ON global_email_setting(EMAIL_SETTING_ID);

CREATE PROCEDURE check_email_setting_exist(IN email_setting_id INT(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_email_setting WHERE EMAIL_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING email_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_email_setting(IN email_setting_id INT(50), IN email_setting_name VARCHAR(100), IN description VARCHAR(200), IN mail_host VARCHAR(100), IN port INT, IN smtp_auth INT(1), IN smtp_auto_tls INT(1), IN mail_username VARCHAR(200), IN mail_password VARCHAR(200), IN mail_encryption VARCHAR(20), IN mail_from_name VARCHAR(200), IN mail_from_email VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_email_setting SET EMAIL_SETTING_NAME = ?, DESCRIPTION = ?, MAIL_HOST = ?, PORT = ?, SMTP_AUTH = ?, SMTP_AUTO_TLS = ?, MAIL_USERNAME = ?, MAIL_PASSWORD = ?, MAIL_ENCRYPTION = ?, MAIL_FROM_NAME = ?, MAIL_FROM_EMAIL = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMAIL_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING email_setting_name, description, mail_host, port, smtp_auth, smtp_auto_tls, mail_username, mail_password, mail_encryption, mail_from_name, mail_from_email, transaction_log_id, record_log, email_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_email_setting_status(IN email_setting_id INT(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_email_setting SET STATUS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMAIL_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING status, transaction_log_id, record_log, email_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_other_email_setting_status(IN email_setting_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_email_setting SET STATUS = 2, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMAIL_SETTING_ID != ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING transaction_log_id, record_log, email_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_email_setting(IN email_setting_id INT(50), IN email_setting_name VARCHAR(100), IN description VARCHAR(200), IN mail_host VARCHAR(100), IN port INT, IN smtp_auth INT(1), IN smtp_auto_tls INT(1), IN mail_username VARCHAR(200), IN mail_password VARCHAR(200), IN mail_encryption VARCHAR(20), IN mail_from_name VARCHAR(200), IN mail_from_email VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_email_setting (EMAIL_SETTING_ID, EMAIL_SETTING_NAME, DESCRIPTION, STATUS, MAIL_HOST, PORT, SMTP_AUTH, SMTP_AUTO_TLS, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_NAME, MAIL_FROM_EMAIL, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, "2", ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING email_setting_id, email_setting_name, description, mail_host, port, smtp_auth, smtp_auto_tls, mail_username, mail_password, mail_encryption, mail_from_name, mail_from_email, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_email_setting_details(IN email_setting_id INT(50))
BEGIN
	SET @query = 'SELECT EMAIL_SETTING_NAME, DESCRIPTION, STATUS, MAIL_HOST, PORT, SMTP_AUTH, SMTP_AUTO_TLS, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_NAME, MAIL_FROM_EMAIL, TRANSACTION_LOG_ID, RECORD_LOG FROM global_email_setting WHERE EMAIL_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING email_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_activated_email_setting_details()
BEGIN
	SET @query = 'SELECT EMAIL_SETTING_ID, EMAIL_SETTING_NAME, DESCRIPTION, STATUS, MAIL_HOST, PORT, SMTP_AUTH, SMTP_AUTO_TLS, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_NAME, MAIL_FROM_EMAIL, TRANSACTION_LOG_ID, RECORD_LOG FROM global_email_setting WHERE STATUS = 1';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_email_setting(IN email_setting_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_email_setting WHERE EMAIL_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING email_setting_id;
	DEALLOCATE PREPARE stmt;
END //

/* Global Notification Setting */
CREATE TABLE global_notification_setting(
	NOTIFICATION_SETTING_ID INT(50) PRIMARY KEY,
	NOTIFICATION_SETTING VARCHAR(100) NOT NULL,
	DESCRIPTION VARCHAR(200) NOT NULL,
	NOTIFICATION_TITLE VARCHAR(500),
	NOTIFICATION_MESSAGE VARCHAR(500),
	SYSTEM_LINK VARCHAR(200),
	EMAIL_LINK VARCHAR(200),
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_notification_user_account_recipient(
	NOTIFICATION_SETTING_ID INT(50),
	USERNAME VARCHAR(50) NOT NULL
);

CREATE TABLE global_notification_role_recipient(
	NOTIFICATION_SETTING_ID INT(50),
	ROLE_ID VARCHAR(50) NOT NULL
);

CREATE TABLE global_notification_channel(
	NOTIFICATION_SETTING_ID INT(50),
	CHANNEL VARCHAR(20) NOT NULL
);

ALTER TABLE global_notification_user_account_recipient
ADD FOREIGN KEY (NOTIFICATION_SETTING_ID) REFERENCES global_notification_setting(NOTIFICATION_SETTING_ID);

ALTER TABLE global_notification_role_recipient
ADD FOREIGN KEY (NOTIFICATION_SETTING_ID) REFERENCES global_notification_setting(NOTIFICATION_SETTING_ID);

ALTER TABLE global_notification_channel
ADD FOREIGN KEY (NOTIFICATION_SETTING_ID) REFERENCES global_notification_setting(NOTIFICATION_SETTING_ID);

CREATE INDEX global_notification_setting_index ON global_notification_setting(NOTIFICATION_SETTING_ID);

CREATE PROCEDURE check_notification_setting_exist(IN notification_setting_id INT(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_setting WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_notification_user_account_recipient_exist(IN notification_setting_id INT(50), IN username VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_user_account_recipient WHERE NOTIFICATION_SETTING_ID = ? AND USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, username;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_notification_role_recipient_exist(IN notification_setting_id INT(50), IN role_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_role_recipient WHERE NOTIFICATION_SETTING_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_notification_channel_exist(IN notification_setting_id INT(50), IN channel VARCHAR(20))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = ? AND CHANNEL = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, channel;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_notification_setting(IN notification_setting_id INT(50), IN notification_setting VARCHAR(100), IN description VARCHAR(200), IN notification_title VARCHAR(500), IN notification_message VARCHAR(500), IN system_link VARCHAR(200), IN email_link VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_notification_setting SET NOTIFICATION_SETTING = ?, DESCRIPTION = ?, NOTIFICATION_TITLE = ?, NOTIFICATION_MESSAGE = ?, SYSTEM_LINK = ?, EMAIL_LINK = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting, description, notification_title, notification_message, system_link, email_link, transaction_log_id, record_log, notification_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_notification_setting(IN notification_setting_id INT(50), IN notification_setting VARCHAR(100), IN description VARCHAR(200), IN notification_title VARCHAR(500), IN notification_message VARCHAR(500), IN system_link VARCHAR(200), IN email_link VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_notification_setting (NOTIFICATION_SETTING_ID, NOTIFICATION_SETTING, DESCRIPTION, NOTIFICATION_TITLE, NOTIFICATION_MESSAGE, SYSTEM_LINK, EMAIL_LINK, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, notification_setting, description, notification_title, notification_message, system_link, email_link, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_notification_user_account_recipient(IN notification_setting_id INT(50), IN username VARCHAR(50))
BEGIN
	SET @query = 'INSERT INTO global_notification_user_account_recipient (NOTIFICATION_SETTING_ID, USERNAME) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, username;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_notification_role_recipient(IN notification_setting_id INT(50), IN role_id VARCHAR(50))
BEGIN
	SET @query = 'INSERT INTO global_notification_role_recipient (NOTIFICATION_SETTING_ID, ROLE_ID) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_notification_channel(IN notification_setting_id INT(50), IN channel VARCHAR(20))
BEGIN
	SET @query = 'INSERT INTO global_notification_channel (NOTIFICATION_SETTING_ID, CHANNEL) VALUES(?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, channel;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_notification_setting_details(IN notification_setting_id INT(50))
BEGIN
	SET @query = 'SELECT NOTIFICATION_SETTING, DESCRIPTION, NOTIFICATION_TITLE, NOTIFICATION_MESSAGE, SYSTEM_LINK, EMAIL_LINK, TRANSACTION_LOG_ID, RECORD_LOG FROM global_notification_setting WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_notification_setting(IN notification_setting_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_notification_setting WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_notification_user_account_recipient(IN notification_setting_id INT(50), IN username VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM global_notification_user_account_recipient WHERE NOTIFICATION_SETTING_ID = ? AND USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, username;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_notification_role_recipient(IN notification_setting_id INT(50), IN role_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM global_notification_role_recipient WHERE NOTIFICATION_SETTING_ID = ? AND ROLE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, role_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_notification_channel(IN notification_setting_id INT(50), IN channel VARCHAR(20))
BEGIN
	SET @query = 'DELETE FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = ? AND CHANNEL = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id, channel;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_notification_role_recipient(IN notification_setting_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_notification_role_recipient WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_notification_user_account_recipient(IN notification_setting_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_notification_user_account_recipient WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_notification_channel(IN notification_setting_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING notification_setting_id;
	DEALLOCATE PREPARE stmt;
END //

/* Global Country */
CREATE TABLE global_country(
	COUNTRY_ID INT(50) PRIMARY KEY,
	COUNTRY_NAME VARCHAR(200) NOT NULL,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX global_country_index ON global_country(COUNTRY_ID);

DELIMITER //
DROP PROCEDURE check_country_exist //

CREATE PROCEDURE check_country_exist(IN country_id INT(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_country WHERE COUNTRY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING country_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_country(IN country_id INT(50), IN country_name VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_country SET COUNTRY_NAME = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE COUNTRY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING country_name, transaction_log_id, record_log, country_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_country(IN country_id INT(50), IN country_name VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_country (COUNTRY_ID, COUNTRY_NAME, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING country_id, country_name, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_country_details(IN country_id INT(50))
BEGIN
	SET @query = 'SELECT COUNTRY_NAME, TRANSACTION_LOG_ID, RECORD_LOG FROM global_country WHERE COUNTRY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING country_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_country(IN country_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_country WHERE COUNTRY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING country_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_state(IN country_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_state WHERE COUNTRY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING country_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generate_country_options()
BEGIN
	SET @query = 'SELECT COUNTRY_ID, COUNTRY_NAME FROM global_country ORDER BY COUNTRY_NAME';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //

/* Global State */
CREATE TABLE global_state(
	STATE_ID INT(50) PRIMARY KEY,
	STATE_NAME VARCHAR(200) NOT NULL,
	COUNTRY_ID INT(50) NOT NULL,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

ALTER TABLE global_state
ADD FOREIGN KEY (COUNTRY_ID) REFERENCES global_country(COUNTRY_ID);

CREATE INDEX global_state_index ON global_state(STATE_ID);

DELIMITER //
DROP PROCEDURE check_state_exist //

CREATE PROCEDURE check_state_exist(IN state_id INT(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_state WHERE STATE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING state_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_state(IN state_id INT(50), IN state_name VARCHAR(200), IN country_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_state SET STATE_NAME = ?, COUNTRY_ID = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE STATE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING state_name, country_id, transaction_log_id, record_log, state_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_state(IN state_id INT(50), IN state_name VARCHAR(200), IN country_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_state (STATE_ID, STATE_NAME, COUNTRY_ID, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING state_id, state_name, country_id, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_state_details(IN state_id INT(50))
BEGIN
	SET @query = 'SELECT STATE_NAME, COUNTRY_ID, TRANSACTION_LOG_ID, RECORD_LOG FROM global_state WHERE STATE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING state_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_state(IN state_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_state WHERE STATE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING state_id;
	DEALLOCATE PREPARE stmt;
END //

/* Global Zoom API */
CREATE TABLE global_zoom_api(
	ZOOM_API_ID INT(50) PRIMARY KEY,
	ZOOM_API_NAME VARCHAR(100) NOT NULL,
	DESCRIPTION VARCHAR(200) NOT NULL,
	API_KEY VARCHAR(1000) NOT NULL,
	API_SECRET VARCHAR(1000) NOT NULL,
	STATUS TINYINT(1) NOT NULL,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX global_zoom_api_index ON global_zoom_api(ZOOM_API_ID);

DELIMITER //
DROP PROCEDURE check_zoom_api_exist //

CREATE PROCEDURE check_zoom_api_exist(IN zoom_api_id INT(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_zoom_api WHERE ZOOM_API_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING zoom_api_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_zoom_api(IN zoom_api_id INT(50), IN zoom_api_name VARCHAR(100), IN description VARCHAR(200), IN api_key VARCHAR(1000), IN api_secret VARCHAR(1000), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_zoom_api SET ZOOM_API_NAME = ?, DESCRIPTION = ?, API_KEY = ?, API_SECRET = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE ZOOM_API_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING zoom_api_name, description, api_key, api_secret, transaction_log_id, record_log, zoom_api_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_zoom_api_status(IN zoom_api_id INT(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_zoom_api SET STATUS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE ZOOM_API_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING status, transaction_log_id, record_log, zoom_api_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_other_zoom_api_status(IN zoom_api_id INT(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE global_zoom_api SET STATUS = 2, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE ZOOM_API_ID != ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING transaction_log_id, record_log, zoom_api_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_zoom_api(IN zoom_api_id INT(50), IN zoom_api_name VARCHAR(100), IN description VARCHAR(200), IN api_key VARCHAR(1000), IN api_secret VARCHAR(1000), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO global_zoom_api (ZOOM_API_ID, ZOOM_API_NAME, DESCRIPTION, API_KEY, API_SECRET, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, "2", ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING zoom_api_id, zoom_api_name, description, api_key, api_secret, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_zoom_api_details(IN zoom_api_id INT(50))
BEGIN
	SET @query = 'SELECT ZOOM_API_NAME, DESCRIPTION, API_KEY, API_SECRET, STATUS, TRANSACTION_LOG_ID, RECORD_LOG FROM global_zoom_api WHERE ZOOM_API_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING zoom_api_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_zoom_api(IN zoom_api_id INT(50))
BEGIN
	SET @query = 'DELETE FROM global_zoom_api WHERE ZOOM_API_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING zoom_api_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_activated_zoom_api_details()
BEGIN
	SET @query = 'SELECT ZOOM_API_ID, ZOOM_API_NAME, DESCRIPTION, API_KEY, API_SECRET, STATUS, TRANSACTION_LOG_ID, RECORD_LOG FROM global_zoom_api WHERE STATUS = 1';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //

/* Employee Details */
CREATE TABLE employee_details(
	EMPLOYEE_ID VARCHAR(100) PRIMARY KEY,
	USERNAME VARCHAR(50),
	BADGE_ID VARCHAR(100),
	EMPLOYEE_IMAGE VARCHAR(500),
	FILE_AS VARCHAR(350) NOT NULL,
	FIRST_NAME VARCHAR(100) NOT NULL,
	MIDDLE_NAME VARCHAR(100) NOT NULL,
	LAST_NAME VARCHAR(100) NOT NULL,
	SUFFIX VARCHAR(5),
	COMPANY VARCHAR(50),
	JOB_POSITION VARCHAR(50),
	DEPARTMENT VARCHAR(50),
	WORK_LOCATION VARCHAR(50),
	WORKING_HOURS VARCHAR(50),
	MANAGER VARCHAR(100),
	COACH VARCHAR(100),
	EMPLOYEE_TYPE VARCHAR(100),
	EMPLOYEE_STATUS VARCHAR(100),
	PERMANENCY_DATE DATE,
	ONBOARD_DATE DATE,
	OFFBOARD_DATE DATE,
	DEPARTURE_REASON VARCHAR(50),
	DETAILED_REASON VARCHAR(500),
	WORK_EMAIL VARCHAR(50),
	WORK_TELEPHONE VARCHAR(20),
	WORK_MOBILE VARCHAR(20),
	SSS VARCHAR(20),
	TIN VARCHAR(20),
	PAGIBIG VARCHAR(20),
	PHILHEALTH VARCHAR(20),
	BANK_ACCOUNT_NUMBER VARCHAR(100),
	HOME_WORK_DISTANCE DOUBLE,
	PERSONAL_EMAIL VARCHAR(50),
	PERSONAL_TELEPHONE VARCHAR(20),
	PERSONAL_MOBILE VARCHAR(20),
	STREET_1 VARCHAR(200),
	STREET_2 VARCHAR(200),
	COUNTRY_ID INT,
	STATE_ID INT,
	CITY VARCHAR(100),
	ZIP_CODE VARCHAR(10),
	MARITAL_STATUS VARCHAR(20),
	SPOUSE_NAME VARCHAR(500),
	SPOUSE_BIRTHDAY DATE,
	EMERGENCY_CONTACT VARCHAR(500),
	EMERGENCY_PHONE VARCHAR(20),
	NATIONALITY INT,
	IDENTIFICATION_NUMBER VARCHAR(100),
	PASSPORT_NUMBER VARCHAR(100),
	GENDER VARCHAR(20),
	BIRTHDAY DATE,
	CERTIFICATE_LEVEL VARCHAR(20),
	FIELD_OF_STUDY VARCHAR(200),
	SCHOOL VARCHAR(200),
	PLACE_OF_BIRTH VARCHAR(500),
	NUMBER_OF_CHILDREN INT,
	VISA_NUMBER VARCHAR(100),
	WORK_PERMIT_NUMBER VARCHAR(100),
	VISA_EXPIRY_DATE DATE,
	WORK_PERMIT_EXPIRY_DATE DATE,
	WORK_PERMIT VARCHAR(500),
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX employee_details_index ON employee_details(EMPLOYEE_ID);

CREATE PROCEDURE check_employee_exist(IN employee_id INT)
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_details WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_employee(IN employee_id VARCHAR(100), IN badge_id VARCHAR(100), IN file_as VARCHAR(350), IN first_name VARCHAR(100), IN middle_name VARCHAR(100), IN last_name VARCHAR(100), IN suffix VARCHAR(5), IN company VARCHAR(50), IN job_position VARCHAR(50), IN department VARCHAR(50), IN work_location VARCHAR(50), IN working_hours VARCHAR(50), IN manager VARCHAR(100), IN coach VARCHAR(100), IN employee_type VARCHAR(100), IN permanency_date DATE, IN onboard_date DATE, IN work_email VARCHAR(50), IN work_telephone VARCHAR(50), IN work_mobile VARCHAR(50), IN sss VARCHAR(20), IN tin VARCHAR(20), IN pagibig VARCHAR(20), IN philhealth VARCHAR(20), IN bank_account_number VARCHAR(100), IN home_work_distance DOUBLE, IN personal_email VARCHAR(50), IN personal_telephone VARCHAR(20), IN personal_mobile VARCHAR(20), IN street_1 VARCHAR(200), IN street_2 VARCHAR(200), IN country_id INT, IN state INT, IN city VARCHAR(100), IN zip_code VARCHAR(10), IN marital_status VARCHAR(20), IN spouse_name VARCHAR(500), IN spouse_birthday DATE, IN emergency_contact VARCHAR(500), IN emergency_phone VARCHAR(20), IN nationality INT, IN identification_number VARCHAR(100), IN passport_number VARCHAR(100), IN gender VARCHAR(20), IN birthday DATE, IN certificate_level VARCHAR(20), IN field_of_study VARCHAR(200), IN school VARCHAR(200), IN place_of_birth VARCHAR(500), IN number_of_children INT, IN visa_number VARCHAR(100), IN work_permit_number VARCHAR(100), IN visa_expiry_date DATE, IN work_permit_expiry_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_details SET BADGE_ID = ?, FILE_AS = ?, FIRST_NAME = ?, MIDDLE_NAME = ?, LAST_NAME = ?, SUFFIX = ?, COMPANY = ?, JOB_POSITION = ?, DEPARTMENT = ?, WORK_LOCATION = ?, WORKING_HOURS = ?, MANAGER = ?, COACH = ?, EMPLOYEE_TYPE = ?, PERMANENCY_DATE = ?, ONBOARD_DATE = ?, WORK_EMAIL = ?, WORK_TELEPHONE = ?, WORK_MOBILE = ?, SSS = ?, TIN = ?, PAGIBIG = ?, PHILHEALTH = ?, BANK_ACCOUNT_NUMBER = ?, HOME_WORK_DISTANCE = ?, PERSONAL_EMAIL = ?, PERSONAL_TELEPHONE = ?, PERSONAL_MOBILE = ?, STREET_1 = ?, STREET_2 = ?, COUNTRY_ID = ?, STATE_ID = ?, CITY = ?, ZIP_CODE = ?, MARITAL_STATUS = ?, SPOUSE_NAME = ?, SPOUSE_BIRTHDAY = ?, EMERGENCY_CONTACT = ?, EMERGENCY_PHONE = ?, NATIONALITY = ?, IDENTIFICATION_NUMBER = ?, PASSPORT_NUMBER = ?, GENDER = ?, BIRTHDAY = ?, CERTIFICATE_LEVEL = ?, FIELD_OF_STUDY = ?, SCHOOL = ?, PLACE_OF_BIRTH = ?, NUMBER_OF_CHILDREN = ?, VISA_NUMBER = ?, WORK_PERMIT_NUMBER = ?, VISA_EXPIRY_DATE = ?, WORK_PERMIT_EXPIRY_DATE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING badge_id, file_as, first_name, middle_name, last_name, suffix, company, job_position, department, work_location, working_hours, manager, coach, employee_type, permanency_date, onboard_date, work_email, work_telephone, work_mobile, sss, tin, pagibig, philhealth, bank_account_number, home_work_distance, personal_email, personal_telephone, personal_mobile, street_1, street_2, country_id, state, city, zip_code, marital_status, spouse_name, spouse_birthday, emergency_contact, emergency_phone, nationality, identification_number, passport_number, gender, birthday, certificate_level, field_of_study, school, place_of_birth, number_of_children, visa_number, work_permit_number, visa_expiry_date, work_permit_expiry_date, transaction_log_id, record_log, employee_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_employee(IN employee_id VARCHAR(100), IN badge_id VARCHAR(100), IN file_as VARCHAR(350), IN first_name VARCHAR(100), IN middle_name VARCHAR(100), IN last_name VARCHAR(100), IN suffix VARCHAR(5), IN company VARCHAR(50), IN job_position VARCHAR(50), IN department VARCHAR(50), IN work_location VARCHAR(50), IN working_hours VARCHAR(50), IN manager VARCHAR(100), IN coach VARCHAR(100), IN employee_type VARCHAR(100), IN permanency_date DATE, IN onboard_date DATE, IN work_email VARCHAR(50), IN work_telephone VARCHAR(50), IN work_mobile VARCHAR(50), IN sss VARCHAR(20), IN tin VARCHAR(20), IN pagibig VARCHAR(20), IN philhealth VARCHAR(20), IN bank_account_number VARCHAR(100), IN home_work_distance DOUBLE, IN personal_email VARCHAR(50), IN personal_telephone VARCHAR(20), IN personal_mobile VARCHAR(20), IN street_1 VARCHAR(200), IN street_2 VARCHAR(200), IN country_id INT, IN state INT, IN city VARCHAR(100), IN zip_code VARCHAR(10), IN marital_status VARCHAR(20), IN spouse_name VARCHAR(500), IN spouse_birthday DATE, IN emergency_contact VARCHAR(500), IN emergency_phone VARCHAR(20), IN nationality INT, IN identification_number VARCHAR(100), IN passport_number VARCHAR(100), IN gender VARCHAR(20), IN birthday DATE, IN certificate_level VARCHAR(20), IN field_of_study VARCHAR(200), IN school VARCHAR(200), IN place_of_birth VARCHAR(500), IN number_of_children INT, IN visa_number VARCHAR(100), IN work_permit_number VARCHAR(100), IN visa_expiry_date DATE, IN work_permit_expiry_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_details (EMPLOYEE_ID, BADGE_ID, FILE_AS, FIRST_NAME, MIDDLE_NAME, LAST_NAME, SUFFIX, COMPANY, JOB_POSITION, DEPARTMENT, WORK_LOCATION, WORKING_HOURS, MANAGER, COACH, EMPLOYEE_TYPE, EMPLOYEE_STATUS, PERMANENCY_DATE, ONBOARD_DATE, WORK_EMAIL, WORK_TELEPHONE, WORK_MOBILE, SSS, TIN, PAGIBIG, PHILHEALTH, BANK_ACCOUNT_NUMBER, HOME_WORK_DISTANCE, PERSONAL_EMAIL, PERSONAL_TELEPHONE, PERSONAL_MOBILE, STREET_1, STREET_2, COUNTRY_ID, STATE_ID, CITY, ZIP_CODE, MARITAL_STATUS, SPOUSE_NAME, SPOUSE_BIRTHDAY, EMERGENCY_CONTACT, EMERGENCY_PHONE, NATIONALITY, IDENTIFICATION_NUMBER, PASSPORT_NUMBER, GENDER, BIRTHDAY, CERTIFICATE_LEVEL, FIELD_OF_STUDY, SCHOOL, PLACE_OF_BIRTH, NUMBER_OF_CHILDREN, VISA_NUMBER, WORK_PERMIT_NUMBER, VISA_EXPIRY_DATE, WORK_PERMIT_EXPIRY_DATE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "ACTIVE", ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_id, badge_id, file_as, first_name, middle_name, last_name, suffix, company, job_position, department, work_location, working_hours, manager, coach, employee_type, permanency_date, onboard_date, work_email, work_telephone, work_mobile, sss, tin, pagibig, philhealth, bank_account_number, home_work_distance, personal_email, personal_telephone, personal_mobile, street_1, street_2, country_id, state, city, zip_code, marital_status, spouse_name, spouse_birthday, emergency_contact, emergency_phone, nationality, identification_number, passport_number, gender, birthday, certificate_level, field_of_study, school, place_of_birth, number_of_children, visa_number, work_permit_number, visa_expiry_date, work_permit_expiry_date, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_employee_details(IN id VARCHAR(100))
BEGIN
	SET @query = 'SELECT EMPLOYEE_ID, USERNAME, BADGE_ID, EMPLOYEE_IMAGE, FILE_AS, FIRST_NAME, MIDDLE_NAME, LAST_NAME, SUFFIX, COMPANY, JOB_POSITION, DEPARTMENT, WORK_LOCATION, WORKING_HOURS, MANAGER, COACH, EMPLOYEE_TYPE, EMPLOYEE_STATUS, PERMANENCY_DATE, ONBOARD_DATE, OFFBOARD_DATE, DEPARTURE_REASON, DETAILED_REASON, WORK_EMAIL, WORK_TELEPHONE, WORK_MOBILE, SSS, TIN, PAGIBIG, PHILHEALTH, BANK_ACCOUNT_NUMBER, HOME_WORK_DISTANCE, PERSONAL_EMAIL, PERSONAL_TELEPHONE, PERSONAL_MOBILE, STREET_1, STREET_2, COUNTRY_ID, STATE_ID, CITY, ZIP_CODE, MARITAL_STATUS, SPOUSE_NAME, SPOUSE_BIRTHDAY, EMERGENCY_CONTACT, EMERGENCY_PHONE, NATIONALITY, IDENTIFICATION_NUMBER, PASSPORT_NUMBER, GENDER, BIRTHDAY, CERTIFICATE_LEVEL, FIELD_OF_STUDY, SCHOOL, PLACE_OF_BIRTH, NUMBER_OF_CHILDREN, VISA_NUMBER, WORK_PERMIT_NUMBER, VISA_EXPIRY_DATE, WORK_PERMIT_EXPIRY_DATE, WORK_PERMIT, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_details WHERE EMPLOYEE_ID = ? OR USERNAME = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_employee(IN employee_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_details WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_employee_image(IN employee_id VARCHAR(100), IN employee_image VARCHAR(500))
BEGIN
	SET @query = 'UPDATE employee_details SET EMPLOYEE_IMAGE = ? WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_image, employee_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_employee_status(IN employee_id VARCHAR(100), IN employee_status VARCHAR(100), IN offboard_date DATE, IN departure_reason VARCHAR(50), IN details_reason VARCHAR(500))
BEGIN
	SET @query = 'UPDATE employee_details SET EMPLOYEE_STATUS = ?, OFFBOARD_DATE = ?, DEPARTURE_REASON = ?, DETAILED_REASON = ? WHERE EMPLOYEE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_status, offboard_date, departure_reason, details_reason, employee_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generate_employee_options()
BEGIN
	SET @query = 'SELECT EMPLOYEE_ID, FILE_AS FROM employee_details ORDER BY FILE_AS';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //

/* Employee Department */
CREATE TABLE employee_department(
	DEPARTMENT_ID VARCHAR(50) PRIMARY KEY,
	DEPARTMENT VARCHAR(100) NOT NULL,
	PARENT_DEPARTMENT VARCHAR(50),
	MANAGER VARCHAR(100),
	STATUS TINYINT(1),
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX employee_department_index ON employee_department(DEPARTMENT_ID);

CREATE PROCEDURE check_department_exist(IN department_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_department WHERE DEPARTMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING department_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_department(IN department_id VARCHAR(50), IN department VARCHAR(100), IN parent_department VARCHAR(50), IN manager VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_department SET DEPARTMENT = ?, PARENT_DEPARTMENT = ?, MANAGER = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE DEPARTMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING department, parent_department, manager, transaction_log_id, record_log, department_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_department_status(IN department_id VARCHAR(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_department SET STATUS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE DEPARTMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING status, transaction_log_id, record_log, department_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_department(IN department_id VARCHAR(50), IN department VARCHAR(100), IN parent_department VARCHAR(50), IN manager VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_department (DEPARTMENT_ID, DEPARTMENT, PARENT_DEPARTMENT, MANAGER, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, "1", ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING department_id, department, parent_department, manager, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_department_details(IN department_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT DEPARTMENT, PARENT_DEPARTMENT, MANAGER, STATUS, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_department WHERE DEPARTMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING department_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_department(IN department_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM employee_department WHERE DEPARTMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING department_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generate_department_options(IN generation_type VARCHAR(10))
BEGIN
	IF generation_type = 'active' THEN
		SET @query = 'SELECT DEPARTMENT_ID, DEPARTMENT FROM employee_department WHERE STATUS = "1" ORDER BY DEPARTMENT';
	ELSE
		SET @query = 'SELECT DEPARTMENT_ID, DEPARTMENT FROM employee_department ORDER BY DEPARTMENT';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //

/* Employee Job Position */
CREATE TABLE employee_job_position(
	JOB_POSITION_ID VARCHAR(100) PRIMARY KEY,
	JOB_POSITION VARCHAR(100) NOT NULL,
	DESCRIPTION VARCHAR(500) NOT NULL,
	RECRUITMENT_STATUS TINYINT(1),
	DEPARTMENT VARCHAR(50),
	EXPECTED_NEW_EMPLOYEES INT(10),
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE employee_job_position_responsibility(
	RESPONSIBILITY_ID VARCHAR(100) PRIMARY KEY,
	JOB_POSITION_ID VARCHAR(100) NOT NULL,
	RESPONSIBILITY VARCHAR(500) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE employee_job_position_requirement(
	REQUIREMENT_ID VARCHAR(100) PRIMARY KEY,
	JOB_POSITION_ID VARCHAR(100) NOT NULL,
	REQUIREMENT VARCHAR(500) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE employee_job_position_qualification(
	QUALIFICATION_ID VARCHAR(100) PRIMARY KEY,
	JOB_POSITION_ID VARCHAR(100) NOT NULL,
	QUALIFICATION VARCHAR(500) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE employee_job_position_attachment(
	ATTACHMENT_ID VARCHAR(100) PRIMARY KEY,
	JOB_POSITION_ID VARCHAR(100) NOT NULL,
	ATTACHMENT_NAME VARCHAR(100) NOT NULL,
	ATTACHMENT VARCHAR(500) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

ALTER TABLE employee_job_position_responsibility
ADD FOREIGN KEY (JOB_POSITION_ID) REFERENCES employee_job_position(JOB_POSITION_ID);

ALTER TABLE employee_job_position_requirement
ADD FOREIGN KEY (JOB_POSITION_ID) REFERENCES employee_job_position(JOB_POSITION_ID);

ALTER TABLE employee_job_position_qualification
ADD FOREIGN KEY (JOB_POSITION_ID) REFERENCES employee_job_position(JOB_POSITION_ID);

ALTER TABLE employee_job_position_attachment
ADD FOREIGN KEY (JOB_POSITION_ID) REFERENCES employee_job_position(JOB_POSITION_ID);

CREATE INDEX employee_job_position_index ON employee_job_position(JOB_POSITION_ID);
CREATE INDEX employee_job_position_attachment_index ON employee_job_position_attachment(ATTACHMENT_ID);
CREATE INDEX employee_job_position_responsibility_index ON employee_job_position_responsibility(RESPONSIBILITY_ID);
CREATE INDEX employee_job_position_requirement_index ON employee_job_position_requirement(REQUIREMENT_ID);
CREATE INDEX employee_job_position_qualification_index ON employee_job_position_qualification(QUALIFICATION_ID);

CREATE PROCEDURE check_job_position_exist(IN job_position_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_job_position_responsibility_exist(IN responsibility_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position_responsibility WHERE RESPONSIBILITY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING responsibility_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_job_position_requirement_exist(IN requirement_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position_requirement WHERE REQUIREMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING requirement_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_job_position_qualification_exist(IN qualification_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position_qualification WHERE QUALIFICATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING qualification_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_job_position_attachment_exist(IN attachment_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position_attachment WHERE ATTACHMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING attachment_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_job_position(IN job_position_id VARCHAR(100), IN job_position VARCHAR(100), IN description VARCHAR(500), IN department VARCHAR(50), IN expected_new_employees INT(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_job_position SET JOB_POSITION = ?, DESCRIPTION = ?, DEPARTMENT = ?, EXPECTED_NEW_EMPLOYEES = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position, description, department, expected_new_employees, transaction_log_id, record_log, job_position_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_job_position_recruitment_status(IN job_position_id VARCHAR(50), IN recruitment_status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	IF recruitment_status = 2 THEN
		SET @query = 'UPDATE employee_job_position SET RECRUITMENT_STATUS = ?, EXPECTED_NEW_EMPLOYEES = 0, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE JOB_POSITION_ID = ?';
	ELSE
		SET @query = 'UPDATE employee_job_position SET RECRUITMENT_STATUS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE JOB_POSITION_ID = ?';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt USING recruitment_status, transaction_log_id, record_log, job_position_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_job_position_responsibility(IN responsibility_id VARCHAR(100), IN job_position_id VARCHAR(100), IN responsibility VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_job_position_responsibility SET RESPONSIBILITY = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE RESPONSIBILITY_ID = ? AND JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING responsibility, transaction_log_id, record_log, responsibility_id, job_position_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_job_position_requirement(IN requirement_id VARCHAR(100), IN job_position_id VARCHAR(100), IN requirement VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_job_position_requirement SET REQUIREMENT = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE REQUIREMENT_ID = ? AND JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING requirement, transaction_log_id, record_log, requirement_id, job_position_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_job_position_qualification(IN qualification_id VARCHAR(100), IN job_position_id VARCHAR(100), IN qualification VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_job_position_qualification SET QUALIFICATION = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE QUALIFICATION_ID = ? AND JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING qualification, transaction_log_id, record_log, qualification_id, job_position_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_job_position_attachment(IN attachment_id VARCHAR(100), IN attachment VARCHAR(500))
BEGIN
	SET @query = 'UPDATE employee_job_position_attachment SET ATTACHMENT = ? WHERE ATTACHMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING attachment, attachment_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_job_position_attachment_details(IN attachment_id VARCHAR(100), IN job_position_id VARCHAR(100), IN attachment_name VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_job_position_attachment SET ATTACHMENT_NAME = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE ATTACHMENT_ID = ? AND JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING attachment_name, transaction_log_id, record_log, attachment_id, job_position_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_job_position(IN job_position_id VARCHAR(100), IN job_position VARCHAR(100), IN description VARCHAR(500), IN department VARCHAR(50), IN expected_new_employees INT(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_job_position (JOB_POSITION_ID, JOB_POSITION, DESCRIPTION, RECRUITMENT_STATUS, DEPARTMENT, EXPECTED_NEW_EMPLOYEES, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, "2", ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id, job_position, description, department, expected_new_employees, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_job_position_responsibility(IN responsibility_id VARCHAR(100), IN job_position_id VARCHAR(100), IN responsibility VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_job_position_responsibility (RESPONSIBILITY_ID, JOB_POSITION_ID, RESPONSIBILITY, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING responsibility_id, job_position_id, responsibility, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_job_position_requirement(IN requirement_id VARCHAR(100), IN job_position_id VARCHAR(100), IN requirement VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_job_position_requirement (REQUIREMENT_ID, JOB_POSITION_ID, REQUIREMENT, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING requirement_id, job_position_id, requirement, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_job_position_qualification(IN qualification_id VARCHAR(100), IN job_position_id VARCHAR(100), IN qualification VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_job_position_qualification (QUALIFICATION_ID, JOB_POSITION_ID, QUALIFICATION, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING qualification_id, job_position_id, qualification, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_job_position_attachment(IN attachment_id VARCHAR(100), IN job_position_id VARCHAR(100), IN attachment_name VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_job_position_attachment (ATTACHMENT_ID, JOB_POSITION_ID, ATTACHMENT_NAME, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING attachment_id, job_position_id, attachment_name, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_job_position_details(IN job_position_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT JOB_POSITION, DESCRIPTION, RECRUITMENT_STATUS, DEPARTMENT, EXPECTED_NEW_EMPLOYEES, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_job_position_responsibility_details(IN responsibility_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT JOB_POSITION_ID, RESPONSIBILITY, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position_responsibility WHERE RESPONSIBILITY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING responsibility_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_job_position_requirement_details(IN requirement_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT JOB_POSITION_ID, REQUIREMENT, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position_requirement WHERE REQUIREMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING requirement_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_job_position_qualification_details(IN qualification_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT JOB_POSITION_ID, QUALIFICATION, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position_qualification WHERE QUALIFICATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING qualification_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_job_position_attachment_details(IN attachment_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT JOB_POSITION_ID, ATTACHMENT_NAME, ATTACHMENT, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position_attachment WHERE ATTACHMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING attachment_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_job_position(IN job_position_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_job_position_responsibility(IN job_position_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_responsibility WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_job_position_requirement(IN job_position_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_requirement WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_job_position_qualification(IN job_position_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_qualification WHERE JOB_POSITION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING job_position_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_job_position_responsibility(IN responsibility_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_responsibility WHERE RESPONSIBILITY_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING responsibility_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_job_position_requirement(IN requirement_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_requirement WHERE REQUIREMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING requirement_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_job_position_qualification(IN qualification_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_qualification WHERE QUALIFICATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING qualification_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_job_position_attachment(IN attachment_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_job_position_attachment WHERE ATTACHMENT_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING attachment_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generate_job_position_options(IN generation_type VARCHAR(10))
BEGIN
	IF generation_type = 'active' THEN
		SET @query = 'SELECT JOB_POSITION_ID, JOB_POSITION FROM employee_work_location WHERE RECRUITMENT_STATUS = "1" ORDER BY JOB_POSITION';
	ELSEIF generation_type = 'inactive' THEN
		SET @query = 'SELECT JOB_POSITION_ID, JOB_POSITION FROM employee_work_location WHERE RECRUITMENT_STATUS = "2" ORDER BY JOB_POSITION';
	ELSE
		SET @query = 'SELECT JOB_POSITION_ID, JOB_POSITION FROM employee_work_location ORDER BY JOB_POSITION';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt ;
	DEALLOCATE PREPARE stmt;
END //

/* Employee Work Location */
CREATE TABLE employee_work_location(
	WORK_LOCATION_ID VARCHAR(50) PRIMARY KEY,
	WORK_LOCATION VARCHAR(100) NOT NULL,
	WORK_LOCATION_ADDRESS VARCHAR(500),
	EMAIL VARCHAR(50),
	TELEPHONE VARCHAR(20),
	MOBILE VARCHAR(20),
	LOCATION_NUMBER INT(10),
	STATUS TINYINT(1),
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX employee_work_location_index ON employee_work_location(WORK_LOCATION_ID);

CREATE PROCEDURE check_work_location_exist(IN work_location_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_work_location WHERE WORK_LOCATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING work_location_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_work_location(IN work_location_id VARCHAR(50), IN work_location VARCHAR(100), IN work_location_address VARCHAR(500), IN email VARCHAR(50), IN telephone VARCHAR(50), IN mobile VARCHAR(50), IN location_number INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_work_location SET WORK_LOCATION = ?, WORK_LOCATION_ADDRESS = ?, EMAIL = ?, TELEPHONE = ?, MOBILE = ?, LOCATION_NUMBER = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE WORK_LOCATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING work_location, work_location_address, email, telephone, mobile, location_number, transaction_log_id, record_log, work_location_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_work_location_status(IN work_location_id VARCHAR(50), IN status TINYINT(1), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_work_location SET STATUS = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE WORK_LOCATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING status, transaction_log_id, record_log, work_location_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_work_location(IN work_location_id VARCHAR(50), IN work_location VARCHAR(100), IN work_location_address VARCHAR(500), IN email VARCHAR(50), IN telephone VARCHAR(50), IN mobile VARCHAR(50), IN location_number INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_work_location (WORK_LOCATION_ID, WORK_LOCATION, WORK_LOCATION_ADDRESS, EMAIL, TELEPHONE, MOBILE, LOCATION_NUMBER, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, "1", ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING work_location_id, work_location, work_location_address, email, telephone, mobile, location_number, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_work_location_details(IN work_location_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT WORK_LOCATION, WORK_LOCATION_ADDRESS, EMAIL, TELEPHONE, MOBILE, LOCATION_NUMBER, STATUS, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_work_location WHERE WORK_LOCATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING work_location_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_work_location(IN work_location_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM employee_work_location WHERE WORK_LOCATION_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING work_location_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generate_work_location_options(IN generation_type VARCHAR(10))
BEGIN
	IF generation_type = 'active' THEN
		SET @query = 'SELECT WORK_LOCATION_ID, WORK_LOCATION FROM employee_work_location WHERE STATUS = "1" ORDER BY WORK_LOCATION';
	ELSE
		SET @query = 'SELECT WORK_LOCATION_ID, WORK_LOCATION FROM employee_work_location ORDER BY WORK_LOCATION';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //

/* Employee Departure Reason */
CREATE TABLE employee_departure_reason(
	DEPARTURE_REASON_ID VARCHAR(50) PRIMARY KEY,
	DEPARTURE_REASON VARCHAR(100) NOT NULL,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX employee_departure_reason_index ON employee_departure_reason(DEPARTURE_REASON_ID);

CREATE PROCEDURE check_departure_reason_exist(IN departure_reason_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_departure_reason WHERE DEPARTURE_REASON_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING departure_reason_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_departure_reason(IN departure_reason_id VARCHAR(50), IN departure_reason VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_departure_reason SET DEPARTURE_REASON = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE DEPARTURE_REASON_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING departure_reason, transaction_log_id, record_log, departure_reason_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_departure_reason(IN departure_reason_id VARCHAR(50), IN departure_reason VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_departure_reason (DEPARTURE_REASON_ID, DEPARTURE_REASON, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING departure_reason_id, departure_reason, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_departure_reason_details(IN departure_reason_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT DEPARTURE_REASON, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_departure_reason WHERE DEPARTURE_REASON_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING departure_reason_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_departure_reason(IN departure_reason_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM employee_departure_reason WHERE DEPARTURE_REASON_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING departure_reason_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generate_departure_reason_options()
BEGIN
	SET @query = 'SELECT DEPARTURE_REASON_ID, DEPARTURE_REASON FROM employee_departure_reason ORDER BY DEPARTURE_REASON';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //

/* Employee Employee Type */
CREATE TABLE employee_employee_type(
	EMPLOYEE_TYPE_ID VARCHAR(50) PRIMARY KEY,
	EMPLOYEE_TYPE VARCHAR(100) NOT NULL,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX employee_employee_type_index ON employee_employee_type(EMPLOYEE_TYPE_ID);

CREATE PROCEDURE check_employee_type_exist(IN employee_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_employee_type WHERE EMPLOYEE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_type_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_employee_type(IN employee_type_id VARCHAR(50), IN employee_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_employee_type SET EMPLOYEE_TYPE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE EMPLOYEE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_type, transaction_log_id, record_log, employee_type_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_employee_type(IN employee_type_id VARCHAR(50), IN employee_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_employee_type (EMPLOYEE_TYPE_ID, EMPLOYEE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_type_id, employee_type, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_employee_type_details(IN employee_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT EMPLOYEE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_employee_type WHERE EMPLOYEE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_type_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_employee_type(IN employee_type_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM employee_employee_type WHERE EMPLOYEE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING employee_type_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generate_employee_type_options()
BEGIN
	SET @query = 'SELECT EMPLOYEE_TYPE_ID, EMPLOYEE_TYPE FROM employee_employee_type ORDER BY EMPLOYEE_TYPE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //

/* Employee Wage Type */
CREATE TABLE employee_wage_type(
	WAGE_TYPE_ID VARCHAR(50) PRIMARY KEY,
	WAGE_TYPE VARCHAR(100) NOT NULL,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX employee_wage_type_index ON employee_wage_type(WAGE_TYPE_ID);

CREATE PROCEDURE check_wage_type_exist(IN wage_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_wage_type WHERE WAGE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING wage_type_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_wage_type(IN wage_type_id VARCHAR(50), IN wage_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_wage_type SET WAGE_TYPE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE WAGE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING wage_type, transaction_log_id, record_log, wage_type_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_wage_type(IN wage_type_id VARCHAR(50), IN wage_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_wage_type (WAGE_TYPE_ID, WAGE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING wage_type_id, wage_type, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_wage_type_details(IN wage_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT WAGE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_wage_type WHERE WAGE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING wage_type_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_wage_type(IN wage_type_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM employee_wage_type WHERE WAGE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING wage_type_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generate_wage_type_options()
BEGIN
	SET @query = 'SELECT WAGE_TYPE_ID, WAGE_TYPE FROM employee_wage_type ORDER BY WAGE_TYPE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //

/* Employee Working Schedule */
CREATE TABLE employee_working_schedule(
	WORKING_SCHEDULE_ID VARCHAR(100) PRIMARY KEY,
	WORKING_SCHEDULE VARCHAR(100) NOT NULL,
	WORKING_SCHEDULE_TYPE VARCHAR(20) NOT NULL,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE employee_working_hours(
	WORKING_HOURS_ID VARCHAR(100) PRIMARY KEY,
	WORKING_SCHEDULE_ID VARCHAR(100) NOT NULL,
	WORKING_HOURS VARCHAR(100) NOT NULL,
	WORKING_DATE DATE,
	DAY_OF_WEEK VARCHAR(20) NOT NULL,
	DAY_PERIOD VARCHAR(20) NOT NULL,
	WORK_FROM TIME NOT NULL,
	WORK_TO TIME NOT NULL,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

ALTER TABLE employee_working_hours
ADD FOREIGN KEY (WORKING_SCHEDULE_ID) REFERENCES employee_working_schedule(WORKING_SCHEDULE_ID);

CREATE INDEX employee_working_schedule_index ON employee_working_schedule(WORKING_SCHEDULE_ID);
CREATE INDEX employee_working_hours_index ON employee_working_hours(WORKING_HOURS_ID);

CREATE PROCEDURE check_working_schedule_exist(IN working_schedule_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_working_schedule WHERE WORKING_SCHEDULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_working_schedule(IN working_schedule_id VARCHAR(100), IN working_schedule VARCHAR(100), IN working_schedule_type VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_working_schedule SET WORKING_SCHEDULE = ?, WORKING_SCHEDULE_TYPE = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE WORKING_SCHEDULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule, working_schedule_type, transaction_log_id, record_log, working_schedule_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_working_schedule(IN working_schedule_id VARCHAR(100), IN working_schedule VARCHAR(100), IN working_schedule_type VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_working_schedule (WORKING_SCHEDULE_ID, WORKING_SCHEDULE, WORKING_SCHEDULE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_id, working_schedule, working_schedule_type, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_working_schedule_details(IN working_schedule_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT WORKING_SCHEDULE, WORKING_SCHEDULE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_working_schedule WHERE WORKING_SCHEDULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_working_schedule(IN working_schedule_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_working_schedule WHERE WORKING_SCHEDULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_all_working_hours(IN working_schedule_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_working_hours WHERE WORKING_SCHEDULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_working_schedule_type(IN working_schedule_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT WORKING_SCHEDULE_TYPE_CATEGORY FROM employee_working_schedule_type WHERE WORKING_SCHEDULE_TYPE_ID = (SELECT WORKING_SCHEDULE_TYPE FROM employee_working_schedule WHERE WORKING_SCHEDULE_ID = ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generate_working_schedule_options()
BEGIN
	SET @query = 'SELECT WORKING_SCHEDULE_ID, WORKING_SCHEDULE FROM employee_working_schedule ORDER BY WORKING_SCHEDULE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_working_hours_exist(IN working_hours_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_working_hours WHERE WORKING_HOURS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_hours_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_working_hours(IN working_hours_id VARCHAR(100), IN working_schedule_id VARCHAR(100), IN working_hours VARCHAR(100), IN working_date DATE, IN day_of_week VARCHAR(20), IN day_period VARCHAR(20), IN work_from TIME, IN work_to TIME, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_working_hours SET WORKING_HOURS = ?, WORKING_DATE = ?, DAY_OF_WEEK = ?, DAY_PERIOD = ?, WORK_FROM = ?, WORK_TO = ?, TRANSACTION_LOG_ID =?, RECORD_LOG = ? WHERE WORKING_HOURS_ID = ? AND WORKING_SCHEDULE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_hours, working_date, day_of_week, day_period, work_from, work_to, transaction_log_id, record_log, working_hours_id, working_schedule_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_working_hours(IN working_hours_id VARCHAR(100), IN working_schedule_id VARCHAR(100), IN working_hours VARCHAR(100), IN working_date DATE, IN day_of_week VARCHAR(20), IN day_period VARCHAR(20), IN work_from TIME, IN work_to TIME, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_working_hours (WORKING_HOURS_ID, WORKING_SCHEDULE_ID, WORKING_HOURS, WORKING_DATE, DAY_OF_WEEK, DAY_PERIOD, WORK_FROM, WORK_TO, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_hours_id, working_schedule_id, working_hours, working_date, day_of_week, day_period, work_from, work_to, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_working_hours_details(IN working_hours_id VARCHAR(100))
BEGIN
	SET @query = 'SELECT WORKING_SCHEDULE_ID, WORKING_HOURS, WORKING_DATE, DAY_OF_WEEK, DAY_PERIOD, WORK_FROM, WORK_TO, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_working_hours WHERE WORKING_HOURS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_hours_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_working_hours(IN working_hours_id VARCHAR(100))
BEGIN
	SET @query = 'DELETE FROM employee_working_hours WHERE WORKING_HOURS_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_hours_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_fixed_working_schedule_overlap(IN working_hours_id VARCHAR(100), IN working_schedule_id VARCHAR(100), IN day_of_week VARCHAR(20), IN work_from TIME, IN work_to TIME)
BEGIN
	IF working_hours_id IS NOT NULL OR working_hours_id <> '' THEN
		SET @query = 'SELECT COUNT(WORKING_HOURS_ID) AS TOTAL FROM employee_working_hours WHERE WORKING_HOURS_ID != ? AND WORKING_SCHEDULE_ID = ? AND DAY_OF_WEEK = ? AND ((WORK_FROM BETWEEN ? AND ?) OR (WORK_TO BETWEEN ? AND ?))';

		PREPARE stmt FROM @query;
		EXECUTE stmt USING working_hours_id, working_schedule_id, day_of_week, work_from, work_to, work_from, work_to;
	ELSE
		SET @query = 'SELECT COUNT(WORKING_HOURS_ID) AS TOTAL FROM employee_working_hours WHERE WORKING_SCHEDULE_ID = ? AND DAY_OF_WEEK = ? AND ((WORK_FROM BETWEEN ? AND ?) OR (WORK_TO BETWEEN ? AND ?))';

		PREPARE stmt FROM @query;
		EXECUTE stmt USING working_schedule_id, day_of_week, work_from, work_to, work_from, work_to;
    END IF;

	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE check_flexible_working_schedule_overlap(IN working_hours_id VARCHAR(100), IN working_schedule_id VARCHAR(100), IN working_date DATE, IN work_from TIME, IN work_to TIME)
BEGIN
	IF working_hours_id IS NOT NULL OR working_hours_id <> '' THEN
		SET @query = 'SELECT COUNT(WORKING_HOURS_ID) AS TOTAL FROM employee_working_hours WHERE WORKING_HOURS_ID != ? AND WORKING_SCHEDULE_ID = ? AND WORKING_DATE = ? AND ((WORK_FROM BETWEEN ? AND ?) OR (WORK_TO BETWEEN ? AND ?))';

		PREPARE stmt FROM @query;
		EXECUTE stmt USING working_hours_id, working_schedule_id, working_date, work_from, work_to, work_from, work_to;
	ELSE
		SET @query = 'SELECT COUNT(WORKING_HOURS_ID) AS TOTAL FROM employee_working_hours WHERE WORKING_SCHEDULE_ID = ? AND WORKING_DATE = ? AND ((WORK_FROM BETWEEN ? AND ?) OR (WORK_TO BETWEEN ? AND ?))';

		PREPARE stmt FROM @query;
		EXECUTE stmt USING working_schedule_id, working_date, work_from, work_to, work_from, work_to;
    END IF;

	DEALLOCATE PREPARE stmt;
END //

/* Working Schedule Type */
CREATE TABLE employee_working_schedule_type(
	WORKING_SCHEDULE_TYPE_ID VARCHAR(50) PRIMARY KEY,
	WORKING_SCHEDULE_TYPE VARCHAR(100) NOT NULL,
	WORKING_SCHEDULE_TYPE_CATEGORY VARCHAR(20) NOT NULL,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE INDEX employee_working_schedule_type_index ON employee_working_schedule_type(WORKING_SCHEDULE_TYPE_ID);

CREATE PROCEDURE check_working_schedule_type_exist(IN working_schedule_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_working_schedule_type WHERE WORKING_SCHEDULE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_type_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE update_working_schedule_type(IN working_schedule_type_id VARCHAR(50), IN working_schedule_type VARCHAR(100), IN working_schedule_type_category VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'UPDATE employee_working_schedule_type SET WORKING_SCHEDULE_TYPE = ?, WORKING_SCHEDULE_TYPE_CATEGORY = ?, TRANSACTION_LOG_ID = ?, RECORD_LOG = ? WHERE WORKING_SCHEDULE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_type, working_schedule_type_category, transaction_log_id, record_log, working_schedule_type_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE insert_working_schedule_type(IN working_schedule_type_id VARCHAR(50), IN working_schedule_type VARCHAR(100), IN working_schedule_type_category VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @query = 'INSERT INTO employee_working_schedule_type (WORKING_SCHEDULE_TYPE_ID, WORKING_SCHEDULE_TYPE, WORKING_SCHEDULE_TYPE_CATEGORY, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(?, ?, ?, ?, ?)';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_type_id, working_schedule_type, working_schedule_type_category, transaction_log_id, record_log;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE get_working_schedule_type_details(IN working_schedule_type_id VARCHAR(50))
BEGIN
	SET @query = 'SELECT WORKING_SCHEDULE_TYPE, WORKING_SCHEDULE_TYPE_CATEGORY, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_working_schedule_type WHERE WORKING_SCHEDULE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_type_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE delete_working_schedule_type(IN working_schedule_type_id VARCHAR(50))
BEGIN
	SET @query = 'DELETE FROM employee_working_schedule_type WHERE WORKING_SCHEDULE_TYPE_ID = ?';

	PREPARE stmt FROM @query;
	EXECUTE stmt USING working_schedule_type_id;
	DEALLOCATE PREPARE stmt;
END //

CREATE PROCEDURE generate_working_schedule_type_options()
BEGIN
	SET @query = 'SELECT WORKING_SCHEDULE_TYPE_ID, WORKING_SCHEDULE_TYPE FROM employee_working_schedule_type ORDER BY WORKING_SCHEDULE_TYPE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;
END //

/* Global Stored Procedure */
CREATE PROCEDURE get_access_rights_count(IN role_id VARCHAR(100), IN access_right_id VARCHAR(100), IN access_type VARCHAR(10))
BEGIN
	IF access_type = 'module' THEN
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_module_access_rights WHERE MODULE_ID = ?  AND ROLE_ID = ?';
	ELSEIF access_type = 'page' THEN
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_page_access_rights WHERE PAGE_ID = ? AND ROLE_ID = ?';
	ELSE
		SET @query = 'SELECT COUNT(1) AS TOTAL FROM technical_action_access_rights WHERE ACTION_ID = ? AND ROLE_ID = ?';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt USING access_right_id, role_id;
	DEALLOCATE PREPARE stmt;
END //