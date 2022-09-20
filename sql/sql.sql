/* Create Table */

CREATE TABLE global_user_account(
	USERNAME VARCHAR(50) PRIMARY KEY,
	PASSWORD VARCHAR(200) NOT NULL,
	FILE_AS VARCHAR(300) NOT NULL,
	USER_STATUS VARCHAR(10) NOT NULL,
	PASSWORD_EXPIRY_DATE DATE NOT NULL,
	FAILED_LOGIN INT(1) NOT NULL,
	LAST_FAILED_LOGIN DATETIME,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_transaction_log( 
	TRANSACTION_LOG_ID VARCHAR(100) NOT NULL,
	USERNAME VARCHAR(50) NOT NULL,
	LOG_TYPE VARCHAR(100) NOT NULL,
	LOG_DATE DATETIME NOT NULL,
	LOG VARCHAR(4000)
);

CREATE TABLE global_system_parameters(
	PARAMETER_ID INT PRIMARY KEY,
	PARAMETER VARCHAR(100) NOT NULL,
	PARAMETER_DESCRIPTION VARCHAR(100) NOT NULL,
	PARAMETER_EXTENSION VARCHAR(10),
	PARAMETER_NUMBER INT NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_policy(
	POLICY_ID INT(50) PRIMARY KEY,
	POLICY VARCHAR(100) NOT NULL,
	POLICY_DESCRIPTION VARCHAR(200) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_permission(
	PERMISSION_ID INT(50) PRIMARY KEY,
	POLICY_ID INT(50) NOT NULL,
	PERMISSION VARCHAR(100) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_role(
	ROLE_ID VARCHAR(50) PRIMARY KEY,
	ROLE VARCHAR(100) NOT NULL,
	ROLE_DESCRIPTION VARCHAR(200) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_role_user_account(
	ROLE_ID VARCHAR(50) NOT NULL,
	USERNAME VARCHAR(50) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_role_permission(
	ROLE_ID VARCHAR(50) NOT NULL,
	PERMISSION_ID INT(20) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_system_code(
	SYSTEM_TYPE VARCHAR(20) NOT NULL,
	SYSTEM_CODE VARCHAR(20) NOT NULL,
	SYSTEM_DESCRIPTION VARCHAR(100) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100)
	RECORD_LOG VARCHAR(100)
);

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

CREATE TABLE global_company(
	COMPANY_ID VARCHAR(50) PRIMARY KEY,
	COMPANY_NAME VARCHAR(100) NOT NULL,
	COMPANY_LOGO VARCHAR(500),
	EMAIL VARCHAR(50),
	TELEPHONE VARCHAR(20),
	MOBILE VARCHAR(20),
	WEBSITE VARCHAR(100),
	TAX_ID VARCHAR(100),
	STREET_1 VARCHAR(200),
	STREET_2 VARCHAR(200),
	COUNTRY_ID INT,
	STATE_ID INT,
	CITY VARCHAR(100),
	ZIP_CODE VARCHAR(10),
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_country(
	COUNTRY_ID INT PRIMARY KEY,
	COUNTRY_NAME VARCHAR(200) NOT NULL,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_state(
	STATE_ID INT PRIMARY KEY,
	STATE_NAME VARCHAR(200) NOT NULL,
	COUNTRY_ID INT NOT NULL,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_notification_setting(
	NOTIFICATION_SETTING_ID INT(50) PRIMARY KEY,
	NOTIFICATION_SETTING VARCHAR(100) NOT NULL,
	NOTIFICATION_SETTING_DESCRIPTION VARCHAR(200) NOT NULL,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_notification_template(
	NOTIFICATION_SETTING_ID INT(50) PRIMARY KEY,
	NOTIFICATION_TITLE VARCHAR(500),
	NOTIFICATION_MESSAGE VARCHAR(500),
	SYSTEM_LINK VARCHAR(200),
	EMAIL_LINK VARCHAR(200),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_notification_user_account_recipient(
	NOTIFICATION_SETTING_ID INT(50),
	USERNAME VARCHAR(50) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_notification_role_recipient(
	NOTIFICATION_SETTING_ID INT(50),
	ROLE_ID VARCHAR(50) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_notification_channel(
	NOTIFICATION_SETTING_ID INT(50),
	CHANNEL VARCHAR(20) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_interface_setting(
	INTERFACE_SETTING_ID INT(50) PRIMARY KEY,
	LOGIN_BACKGROUND VARCHAR(500),
	LOGIN_LOGO VARCHAR(500),
	MENU_LOGO VARCHAR(500),
	MENU_ICON VARCHAR(500),
	FAVICON VARCHAR(500),
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_mail_configuration(
	MAIL_CONFIGURATION_ID INT PRIMARY KEY,
	MAIL_HOST VARCHAR(100) NOT NULL,
	PORT INT NOT NULL,
	SMTP_AUTH INT(1) NOT NULL,
	SMTP_AUTO_TLS INT(1) NOT NULL,
	USERNAME VARCHAR(200) NOT NULL,
	PASSWORD VARCHAR(200) NOT NULL,
	MAIL_ENCRYPTION VARCHAR(20),
	MAIL_FROM_NAME VARCHAR(200),
	MAIL_FROM_EMAIL VARCHAR(200),
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_zoom_integration(
	ZOOM_INTEGRATION_ID INT PRIMARY KEY,
	API_KEY VARCHAR(1000) NOT NULL,
	API_SECRET VARCHAR(1000) NOT NULL,
    TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE employee_department(
	DEPARTMENT_ID VARCHAR(50) PRIMARY KEY,
	DEPARTMENT VARCHAR(100) NOT NULL,
	PARENT_DEPARTMENT VARCHAR(50),
	MANAGER VARCHAR(100),
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE employee_job_position(
	JOB_POSITION_ID VARCHAR(50) PRIMARY KEY,
	JOB_POSITION VARCHAR(100) NOT NULL,
	JOB_DESCRIPTION VARCHAR(500),
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE employee_work_location(
	WORK_LOCATION_ID VARCHAR(50) PRIMARY KEY,
	WORK_LOCATION VARCHAR(100) NOT NULL,
	EMAIL VARCHAR(50),
	TELEPHONE VARCHAR(20),
	MOBILE VARCHAR(20),
	STREET_1 VARCHAR(200),
	STREET_2 VARCHAR(200),
	COUNTRY_ID INT,
	STATE_ID INT,
	CITY VARCHAR(100),
	ZIP_CODE VARCHAR(10),
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE employee_departure_reason(
	DEPARTURE_REASON_ID VARCHAR(50) PRIMARY KEY,
	DEPARTURE_REASON VARCHAR(100) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

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

CREATE TABLE employee_type(
	EMPLOYEE_TYPE_ID VARCHAR(50) PRIMARY KEY,
	EMPLOYEE_TYPE VARCHAR(100) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE employee_working_hours(
	WORKING_HOURS_ID VARCHAR(50) PRIMARY KEY,
	WORKING_HOURS VARCHAR(100) NOT NULL,
	SCHEDULE_TYPE VARCHAR(20) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE employee_working_hours_schedule(
	WORKING_HOURS_ID VARCHAR(50) PRIMARY KEY,
	START_DATE DATE,
	END_DATE DATE,
	MONDAY_MORNING_WORK_FROM TIME,
	MONDAY_MORNING_WORK_TO TIME,
	MONDAY_AFTERNOON_WORK_FROM TIME,
	MONDAY_AFTERNOON_WORK_TO TIME,
	TUESDAY_MORNING_WORK_FROM TIME,
	TUESDAY_MORNING_WORK_TO TIME,
	TUESDAY_AFTERNOON_WORK_FROM TIME,
	TUESDAY_AFTERNOON_WORK_TO TIME,
	WEDNESDAY_MORNING_WORK_FROM TIME,
	WEDNESDAY_MORNING_WORK_TO TIME,
	WEDNESDAY_AFTERNOON_WORK_FROM TIME,
	WEDNESDAY_AFTERNOON_WORK_TO TIME,
	THURSDAY_MORNING_WORK_FROM TIME,
	THURSDAY_MORNING_WORK_TO TIME,
	THURSDAY_AFTERNOON_WORK_FROM TIME,
	THURSDAY_AFTERNOON_WORK_TO TIME,
	FRIDAY_MORNING_WORK_FROM TIME,
	FRIDAY_MORNING_WORK_TO TIME,
	FRIDAY_AFTERNOON_WORK_FROM TIME,
	FRIDAY_AFTERNOON_WORK_TO TIME,
	SATURDAY_MORNING_WORK_FROM TIME,
	SATURDAY_MORNING_WORK_TO TIME,
	SATURDAY_AFTERNOON_WORK_FROM TIME,
	SATURDAY_AFTERNOON_WORK_TO TIME,
	SUNDAY_MORNING_WORK_FROM TIME,
	SUNDAY_MORNING_WORK_TO TIME,
	SUNDAY_AFTERNOON_WORK_FROM TIME,
	SUNDAY_AFTERNOON_WORK_TO TIME,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE attendance_setting(
	ATTENDANCE_SETTING_ID INT PRIMARY KEY,
	MAX_ATTENDANCE INT NOT NULL,
	LATE_GRACE_PERIOD INT NOT NULL,
	TIME_OUT_INTERVAL INT NOT NULL,
	LATE_POLICY INT NOT NULL,
	EARLY_LEAVING_POLICY INT NOT NULL,
	OVERTIME_POLICY INT NOT NULL,
	ATTENDANCE_ADJUSTMENT_RECOMMENDATION INT NOT NULL,
	ATTENDANCE_ADJUSTMENT_APPROVAL INT NOT NULL,
	ATTENDANCE_CREATION_RECOMMENDATION INT NOT NULL,
	ATTENDANCE_CREATION_APPROVAL INT NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(500),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE attendance_creation_exception(
	EMPLOYEE_ID VARCHAR(100) NOT NULL,
	EXCEPTION_TYPE VARCHAR(5) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE attendance_adjustment_exception(
	EMPLOYEE_ID VARCHAR(100) NOT NULL,
	EXCEPTION_TYPE VARCHAR(5) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE attendance_creation_approver(
	EMPLOYEE_ID VARCHAR(100) NOT NULL,
	EXCEPTION_TYPE VARCHAR(5) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE attendance_record(
	ATTENDANCE_ID VARCHAR(100) PRIMARY KEY,
	EMPLOYEE_ID VARCHAR(100) NOT NULL,
	TIME_IN DATETIME,
	TIME_IN_LOCATION VARCHAR(100),
	TIME_IN_IP_ADDRESS VARCHAR(20),
	TIME_IN_BY VARCHAR(100),
	TIME_IN_BEHAVIOR VARCHAR(20),
	TIME_IN_NOTE VARCHAR(200),
	TIME_OUT DATETIME,
	TIME_OUT_LOCATION VARCHAR(100),
	TIME_OUT_IP_ADDRESS VARCHAR(20),
	TIME_OUT_BY VARCHAR(100),
	TIME_OUT_BEHAVIOR VARCHAR(100),
	TIME_OUT_NOTE VARCHAR(200),
	LATE DOUBLE,
	EARLY_LEAVING DOUBLE,
	OVERTIME DOUBLE,
	TOTAL_WORKING_HOURS DOUBLE,
	REMARKS VARCHAR(500),
	TRANSACTION_LOG_ID VARCHAR(500),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE global_notification(
	NOTIFICATION_ID INT PRIMARY KEY,
	NOTIFICATION_FROM VARCHAR(100) NOT NULL,
	NOTIFICATION_TO VARCHAR(100),
	STATUS INT(1),
	NOTIFICATION_TITLE VARCHAR(200),
	NOTIFICATION VARCHAR(1000),
	LINK VARCHAR(500),
	NOTIFICATION_DATE DATETIME,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE attendance_adjustment(
	ADJUSTMENT_ID VARCHAR(100) PRIMARY KEY,
	ATTENDANCE_ID VARCHAR(100) NOT NULL,
	EMPLOYEE_ID VARCHAR(100) NOT NULL,
	TIME_IN DATETIME,
	TIME_OUT DATETIME,
	REASON VARCHAR(500) NOT NULL,
	ATTACHMENT VARCHAR(500),
	STATUS VARCHAR(10) NOT NULL,
	SANCTION INT(1) NOT NULL,
	CREATED_DATE DATETIME,
	FOR_RECOMMENDATION_DATE DATETIME,
	RECOMMENDATION_DATE DATETIME,
	RECOMMENDATION_BY VARCHAR(100),
	RECOMMENDATION_REMARKS VARCHAR(500),
	DECISION_DATE DATETIME,
	DECISION_BY VARCHAR(100),
	DECISION_REMARKS VARCHAR(500),
	TRANSACTION_LOG_ID VARCHAR(500),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE attendance_creation(
	CREATION_ID VARCHAR(100) PRIMARY KEY,
	EMPLOYEE_ID VARCHAR(100) NOT NULL,
	TIME_IN DATETIME,
	TIME_OUT DATETIME,
	REASON VARCHAR(500) NOT NULL,
	ATTACHMENT VARCHAR(500),
	STATUS VARCHAR(10) NOT NULL,
	SANCTION INT(1) NOT NULL,
	CREATED_DATE DATETIME,
	FOR_RECOMMENDATION_DATE DATETIME,
	RECOMMENDATION_DATE DATETIME,
	RECOMMENDATION_BY VARCHAR(100),
	RECOMMENDATION_REMARKS VARCHAR(500),
	DECISION_DATE DATETIME,
	DECISION_BY VARCHAR(100),
	DECISION_REMARKS VARCHAR(500),
	TRANSACTION_LOG_ID VARCHAR(500),
	RECORD_LOG VARCHAR(100)
)

CREATE TABLE attendance_adjustment(
	ADJUSTMENT_ID VARCHAR(100) PRIMARY KEY,
	ATTENDANCE_ID VARCHAR(100) NOT NULL,
	EMPLOYEE_ID VARCHAR(100) NOT NULL,
	TIME_IN DATETIME,
	TIME_OUT DATETIME,
	REASON VARCHAR(500) NOT NULL,
	ATTACHMENT VARCHAR(500),
	STATUS VARCHAR(10) NOT NULL,
	SANCTION INT(1) NOT NULL,
	CREATED_DATE DATETIME,
	FOR_RECOMMENDATION_DATE DATETIME,
	RECOMMENDATION_DATE DATETIME,
	RECOMMENDATION_BY VARCHAR(100),
	RECOMMENDATION_REMARKS VARCHAR(500),
	DECISION_DATE DATETIME,
	DECISION_BY VARCHAR(100),
	DECISION_REMARKS VARCHAR(500),
	TRANSACTION_LOG_ID VARCHAR(500),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE approval_type(
	APPROVAL_TYPE_ID VARCHAR(100) PRIMARY KEY,
	APPROVAL_TYPE VARCHAR(100) NOT NULL,
	APPROVAL_TYPE_DESCRIPTION VARCHAR(100),
	STATUS VARCHAR(10) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(500),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE approval_approver(
	APPROVAL_TYPE_ID VARCHAR(100),
	EMPLOYEE_ID VARCHAR(100) NOT NULL,
	DEPARTMENT VARCHAR(50),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE approval_exception(
	APPROVAL_TYPE_ID VARCHAR(100),
	EMPLOYEE_ID VARCHAR(100) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE public_holiday(
	PUBLIC_HOLIDAY_ID VARCHAR(100) PRIMARY KEY,
	PUBLIC_HOLIDAY VARCHAR(100) NOT NULL,
	HOLIDAY_DATE DATE NOT NULL,
	HOLIDAY_TYPE VARCHAR(50) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE public_holiday_work_location(
	PUBLIC_HOLIDAY_ID VARCHAR(100) NOT NULL,
	WORK_LOCATION_ID VARCHAR(50) NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE leave_type(
	LEAVE_TYPE_ID VARCHAR(100) PRIMARY KEY,
	LEAVE_TYPE VARCHAR(100) NOT NULL,
	PAID_TYPE VARCHAR(10) NOT NULL,
	ALLOCATION_TYPE VARCHAR(50) NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE leave_allocation(
	LEAVE_ALLOCATION_ID VARCHAR(100) PRIMARY KEY,
	LEAVE_TYPE_ID VARCHAR(100) NOT NULL,
	EMPLOYEE_ID VARCHAR(100) NOT NULL,
	VALIDITY_START_DATE DATE NOT NULL,
	VALIDITY_END_DATE DATE,
	DURATION DOUBLE NOT NULL,
	AVAILED DOUBLE NOT NULL,
	TRANSACTION_LOG_ID VARCHAR(100),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE leave_management(
	LEAVE_ID VARCHAR(100) PRIMARY KEY,
	EMPLOYEE_ID VARCHAR(100) NOT NULL,
	LEAVE_TYPE_ID VARCHAR(100) NOT NULL,
	REASON VARCHAR(500) NOT NULL,
	LEAVE_DATE VARCHAR(500) NOT NULL,
	START_TIME TIME NOT NULL,
	END_TIME TIME NOT NULL,
	TOTAL_HOURS DOUBLE,
	STATUS VARCHAR(10) NOT NULL,
	CREATED_DATE DATETIME,
	FOR_APPROVAL_DATE DATETIME,
	DECISION_DATE DATETIME,
	DECISION_BY VARCHAR(100),
	DECISION_REMARKS VARCHAR(500),
	TRANSACTION_LOG_ID VARCHAR(500),
	RECORD_LOG VARCHAR(100)
);

CREATE TABLE leave_supporting_document(
	LEAVE_SUPPORTING_DOCUMENT_ID VARCHAR(100) PRIMARY KEY,
	LEAVE_ID VARCHAR(100) NOT NULL,
	DOCUMENT_NAME VARCHAR(100) NOT NULL,
	SUPPORTING_DOCUMENT VARCHAR(500) NOT NULL,
	UPLOADED_BY VARCHAR(50) NOT NULL,
	UPLOAD_DATE DATETIME NOT NULL,
	RECORD_LOG VARCHAR(100)
);

/* Index */
CREATE INDEX global_user_account_index ON global_user_account(USERNAME);
CREATE INDEX global_system_parameter_index ON global_system_parameters(PARAMETER_ID);
CREATE INDEX global_policy_index ON global_policy(POLICY_ID);
CREATE INDEX global_permission_index ON global_policy(POLICY_ID);
CREATE INDEX global_role_index ON global_role(ROLE_ID);
CREATE INDEX global_system_code_index ON global_system_code(SYSTEM_TYPE, SYSTEM_CODE);
CREATE INDEX global_upload_setting_index ON global_upload_setting(UPLOAD_SETTING_ID);
CREATE INDEX global_company_index ON global_company(COMPANY_ID);
CREATE INDEX global_country_index ON global_country(COUNTRY_ID);
CREATE INDEX global_state_index ON global_state(STATE_ID);
CREATE INDEX global_notification_setting_index ON global_notification_setting(NOTIFICATION_SETTING_ID);
CREATE INDEX global_notification_template_index ON global_notification_template(NOTIFICATION_SETTING_ID);
CREATE INDEX global_interface_setting_index ON global_interface_setting(INTERFACE_SETTING_ID);
CREATE INDEX global_mail_configuration_index ON global_mail_configuration(MAIL_CONFIGURATION_ID);
CREATE INDEX global_zoom_integration_index ON global_zoom_integration(ZOOM_INTEGRATION_ID);
CREATE INDEX employee_department_index ON employee_department(DEPARTMENT_ID);
CREATE INDEX employee_job_position_index ON employee_job_position(JOB_POSITION_ID);
CREATE INDEX employee_work_location_index ON employee_work_location(WORK_LOCATION_ID);
CREATE INDEX employee_departure_reason_index ON employee_departure_reason(DEPARTURE_REASON_ID);
CREATE INDEX employee_details_index ON employee_details(EMPLOYEE_ID);
CREATE INDEX employee_type_index ON employee_type(EMPLOYEE_TYPE_ID);
CREATE INDEX employee_working_hours_index ON employee_working_hours(WORKING_HOURS_ID);
CREATE INDEX employee_working_hours_schedule_index ON employee_working_hours_schedule(WORKING_HOURS_ID);
CREATE INDEX attendance_setting_index ON attendance_setting(ATTENDANCE_SETTING_ID);
CREATE INDEX attendance_record_index ON attendance_record(ATTENDANCE_ID);
CREATE INDEX global_notification_index ON global_notification(NOTIFICATION_ID);
CREATE INDEX attendance_adjustment_index ON attendance_adjustment(ADJUSTMENT_ID);
CREATE INDEX attendance_creation_index ON attendance_creation(CREATION_ID);
CREATE INDEX approval_exception_index ON approval_exception(APPROVAL_TYPE_ID);
CREATE INDEX public_holiday_index ON public_holiday(PUBLIC_HOLIDAY_ID);
CREATE INDEX leave_type_index ON leave_type(LEAVE_TYPE_ID);
CREATE INDEX leave_allocation_index ON leave_allocation(LEAVE_ALLOCATION_ID);
CREATE INDEX leave_management_index ON leave_management(LEAVE_ID);
CREATE INDEX leave_supporting_document_index ON leave_supporting_document(LEAVE_SUPPORTING_DOCUMENT_ID);

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

	SET @query = 'SELECT PASSWORD, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_FAILED_LOGIN, TRANSACTION_LOG_ID, RECORD_LOG FROM global_user_account WHERE USERNAME = @username';

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

CREATE PROCEDURE get_permission_count(IN role_id VARCHAR(50), IN permission_id INT)
BEGIN
	SET @role_id = role_id;
	SET @permission_id = permission_id;

	SET @query = 'SELECT COUNT(PERMISSION_ID) AS TOTAL FROM global_role_permission WHERE ROLE_ID = @role_id AND PERMISSION_ID = @permission_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_policy_exist(IN policy_id INT)
BEGIN
	SET @policy_id = policy_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_policy WHERE POLICY_ID = @policy_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_policy(IN policy_id INT, IN policy VARCHAR(100), IN policy_description VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @policy_id = policy_id;
	SET @policy = policy;
	SET @policy_description = policy_description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_policy SET POLICY = @policy, POLICY_DESCRIPTION = @policy_description, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE POLICY_ID = @policy_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_policy(IN policy_id INT, IN policy VARCHAR(100), IN policy_description VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @policy_id = policy_id;
	SET @policy = policy;
	SET @policy_description = policy_description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_policy (POLICY_ID, POLICY, POLICY_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@policy_id, @policy, @policy_description, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_policy_details(IN policy_id INT)
BEGIN
	SET @policy_id = policy_id;

	SET @query = 'SELECT POLICY, POLICY_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG FROM global_policy WHERE POLICY_ID = @policy_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_policy(IN policy_id INT)
BEGIN
	SET @policy_id = policy_id;

	SET @query = 'DELETE FROM global_policy WHERE POLICY_ID = @policy_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_permission(IN policy_id INT)
BEGIN
	SET @policy_id = policy_id;

	SET @query = 'DELETE FROM global_permission WHERE POLICY_ID = @policy_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_permission_exist(IN permission_id INT)
BEGIN
	SET @permission_id = permission_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_permission WHERE PERMISSION_ID = @permission_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_permission(IN permission_id INT, IN policy_id INT, IN permission VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @permission_id = permission_id;
	SET @permission = permission;
	SET @policy_id = policy_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_permission SET POLICY_ID = @policy_id, PERMISSION = @permission, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE PERMISSION_ID = @permission_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_permission(IN permission_id INT, IN policy_id INT, IN permission VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @permission_id = permission_id;
	SET @policy_id = policy_id;
	SET @permission = permission;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_permission (PERMISSION_ID, POLICY_ID, PERMISSION, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@permission_id, @policy_id, @permission, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_permission_details(IN permission_id INT)
BEGIN
	SET @permission_id = permission_id;

	SET @query = 'SELECT POLICY_ID, PERMISSION, TRANSACTION_LOG_ID, RECORD_LOG FROM global_permission WHERE PERMISSION_ID = @permission_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_permission(IN permission_id INT)
BEGIN
	SET @permission_id = permission_id;

	SET @query = 'DELETE FROM global_permission WHERE PERMISSION_ID = @permission_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

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

CREATE PROCEDURE get_system_parameter(IN parameter_id INT)
BEGIN
	SET @parameter_id = parameter_id;

	SET @query = 'SELECT PARAMETER_EXTENSION, PARAMETER_NUMBER FROM global_system_parameters WHERE PARAMETER_ID = @parameter_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_role_exist(IN role_id VARCHAR(50))
BEGIN
	SET @role_id = role_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_role WHERE ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_role(IN role_id VARCHAR(100), IN role VARCHAR(100), IN role_description VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @role_id = role_id;
	SET @role = role;
	SET @role_description = role_description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_role SET ROLE = @role, ROLE_DESCRIPTION = @role_description, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_role(IN role_id VARCHAR(100), IN role VARCHAR(100), IN role_description VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @role_id = role_id;
	SET @role = role;
	SET @role_description = role_description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_role (ROLE_ID, ROLE, ROLE_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@role_id, @role, @role_description, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_role_details(IN role_id VARCHAR(100))
BEGIN
	SET @role_id = role_id;

	SET @query = 'SELECT ROLE, ROLE_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG FROM global_role WHERE ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_role(IN role_id VARCHAR(100))
BEGIN
	SET @role_id = role_id;

	SET @query = 'DELETE FROM global_role WHERE ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_permission_role(IN role_id VARCHAR(100))
BEGIN
	SET @role_id = role_id;

	SET @query = 'DELETE FROM global_role_permission WHERE ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_permission_role(IN role_id VARCHAR(100), IN permission_id INT, IN record_log VARCHAR(100))
BEGIN
	SET @role_id = role_id;
	SET @permission_id = permission_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_role_permission (ROLE_ID, PERMISSION_ID, RECORD_LOG) VALUES (@role_id, @permission_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_role_permission_details(IN role_id VARCHAR(100))
BEGIN
	SET @role_id = role_id;

	SET @query = 'SELECT PERMISSION_ID, RECORD_LOG FROM global_role_permission WHERE ROLE_ID = @role_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_user_account_exist(IN username VARCHAR(50))
BEGIN
	SET @username = username;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_user_account WHERE USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_user_account(IN username VARCHAR(50), IN password VARCHAR(200), IN file_as VARCHAR (300), IN password_expiry_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @username = username;
	SET @password = password;
	SET @file_as = file_as;
	SET @password_expiry_date = password_expiry_date;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	IF @password IS NULL OR @password = '' THEN
		SET @query = 'UPDATE global_user_account SET FILE_AS = @file_as, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE USERNAME = @username';
	ELSE
		SET @query = 'UPDATE global_user_account SET FILE_AS = @file_as, PASSWORD = @password, PASSWORD_EXPIRY_DATE = @password_expiry_date, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE USERNAME = @username';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_user_account(IN username VARCHAR(50), IN password VARCHAR(200), IN file_as VARCHAR (300), IN password_expiry_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @username = username;
	SET @password = password;
	SET @file_as = file_as;
	SET @password_expiry_date = password_expiry_date;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_user_account (USERNAME, PASSWORD, FILE_AS, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_FAILED_LOGIN, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@username, @password, @file_as, "ACTIVE", @password_expiry_date, 0, null, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_user_account_role(IN username VARCHAR(50), IN role_id VARCHAR(50), IN record_log VARCHAR(100))
BEGIN
	SET @username = username;
	SET @role_id = role_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_role_user_account (ROLE_ID, USERNAME, RECORD_LOG) VALUES(@role_id, @username, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_user_account_role(IN user_code VARCHAR(50))
BEGIN
	SET @user_code = user_code;

	SET @query = 'DELETE FROM global_role_user_account WHERE USERNAME = @user_code';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_user_account_lock_status(IN username VARCHAR(50), IN transaction_type VARCHAR(10), IN last_failed_login DATE, IN record_log VARCHAR(100))
BEGIN
	SET @username = username;
	SET @transaction_type = transaction_type;
	SET @last_failed_login = last_failed_login;
	SET @record_log = record_log;

	IF @transaction_type = 'unlock' THEN
		SET @query = 'UPDATE global_user_account SET FAILED_LOGIN = 0, LAST_FAILED_LOGIN = null, RECORD_LOG = @record_log WHERE USERNAME = @username';
	ELSE
		SET @query = 'UPDATE global_user_account SET FAILED_LOGIN = 5, LAST_FAILED_LOGIN = @last_failed_login, RECORD_LOG = @record_log WHERE USERNAME = @username';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_user_account_status(IN username VARCHAR(50), IN user_status VARCHAR(10), IN record_log VARCHAR(100))
BEGIN
	SET @username = username;
	SET @user_status = user_status;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_user_account SET USER_STATUS = @user_status, RECORD_LOG = @record_log WHERE USERNAME = @username';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_user_account_role_details(IN role_id VARCHAR(50), IN username VARCHAR(50))
BEGIN
	SET @role_id = role_id;
	SET @username = username;

	SET @query = 'SELECT ROLE_ID, USERNAME, RECORD_LOG FROM global_role_user_account WHERE ROLE_ID = @role_id OR USERNAME = @username';

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

CREATE PROCEDURE generate_user_account_options()
BEGIN
	SET @query = 'SELECT USERNAME FROM global_user_account ORDER BY USERNAME';

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

CREATE PROCEDURE check_system_code_exist(IN system_type VARCHAR(20), IN system_code VARCHAR(20))
BEGIN
	SET @system_type = system_type;
	SET @system_code = system_code;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_system_code WHERE SYSTEM_TYPE = @system_type AND SYSTEM_CODE = @system_code';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_system_code(IN system_type VARCHAR(100), IN system_code VARCHAR(100), IN system_description VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @system_type = system_type;
	SET @system_code = system_code;
	SET @system_description = system_description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_system_code SET SYSTEM_DESCRIPTION = @system_description, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE SYSTEM_TYPE = @system_type AND SYSTEM_CODE = @system_code';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_system_code(IN system_type VARCHAR(100), IN system_code VARCHAR(100), IN system_description VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @system_type = system_type;
	SET @system_code = system_code;
	SET @system_description = system_description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_system_code (SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@system_type, @system_code, @system_description, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

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

CREATE PROCEDURE delete_system_code(IN system_type VARCHAR(100), IN system_code VARCHAR(100))
BEGIN
	SET @system_type = system_type;
	SET @system_code = system_code;

	SET @query = 'DELETE FROM global_system_code WHERE SYSTEM_TYPE = @system_type AND SYSTEM_CODE = @system_code';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_upload_setting_exist(IN upload_setting_id INT(50))
BEGIN
	SET @upload_setting_id = upload_setting_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_upload_setting WHERE UPLOAD_SETTING_ID = @upload_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_upload_setting(IN upload_setting_id INT(50), IN upload_setting VARCHAR(200), IN description VARCHAR(200), IN max_file_size DOUBLE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @upload_setting_id = upload_setting_id;
	SET @upload_setting = upload_setting;
	SET @description = description;
	SET @max_file_size = max_file_size;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_upload_setting SET UPLOAD_SETTING = @upload_setting, DESCRIPTION = @description, MAX_FILE_SIZE = @max_file_size, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE UPLOAD_SETTING_ID = @upload_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_upload_setting(IN upload_setting_id INT(50), IN upload_setting VARCHAR(200), IN description VARCHAR(200), IN max_file_size DOUBLE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @upload_setting_id = upload_setting_id;
	SET @upload_setting = upload_setting;
	SET @description = description;
	SET @max_file_size = max_file_size;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_upload_setting (UPLOAD_SETTING_ID, UPLOAD_SETTING, DESCRIPTION, MAX_FILE_SIZE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@upload_setting_id, @upload_setting, @description, @max_file_size, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_upload_file_type(IN upload_setting_id INT(50), IN file_type VARCHAR(50), IN record_log VARCHAR(100))
BEGIN
	SET @upload_setting_id = upload_setting_id;
	SET @file_type = file_type;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_upload_file_type (UPLOAD_SETTING_ID, FILE_TYPE, RECORD_LOG) VALUES(@upload_setting_id, @file_type, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

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

CREATE PROCEDURE delete_upload_setting(IN upload_setting_id INT(50))
BEGIN
	SET @upload_setting_id = upload_setting_id;

	SET @query = 'DELETE FROM global_upload_setting WHERE UPLOAD_SETTING_ID = @upload_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_upload_file_type(IN upload_setting_id INT(50))
BEGIN
	SET @upload_setting_id = upload_setting_id;

	SET @query = 'DELETE FROM global_upload_file_type WHERE UPLOAD_SETTING_ID = @upload_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_company_exist(IN company_id INT)
BEGIN
	SET @company_id = company_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_company WHERE COMPANY_ID = @company_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_company(IN company_id VARCHAR(50), IN company_name VARCHAR(100), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN website VARCHAR(100), IN tax_id VARCHAR(100), IN street_1 VARCHAR(200), IN street_2 VARCHAR(200), IN country_id INT, IN state_id INT, IN city VARCHAR(100), IN zip_code VARCHAR(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @company_id = company_id;
	SET @company_name = company_name;
	SET @email = email;
	SET @telephone = telephone;
	SET @mobile = mobile;
	SET @website = website;
	SET @tax_id = tax_id;
	SET @street_1 = street_1;
	SET @street_2 = street_2;
	SET @country_id = country_id;
	SET @state_id = state_id;
	SET @city = city;
	SET @zip_code = zip_code;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_company SET COMPANY_NAME = @company_name, EMAIL = @email, TELEPHONE = @telephone, MOBILE = @mobile, WEBSITE = @website, TAX_ID = @tax_id, STREET_1 = @street_1, STREET_2 = @street_2, COUNTRY_ID = @country_id, STATE_ID = @state_id, CITY = @city, ZIP_CODE = @zip_code, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE COMPANY_ID = @company_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_company(IN company_id VARCHAR(50), IN company_name VARCHAR(100), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN website VARCHAR(100), IN tax_id VARCHAR(100), IN street_1 VARCHAR(200), IN street_2 VARCHAR(200), IN country_id INT, IN state_id INT, IN city VARCHAR(100), IN zip_code VARCHAR(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @company_id = company_id;
	SET @company_name = company_name;
	SET @email = email;
	SET @telephone = telephone;
	SET @mobile = mobile;
	SET @website = website;
	SET @tax_id = tax_id;
	SET @street_1 = street_1;
	SET @street_2 = street_2;
	SET @country_id = country_id;
	SET @state_id = state_id;
	SET @city = city;
	SET @zip_code = zip_code;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_company (COMPANY_ID, COMPANY_NAME, EMAIL, TELEPHONE, MOBILE, WEBSITE, TAX_ID, STREET_1, STREET_2, COUNTRY_ID, STATE_ID, CITY, ZIP_CODE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@company_id, @company_name, @email, @telephone, @mobile, @website, @tax_id, @street_1, @street_2, @country_id, @state_id, @city, @zip_code, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_company_details(IN company_id VARCHAR(50))
BEGIN
	SET @company_id = company_id;

	SET @query = 'SELECT COMPANY_NAME, COMPANY_LOGO, EMAIL, TELEPHONE, MOBILE, WEBSITE, TAX_ID, STREET_1, STREET_2, COUNTRY_ID, STATE_ID, CITY, ZIP_CODE, TRANSACTION_LOG_ID, RECORD_LOG FROM global_company WHERE COMPANY_ID = @company_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_company(IN company_id VARCHAR(50))
BEGIN
	SET @company_id = company_id;

	SET @query = 'DELETE FROM global_company WHERE COMPANY_ID = @company_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_company_logo(IN company_id VARCHAR(50), IN company_logo VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @company_id = company_id;
	SET @company_logo = company_logo;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_company SET COMPANY_LOGO = @company_logo, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE COMPANY_ID = @company_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_country_exist(IN country_id INT)
BEGIN
	SET @country_id = country_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_country WHERE COUNTRY_ID = @country_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_country(IN country_id INT, IN country_name VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @country_id = country_id;
	SET @country_name = country_name;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_country SET COUNTRY_NAME = @country_name, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE COUNTRY_ID = @country_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_country(IN country_id INT, IN country_name VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @country_id = country_id;
	SET @country_name = country_name;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_country (COUNTRY_ID, COUNTRY_NAME, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@country_id, @country_name, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_country_details(IN country_id INT)
BEGIN
	SET @country_id = country_id;

	SET @query = 'SELECT COUNTRY_NAME, TRANSACTION_LOG_ID, RECORD_LOG FROM global_country WHERE COUNTRY_ID = @country_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_country(IN country_id INT)
BEGIN
	SET @country_id = country_id;

	SET @query = 'DELETE FROM global_country WHERE COUNTRY_ID = @country_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_state_exist(IN state_id INT)
BEGIN
	SET @state_id = state_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_state WHERE STATE_ID = @state_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_state(IN state_id INT, IN state_name VARCHAR(200), IN country_id INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @state_id = state_id;
	SET @state_name = state_name;
	SET @country_id = country_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_state SET STATE_NAME = @state_name, COUNTRY_ID = @country_id, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE STATE_ID = @state_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_state(IN state_id INT, IN state_name VARCHAR(100), IN country_id INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @state_id = state_id;
	SET @state_name = state_name;
	SET @country_id = country_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_state (STATE_ID, STATE_NAME, COUNTRY_ID, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@state_id, @state_name, @country_id, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_state_details(IN state_id INT)
BEGIN
	SET @state_id = state_id;

	SET @query = 'SELECT STATE_NAME, COUNTRY_ID, TRANSACTION_LOG_ID, RECORD_LOG FROM global_state WHERE STATE_ID = @state_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_state(IN state_id INT)
BEGIN
	SET @state_id = state_id;

	SET @query = 'DELETE FROM global_state WHERE STATE_ID = @state_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_country_options()
BEGIN
	SET @query = 'SELECT COUNTRY_ID, COUNTRY_NAME FROM global_country ORDER BY COUNTRY_NAME';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_state(IN country_id INT)
BEGIN
	SET @country_id = country_id;

	SET @query = 'DELETE FROM global_country WHERE COUNTRY_ID = @country_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_notification_setting_exist(IN notification_setting_id INT)
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_setting WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_notification_setting(IN notification_setting_id INT, IN notification_setting VARCHAR(100), IN notification_setting_description VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @notification_setting = notification_setting;
	SET @notification_setting_description = notification_setting_description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_notification_setting SET NOTIFICATION_SETTING = @notification_setting, NOTIFICATION_SETTING_DESCRIPTION = @notification_setting_description, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_notification_setting(IN notification_setting_id INT, IN notification_setting VARCHAR(100), IN notification_setting_description VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @notification_setting = notification_setting;
	SET @notification_setting_description = notification_setting_description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_notification_setting (NOTIFICATION_SETTING_ID, NOTIFICATION_SETTING, NOTIFICATION_SETTING_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@notification_setting_id, @notification_setting, @notification_setting_description, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_notification_setting_details(IN notification_setting_id INT)
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'SELECT NOTIFICATION_SETTING, NOTIFICATION_SETTING_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG FROM global_notification_setting WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_notification_setting(IN notification_setting_id INT)
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'DELETE FROM global_notification_setting WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_notification_template(IN notification_setting_id INT)
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'DELETE FROM global_notification_template WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_notification_user_account_recipient(IN notification_setting_id INT)
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'DELETE FROM global_notification_user_account_recipient WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_notification_role_recipient(IN notification_setting_id INT)
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'DELETE FROM global_notification_role_recipient WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_notification_channel(IN notification_setting_id INT)
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'DELETE FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_notification_template_exist(IN notification_setting_id INT)
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_template WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_notification_template(IN notification_setting_id INT, IN notification_title VARCHAR(500), IN notificate_message VARCHAR(500), IN system_link VARCHAR(200), IN email_link VARCHAR(200), IN record_log VARCHAR(100))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @notification_title = notification_title;
	SET @notificate_message = notificate_message;
	SET @system_link = system_link;
	SET @email_link = email_link;
	SET @record_log = record_log;

	SET @query = 'UPDATE global_notification_template SET NOTIFICATION_TITLE = @notification_title, NOTIFICATION_MESSAGE = @notificate_message, SYSTEM_LINK = @system_link, EMAIL_LINK = @email_link, RECORD_LOG = @record_log WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_notification_template(IN notification_setting_id INT, IN notification_title VARCHAR(500), IN notificate_message VARCHAR(500), IN system_link VARCHAR(200), IN email_link VARCHAR(200), IN record_log VARCHAR(100))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @notification_title = notification_title;
	SET @notificate_message = notificate_message;
	SET @system_link = system_link;
	SET @email_link = email_link;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_notification_template (NOTIFICATION_SETTING_ID, NOTIFICATION_TITLE, NOTIFICATION_MESSAGE, SYSTEM_LINK, EMAIL_LINK, RECORD_LOG) VALUES(@notification_setting_id, @notification_title, @notificate_message, @system_link, @email_link, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_notification_template_details(IN notification_setting_id INT)
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'SELECT NOTIFICATION_TITLE, NOTIFICATION_MESSAGE, SYSTEM_LINK, EMAIL_LINK, RECORD_LOG FROM global_notification_template WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_notification_role_recipient(IN notification_setting_id INT, IN role_id VARCHAR(50), IN record_log VARCHAR(100))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @role_id = role_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_notification_role_recipient (NOTIFICATION_SETTING_ID, ROLE_ID, RECORD_LOG) VALUES(@notification_setting_id, @role_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_notification_user_account_recipient(IN notification_setting_id INT, IN username VARCHAR(50), IN record_log VARCHAR(100))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @username = username;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_notification_user_account_recipient (NOTIFICATION_SETTING_ID, USERNAME, RECORD_LOG) VALUES(@notification_setting_id, @username, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_notification_channel(IN notification_setting_id INT, IN channel VARCHAR(20), IN record_log VARCHAR(100))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @channel = channel;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_notification_channel (NOTIFICATION_SETTING_ID, CHANNEL, RECORD_LOG) VALUES(@notification_setting_id, @channel, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_notification_user_account_recipient_details(IN notification_setting_id INT)
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'SELECT USERNAME, RECORD_LOG FROM global_notification_user_account_recipient WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_notification_role_recipient_details(IN notification_setting_id INT)
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'SELECT ROLE_ID, RECORD_LOG FROM global_notification_role_recipient WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_notification_channel_details(IN notification_setting_id INT)
BEGIN
	SET @notification_setting_id = notification_setting_id;

	SET @query = 'SELECT CHANNEL, RECORD_LOG FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = @notification_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_interface_settings_exist(IN interface_setting_id INT)
BEGIN
	SET @interface_setting_id = interface_setting_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_interface_setting WHERE INTERFACE_SETTING_ID = @interface_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_interface_settings(IN interface_setting_id INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @interface_setting_id = interface_setting_id;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_interface_setting (INTERFACE_SETTING_ID, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@interface_setting_id, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_interface_settings_images(IN interface_setting_id INT, IN file_path VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100), IN request_type VARCHAR(20))
BEGIN
	SET @interface_setting_id = interface_setting_id;
	SET @file_path = file_path;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;
	SET @request_type = request_type;

	IF @request_type = 'login background' THEN
		SET @query = 'UPDATE global_interface_setting SET LOGIN_BACKGROUND = @file_path, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE INTERFACE_SETTING_ID = @interface_setting_id';
	ELSEIF @request_type = 'login logo' THEN
		SET @query = 'UPDATE global_interface_setting SET LOGIN_LOGO = @file_path, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE INTERFACE_SETTING_ID = @interface_setting_id';
	ELSEIF @request_type = 'menu logo' THEN
		SET @query = 'UPDATE global_interface_setting SET MENU_LOGO = @file_path, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE INTERFACE_SETTING_ID = @interface_setting_id';
	ELSEIF @request_type = 'menu icon' THEN
		SET @query = 'UPDATE global_interface_setting SET MENU_ICON = @file_path, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE INTERFACE_SETTING_ID = @interface_setting_id';
	ELSE
		SET @query = 'UPDATE global_interface_setting SET FAVICON = @file_path, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE INTERFACE_SETTING_ID = @interface_setting_id';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_interface_settings_details(IN interface_setting_id INT)
BEGIN
	SET @interface_setting_id = interface_setting_id;

	SET @query = 'SELECT LOGIN_BACKGROUND, LOGIN_LOGO, MENU_LOGO, MENU_ICON, FAVICON, TRANSACTION_LOG_ID, RECORD_LOG FROM global_interface_setting WHERE INTERFACE_SETTING_ID = @interface_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_mail_configuration_exist(IN mail_configuration_id INT)
BEGIN
	SET @mail_configuration_id = mail_configuration_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_mail_configuration WHERE MAIL_CONFIGURATION_ID = @mail_configuration_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_mail_configuration(IN mail_configuration_id INT, IN mail_host VARCHAR(100), IN port INT, IN smtp_auth INT(1), IN smtp_auto_tls INT(1), IN username VARCHAR(200), IN password VARCHAR(200), IN mail_encryption VARCHAR(20), IN mail_from_name VARCHAR(200), IN mail_from_email VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @mail_configuration_id = mail_configuration_id;
	SET @mail_host = mail_host;
	SET @port = port;
	SET @smtp_auth = smtp_auth;
	SET @smtp_auto_tls = smtp_auto_tls;
	SET @username = username;
	SET @password = password;
	SET @mail_encryption = mail_encryption;
	SET @mail_from_name = mail_from_name;
	SET @mail_from_email = mail_from_email;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;


	SET @query = 'INSERT INTO global_mail_configuration (MAIL_CONFIGURATION_ID, MAIL_HOST, PORT, SMTP_AUTH, SMTP_AUTO_TLS, USERNAME, PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_NAME, MAIL_FROM_EMAIL, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@mail_configuration_id, @mail_host, @port, @smtp_auth, @smtp_auto_tls, @username, @password, @mail_encryption, @mail_from_name, @mail_from_email, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_mail_configuration(IN mail_configuration_id INT, IN mail_host VARCHAR(100), IN port INT, IN smtp_auth INT(1), IN smtp_auto_tls INT(1), IN username VARCHAR(200), IN password VARCHAR(200), IN mail_encryption VARCHAR(20), IN mail_from_name VARCHAR(200), IN mail_from_email VARCHAR(200), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @mail_configuration_id = mail_configuration_id;
	SET @mail_host = mail_host;
	SET @port = port;
	SET @smtp_auth = smtp_auth;
	SET @smtp_auto_tls = smtp_auto_tls;
	SET @username = username;
	SET @password = password;
	SET @mail_encryption = mail_encryption;
	SET @mail_from_name = mail_from_name;
	SET @mail_from_email = mail_from_email;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	IF @password IS NULL OR @password = '' THEN
		SET @query = 'UPDATE global_mail_configuration SET MAIL_HOST = @mail_host, PORT = @port, SMTP_AUTH = @smtp_auth, SMTP_AUTO_TLS = @smtp_auto_tls, USERNAME = @username, MAIL_ENCRYPTION = @mail_encryption, MAIL_FROM_NAME = @mail_from_name, MAIL_FROM_EMAIL = @mail_from_email, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE MAIL_CONFIGURATION_ID = @mail_configuration_id';
	ELSE
		SET @query = 'UPDATE global_mail_configuration SET MAIL_HOST = @mail_host, PORT = @port, SMTP_AUTH = @smtp_auth, SMTP_AUTO_TLS = @smtp_auto_tls, USERNAME = @username, PASSWORD = @password, MAIL_ENCRYPTION = @mail_encryption, MAIL_FROM_NAME = @mail_from_name, MAIL_FROM_EMAIL = @mail_from_email, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE MAIL_CONFIGURATION_ID = @mail_configuration_id';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_mail_configuration_details(IN mail_configuration_id INT)
BEGIN
	SET @mail_configuration_id = mail_configuration_id;

	SET @query = 'SELECT MAIL_HOST, PORT, SMTP_AUTH, SMTP_AUTO_TLS, USERNAME, PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_NAME, MAIL_FROM_EMAIL, TRANSACTION_LOG_ID, RECORD_LOG FROM global_mail_configuration WHERE MAIL_CONFIGURATION_ID = @mail_configuration_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_zoom_integration_exist(IN zoom_integration_id INT)
BEGIN
	SET @zoom_integration_id = zoom_integration_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_zoom_integration WHERE ZOOM_INTEGRATION_ID = @zoom_integration_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_zoom_integration(IN zoom_integration_id INT, IN api_key VARCHAR(1000), IN api_secret VARCHAR(1000), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @zoom_integration_id = zoom_integration_id;
	SET @api_key = api_key;
	SET @api_secret = api_secret;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_zoom_integration (ZOOM_INTEGRATION_ID, API_KEY, API_SECRET, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@zoom_integration_id, @api_key, @api_secret, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_zoom_integration(IN zoom_integration_id INT, IN api_key VARCHAR(1000), IN api_secret VARCHAR(1000), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @zoom_integration_id = zoom_integration_id;
	SET @api_key = api_key;
	SET @api_secret = api_secret;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	IF @api_secret IS NULL OR @api_secret = '' THEN
		SET @query = 'UPDATE global_zoom_integration SET API_KEY = @api_key, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ZOOM_INTEGRATION_ID = @zoom_integration_id';
	ELSE
		SET @query = 'UPDATE global_zoom_integration SET API_KEY = @api_key, API_SECRET = @api_secret, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ZOOM_INTEGRATION_ID = @zoom_integration_id';
    END IF;	

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_zoom_integration_details(IN zoom_integration_id INT)
BEGIN
	SET @zoom_integration_id = zoom_integration_id;

	SET @query = 'SELECT API_KEY, API_SECRET, TRANSACTION_LOG_ID, RECORD_LOG FROM global_zoom_integration WHERE ZOOM_INTEGRATION_ID = @zoom_integration_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_department_exist(IN department_id VARCHAR(50))
BEGIN
	SET @department_id = department_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_department WHERE DEPARTMENT_ID = @department_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_department(IN department_id VARCHAR(50), IN department VARCHAR(100), IN parent_department VARCHAR(50), IN manager VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @department_id = department_id;
	SET @department = department;
	SET @parent_department = parent_department;
	SET @manager = manager;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_department SET DEPARTMENT = @department, PARENT_DEPARTMENT = @parent_department, MANAGER = @manager, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE DEPARTMENT_ID = @department_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_department(IN department_id VARCHAR(50), IN department VARCHAR(100), IN parent_department VARCHAR(50), IN manager VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @department_id = department_id;
	SET @department = department;
	SET @parent_department = parent_department;
	SET @manager = manager;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_department (DEPARTMENT_ID, DEPARTMENT, PARENT_DEPARTMENT, MANAGER, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@department_id, @department, @parent_department, @manager, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_department_details(IN department_id VARCHAR(50))
BEGIN
	SET @department_id = department_id;

	SET @query = 'SELECT DEPARTMENT, PARENT_DEPARTMENT, MANAGER, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_department WHERE DEPARTMENT_ID = @department_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_department(IN department_id VARCHAR(50))
BEGIN
	SET @department_id = department_id;

	SET @query = 'DELETE FROM employee_department WHERE DEPARTMENT_ID = @department_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_job_position_exist(IN job_position_id VARCHAR(50))
BEGIN
	SET @job_position_id = job_position_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_job_position WHERE JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_job_position(IN job_position_id VARCHAR(50), IN job_position VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @job_position_id = job_position_id;
	SET @job_position = job_position;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_job_position SET JOB_POSITION = @job_position, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_job_position(IN job_position_id VARCHAR(50), IN job_position VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @job_position_id = job_position_id;
	SET @job_position = job_position;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_job_position (JOB_POSITION_ID, JOB_POSITION, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@job_position_id, @job_position, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_job_position_details(IN job_position_id VARCHAR(50))
BEGIN
	SET @job_position_id = job_position_id;

	SET @query = 'SELECT JOB_POSITION, JOB_DESCRIPTION, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_job_position WHERE JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_job_position(IN job_position_id VARCHAR(50))
BEGIN
	SET @job_position_id = job_position_id;

	SET @query = 'DELETE FROM employee_job_position WHERE JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_job_description(IN job_position_id VARCHAR(50), IN job_description VARCHAR(500), IN record_log VARCHAR(100))
BEGIN
	SET @job_position_id = job_position_id;
	SET @job_description = job_description;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_job_position SET JOB_DESCRIPTION = @job_description, RECORD_LOG = @record_log WHERE JOB_POSITION_ID = @job_position_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_work_location_exist(IN work_location_id VARCHAR(50))
BEGIN
	SET @work_location_id = work_location_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_work_location WHERE WORK_LOCATION_ID = @work_location_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_work_location(IN work_location_id VARCHAR(50), IN work_location VARCHAR(100), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN street_1 VARCHAR(200), IN street_2 VARCHAR(200), IN country_id INT, IN state_id INT, IN city VARCHAR(100), IN zip_code VARCHAR(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @work_location_id = work_location_id;
	SET @work_location = work_location;
	SET @email = email;
	SET @telephone = telephone;
	SET @mobile = mobile;
	SET @street_1 = street_1;
	SET @street_2 = street_2;
	SET @country_id = country_id;
	SET @state_id = state_id;
	SET @city = city;
	SET @zip_code = zip_code;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_work_location SET WORK_LOCATION = @work_location, EMAIL = @email, TELEPHONE = @telephone, MOBILE = @mobile, STREET_1 = @street_1, STREET_2 = @street_2, COUNTRY_ID = @country_id, STATE_ID = @state_id, CITY = @city, ZIP_CODE = @zip_code, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE WORK_LOCATION_ID = @work_location_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_work_location(IN work_location_id VARCHAR(50), IN work_location VARCHAR(100), IN email VARCHAR(50), IN telephone VARCHAR(20), IN mobile VARCHAR(20), IN street_1 VARCHAR(200), IN street_2 VARCHAR(200), IN country_id INT, IN state_id INT, IN city VARCHAR(100), IN zip_code VARCHAR(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @work_location_id = work_location_id;
	SET @work_location = work_location;
	SET @email = email;
	SET @telephone = telephone;
	SET @mobile = mobile;
	SET @street_1 = street_1;
	SET @street_2 = street_2;
	SET @country_id = country_id;
	SET @state_id = state_id;
	SET @city = city;
	SET @zip_code = zip_code;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_work_location (WORK_LOCATION_ID, WORK_LOCATION, EMAIL, TELEPHONE, MOBILE, STREET_1, STREET_2, COUNTRY_ID, STATE_ID, CITY, ZIP_CODE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@work_location_id, @work_location, @email, @telephone, @mobile, @street_1, @street_2, @country_id, @state_id, @city, @zip_code, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_work_location_details(IN work_location_id VARCHAR(50))
BEGIN
	SET @work_location_id = work_location_id;

	SET @query = 'SELECT WORK_LOCATION, EMAIL, TELEPHONE, MOBILE, STREET_1, STREET_2, COUNTRY_ID, STATE_ID, CITY, ZIP_CODE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_work_location WHERE WORK_LOCATION_ID = @work_location_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_work_location(IN work_location_id VARCHAR(50))
BEGIN
	SET @work_location_id = work_location_id;

	SET @query = 'DELETE FROM employee_work_location WHERE WORK_LOCATION_ID = @work_location_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_departure_reason_exist(IN departure_reason_id VARCHAR(50))
BEGIN
	SET @departure_reason_id = departure_reason_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_departure_reason WHERE DEPARTURE_REASON_ID = @departure_reason_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_departure_reason(IN departure_reason_id VARCHAR(50), IN departure_reason VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @departure_reason_id = departure_reason_id;
	SET @departure_reason = departure_reason;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_departure_reason SET DEPARTURE_REASON = @departure_reason, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE DEPARTURE_REASON_ID = @departure_reason_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_departure_reason(IN departure_reason_id VARCHAR(50), IN departure_reason VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @departure_reason_id = departure_reason_id;
	SET @departure_reason = departure_reason;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_departure_reason (DEPARTURE_REASON_ID, DEPARTURE_REASON, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@departure_reason_id, @departure_reason, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_departure_reason_details(IN departure_reason_id VARCHAR(50))
BEGIN
	SET @departure_reason_id = departure_reason_id;

	SET @query = 'SELECT DEPARTURE_REASON, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_departure_reason WHERE DEPARTURE_REASON_ID = @departure_reason_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_departure_reason(IN departure_reason_id VARCHAR(50))
BEGIN
	SET @departure_reason_id = departure_reason_id;

	SET @query = 'DELETE FROM employee_departure_reason WHERE DEPARTURE_REASON_ID = @departure_reason_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_employee_type_exist(IN employee_type_id VARCHAR(50))
BEGIN
	SET @employee_type_id = employee_type_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_type WHERE EMPLOYEE_TYPE_ID = @employee_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_employee_type(IN employee_type_id VARCHAR(50), IN employee_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @employee_type_id = employee_type_id;
	SET @employee_type = employee_type;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_type SET EMPLOYEE_TYPE = @employee_type, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE EMPLOYEE_TYPE_ID = @employee_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_employee_type(IN employee_type_id VARCHAR(50), IN employee_type VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @employee_type_id = employee_type_id;
	SET @employee_type = employee_type;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_type (EMPLOYEE_TYPE_ID, EMPLOYEE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@employee_type_id, @employee_type, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_employee_type_details(IN employee_type_id VARCHAR(50))
BEGIN
	SET @employee_type_id = employee_type_id;

	SET @query = 'SELECT EMPLOYEE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_type WHERE EMPLOYEE_TYPE_ID = @employee_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_employee_type(IN employee_type_id VARCHAR(50))
BEGIN
	SET @employee_type_id = employee_type_id;

	SET @query = 'DELETE FROM employee_type WHERE EMPLOYEE_TYPE_ID = @employee_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_work_location_options()
BEGIN
	SET @query = 'SELECT WORK_LOCATION_ID, WORK_LOCATION FROM employee_work_location ORDER BY WORK_LOCATION';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_department_options()
BEGIN
	SET @query = 'SELECT DEPARTMENT_ID, DEPARTMENT FROM employee_department ORDER BY DEPARTMENT';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_job_position_options()
BEGIN
	SET @query = 'SELECT JOB_POSITION_ID, JOB_POSITION FROM employee_job_position ORDER BY JOB_POSITION';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_employee_type_options()
BEGIN
	SET @query = 'SELECT EMPLOYEE_TYPE_ID, EMPLOYEE_TYPE FROM employee_type ORDER BY EMPLOYEE_TYPE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_working_hours_options()
BEGIN
	SET @query = 'SELECT WORKING_HOURS_ID, WORKING_HOURS FROM employee_working_hours ORDER BY WORKING_HOURS';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_employee_options()
BEGIN
	SET @query = 'SELECT EMPLOYEE_ID, FILE_AS FROM employee_details ORDER BY FILE_AS';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_company_options()
BEGIN
	SET @query = 'SELECT COMPANY_ID, COMPANY_NAME FROM global_company ORDER BY COMPANY_NAME';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_departure_reason_options()
BEGIN
	SET @query = 'SELECT DEPARTURE_REASON_ID, DEPARTURE_REASON FROM employee_departure_reason ORDER BY DEPARTURE_REASON';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_employee_exist(IN employee_id INT)
BEGIN
	SET @employee_id = employee_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_details WHERE EMPLOYEE_ID = @employee_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_employee(IN employee_id VARCHAR(100), IN badge_id VARCHAR(100), IN file_as VARCHAR(350), IN first_name VARCHAR(100), IN middle_name VARCHAR(100), IN last_name VARCHAR(100), IN suffix VARCHAR(5), IN company VARCHAR(50), IN job_position VARCHAR(50), IN department VARCHAR(50), IN work_location VARCHAR(50), IN working_hours VARCHAR(50), IN manager VARCHAR(100), IN coach VARCHAR(100), IN employee_type VARCHAR(100), IN permanency_date DATE, IN onboard_date DATE, IN work_email VARCHAR(50), IN work_telephone VARCHAR(50), IN work_mobile VARCHAR(50), IN sss VARCHAR(20), IN tin VARCHAR(20), IN pagibig VARCHAR(20), IN philhealth VARCHAR(20), IN bank_account_number VARCHAR(100), IN home_work_distance DOUBLE, IN personal_email VARCHAR(50), IN personal_telephone VARCHAR(20), IN personal_mobile VARCHAR(20), IN street_1 VARCHAR(200), IN street_2 VARCHAR(200), IN country_id INT, IN state INT, IN city VARCHAR(100), IN zip_code VARCHAR(10), IN marital_status VARCHAR(20), IN spouse_name VARCHAR(500), IN spouse_birthday DATE, IN emergency_contact VARCHAR(500), IN emergency_phone VARCHAR(20), IN nationality INT, IN identification_number VARCHAR(100), IN passport_number VARCHAR(100), IN gender VARCHAR(20), IN birthday DATE, IN certificate_level VARCHAR(20), IN field_of_study VARCHAR(200), IN school VARCHAR(200), IN place_of_birth VARCHAR(500), IN number_of_children INT, IN visa_number VARCHAR(100), IN work_permit_number VARCHAR(100), IN visa_expiry_date DATE, IN work_permit_expiry_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @employee_id = employee_id;
	SET @badge_id = badge_id;
	SET @file_as = file_as;
	SET @first_name = first_name;
	SET @middle_name = middle_name;
	SET @last_name = last_name;
	SET @suffix = suffix;
	SET @company = company;
	SET @job_position = job_position;
	SET @department = department;
	SET @work_location = work_location;
	SET @working_hours = working_hours;
	SET @manager = manager;
	SET @coach = coach;
	SET @employee_type = employee_type;
	SET @permanency_date = permanency_date;
	SET @onboard_date = onboard_date;
	SET @work_email = work_email;
	SET @work_telephone = work_telephone;
	SET @work_mobile = work_mobile;
	SET @sss = sss;
	SET @tin = tin;
	SET @pagibig = pagibig;
	SET @philhealth = philhealth;
	SET @bank_account_number = bank_account_number;
	SET @home_work_distance = home_work_distance;
	SET @personal_email = personal_email;
	SET @personal_telephone = personal_telephone;
	SET @personal_mobile = personal_mobile;
	SET @street_1 = street_1;
	SET @street_2 = street_2;
	SET @country_id = country_id;
	SET @state = state;
	SET @city = city;
	SET @zip_code = zip_code;
	SET @marital_status = marital_status;
	SET @spouse_name = spouse_name;
	SET @spouse_birthday = spouse_birthday;
	SET @emergency_contact = emergency_contact;
	SET @emergency_phone = emergency_phone;
	SET @nationality = nationality;
	SET @identification_number = identification_number;
	SET @passport_number = passport_number;
	SET @gender = gender;
	SET @birthday = birthday;
	SET @certificate_level = certificate_level;
	SET @field_of_study = field_of_study;
	SET @school = school;
	SET @place_of_birth = place_of_birth;
	SET @number_of_children = number_of_children;
	SET @visa_number = visa_number;
	SET @work_permit_number = work_permit_number;
	SET @visa_expiry_date = visa_expiry_date;
	SET @work_permit_expiry_date = work_permit_expiry_date;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_details SET BADGE_ID = @badge_id, FILE_AS = @file_as, FIRST_NAME = @first_name, MIDDLE_NAME = @middle_name, LAST_NAME = @last_name, SUFFIX = @suffix, COMPANY = @company, JOB_POSITION = @job_position, DEPARTMENT = @department, WORK_LOCATION = @work_location, WORKING_HOURS = @working_hours, MANAGER = @manager, COACH = @coach, EMPLOYEE_TYPE = @employee_type, PERMANENCY_DATE = @permanency_date, ONBOARD_DATE = @onboard_date, WORK_EMAIL = @work_email, WORK_TELEPHONE = @work_telephone, WORK_MOBILE = @work_mobile, SSS = @sss, TIN = @tin, PAGIBIG = @pagibig, PHILHEALTH = @philhealth, BANK_ACCOUNT_NUMBER = @bank_account_number, HOME_WORK_DISTANCE = @home_work_distance, PERSONAL_EMAIL = @personal_email, PERSONAL_TELEPHONE = @personal_telephone, PERSONAL_MOBILE = @personal_mobile, STREET_1 = @street_1, STREET_2 = @street_2, COUNTRY_ID = @country_id, STATE_ID = @state, CITY = @city, ZIP_CODE = @zip_code, MARITAL_STATUS = @marital_status, SPOUSE_NAME = @spouse_name, SPOUSE_BIRTHDAY = @spouse_birthday, EMERGENCY_CONTACT = @emergency_contact, EMERGENCY_PHONE = @emergency_phone, NATIONALITY = @nationality, IDENTIFICATION_NUMBER = @identification_number, PASSPORT_NUMBER = @passport_number, GENDER = @gender, BIRTHDAY = @birthday, CERTIFICATE_LEVEL = @certificate_level, FIELD_OF_STUDY = @field_of_study, SCHOOL = @school, PLACE_OF_BIRTH = @place_of_birth, NUMBER_OF_CHILDREN = @number_of_children, VISA_NUMBER = @visa_number, WORK_PERMIT_NUMBER = @work_permit_number, VISA_EXPIRY_DATE = @visa_expiry_date, WORK_PERMIT_EXPIRY_DATE = @work_permit_expiry_date, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE EMPLOYEE_ID = @employee_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_employee(IN employee_id VARCHAR(100), IN badge_id VARCHAR(100), IN file_as VARCHAR(350), IN first_name VARCHAR(100), IN middle_name VARCHAR(100), IN last_name VARCHAR(100), IN suffix VARCHAR(5), IN company VARCHAR(50), IN job_position VARCHAR(50), IN department VARCHAR(50), IN work_location VARCHAR(50), IN working_hours VARCHAR(50), IN manager VARCHAR(100), IN coach VARCHAR(100), IN employee_type VARCHAR(100), IN permanency_date DATE, IN onboard_date DATE, IN work_email VARCHAR(50), IN work_telephone VARCHAR(50), IN work_mobile VARCHAR(50), IN sss VARCHAR(20), IN tin VARCHAR(20), IN pagibig VARCHAR(20), IN philhealth VARCHAR(20), IN bank_account_number VARCHAR(100), IN home_work_distance DOUBLE, IN personal_email VARCHAR(50), IN personal_telephone VARCHAR(20), IN personal_mobile VARCHAR(20), IN street_1 VARCHAR(200), IN street_2 VARCHAR(200), IN country_id INT, IN state INT, IN city VARCHAR(100), IN zip_code VARCHAR(10), IN marital_status VARCHAR(20), IN spouse_name VARCHAR(500), IN spouse_birthday DATE, IN emergency_contact VARCHAR(500), IN emergency_phone VARCHAR(20), IN nationality INT, IN identification_number VARCHAR(100), IN passport_number VARCHAR(100), IN gender VARCHAR(20), IN birthday DATE, IN certificate_level VARCHAR(20), IN field_of_study VARCHAR(200), IN school VARCHAR(200), IN place_of_birth VARCHAR(500), IN number_of_children INT, IN visa_number VARCHAR(100), IN work_permit_number VARCHAR(100), IN visa_expiry_date DATE, IN work_permit_expiry_date DATE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @employee_id = employee_id;
	SET @badge_id = badge_id;
	SET @file_as = file_as;
	SET @first_name = first_name;
	SET @middle_name = middle_name;
	SET @last_name = last_name;
	SET @suffix = suffix;
	SET @company = company;
	SET @job_position = job_position;
	SET @department = department;
	SET @work_location = work_location;
	SET @working_hours = working_hours;
	SET @manager = manager;
	SET @coach = coach;
	SET @employee_type = employee_type;
	SET @permanency_date = permanency_date;
	SET @onboard_date = onboard_date;
	SET @work_email = work_email;
	SET @work_telephone = work_telephone;
	SET @work_mobile = work_mobile;
	SET @sss = sss;
	SET @tin = tin;
	SET @pagibig = pagibig;
	SET @philhealth = philhealth;
	SET @bank_account_number = bank_account_number;
	SET @home_work_distance = home_work_distance;
	SET @personal_email = personal_email;
	SET @personal_telephone = personal_telephone;
	SET @personal_mobile = personal_mobile;
	SET @street_1 = street_1;
	SET @street_2 = street_2;
	SET @country_id = country_id;
	SET @state = state;
	SET @city = city;
	SET @zip_code = zip_code;
	SET @marital_status = marital_status;
	SET @spouse_name = spouse_name;
	SET @spouse_birthday = spouse_birthday;
	SET @emergency_contact = emergency_contact;
	SET @emergency_phone = emergency_phone;
	SET @nationality = nationality;
	SET @identification_number = identification_number;
	SET @passport_number = passport_number;
	SET @gender = gender;
	SET @birthday = birthday;
	SET @certificate_level = certificate_level;
	SET @field_of_study = field_of_study;
	SET @school = school;
	SET @place_of_birth = place_of_birth;
	SET @number_of_children = number_of_children;
	SET @visa_number = visa_number;
	SET @work_permit_number = work_permit_number;
	SET @visa_expiry_date = visa_expiry_date;
	SET @work_permit_expiry_date = work_permit_expiry_date;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_details (EMPLOYEE_ID, BADGE_ID, FILE_AS, FIRST_NAME, MIDDLE_NAME, LAST_NAME, SUFFIX, COMPANY, JOB_POSITION, DEPARTMENT, WORK_LOCATION, WORKING_HOURS, MANAGER, COACH, EMPLOYEE_TYPE, EMPLOYEE_STATUS, PERMANENCY_DATE, ONBOARD_DATE, WORK_EMAIL, WORK_TELEPHONE, WORK_MOBILE, SSS, TIN, PAGIBIG, PHILHEALTH, BANK_ACCOUNT_NUMBER, HOME_WORK_DISTANCE, PERSONAL_EMAIL, PERSONAL_TELEPHONE, PERSONAL_MOBILE, STREET_1, STREET_2, COUNTRY_ID, STATE_ID, CITY, ZIP_CODE, MARITAL_STATUS, SPOUSE_NAME, SPOUSE_BIRTHDAY, EMERGENCY_CONTACT, EMERGENCY_PHONE, NATIONALITY, IDENTIFICATION_NUMBER, PASSPORT_NUMBER, GENDER, BIRTHDAY, CERTIFICATE_LEVEL, FIELD_OF_STUDY, SCHOOL, PLACE_OF_BIRTH, NUMBER_OF_CHILDREN, VISA_NUMBER, WORK_PERMIT_NUMBER, VISA_EXPIRY_DATE, WORK_PERMIT_EXPIRY_DATE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@employee_id, @badge_id, @file_as, @first_name, @middle_name, @last_name, @suffix, @company, @job_position, @department, @work_location, @working_hours, @manager, @coach, @employee_type, "ACTIVE", @permanency_date, @onboard_date, @work_email, @work_telephone, @work_mobile, @sss, @tin, @pagibig, @philhealth, @bank_account_number, @home_work_distance, @personal_email, @personal_telephone, @personal_mobile, @street_1, @street_2, @country_id, @state, @city, @zip_code, @marital_status, @spouse_name, @spouse_birthday, @emergency_contact, @emergency_phone, @nationality, @identification_number, @passport_number, @gender, @birthday, @certificate_level, @field_of_study, @school, @place_of_birth, @number_of_children, @visa_number, @work_permit_number, @visa_expiry_date, @work_permit_expiry_date, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_employee_details(IN id VARCHAR(100))
BEGIN
	SET @id = id;

	SET @query = 'SELECT EMPLOYEE_ID, USERNAME, BADGE_ID, EMPLOYEE_IMAGE, FILE_AS, FIRST_NAME, MIDDLE_NAME, LAST_NAME, SUFFIX, COMPANY, JOB_POSITION, DEPARTMENT, WORK_LOCATION, WORKING_HOURS, MANAGER, COACH, EMPLOYEE_TYPE, EMPLOYEE_STATUS, PERMANENCY_DATE, ONBOARD_DATE, OFFBOARD_DATE, DEPARTURE_REASON, DETAILED_REASON, WORK_EMAIL, WORK_TELEPHONE, WORK_MOBILE, SSS, TIN, PAGIBIG, PHILHEALTH, BANK_ACCOUNT_NUMBER, HOME_WORK_DISTANCE, PERSONAL_EMAIL, PERSONAL_TELEPHONE, PERSONAL_MOBILE, STREET_1, STREET_2, COUNTRY_ID, STATE_ID, CITY, ZIP_CODE, MARITAL_STATUS, SPOUSE_NAME, SPOUSE_BIRTHDAY, EMERGENCY_CONTACT, EMERGENCY_PHONE, NATIONALITY, IDENTIFICATION_NUMBER, PASSPORT_NUMBER, GENDER, BIRTHDAY, CERTIFICATE_LEVEL, FIELD_OF_STUDY, SCHOOL, PLACE_OF_BIRTH, NUMBER_OF_CHILDREN, VISA_NUMBER, WORK_PERMIT_NUMBER, VISA_EXPIRY_DATE, WORK_PERMIT_EXPIRY_DATE, WORK_PERMIT, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_details WHERE EMPLOYEE_ID = @id OR USERNAME = @id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_employee(IN employee_id VARCHAR(100))
BEGIN
	SET @employee_id = employee_id;

	SET @query = 'DELETE FROM employee_details WHERE EMPLOYEE_ID = @employee_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_employee_image(IN employee_id VARCHAR(100), IN employee_image VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @employee_id = employee_id;
	SET @employee_image = employee_image;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_details SET EMPLOYEE_IMAGE = @employee_image, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE EMPLOYEE_ID = @employee_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_employee_status(IN employee_id VARCHAR(100), IN employee_status VARCHAR(100), IN offboard_date DATE, IN departure_reason VARCHAR(50), IN details_reason VARCHAR(500), IN record_log VARCHAR(100))
BEGIN
	SET @employee_id = employee_id;
	SET @employee_status = employee_status;
	SET @offboard_date = offboard_date;
	SET @departure_reason = departure_reason;
	SET @details_reason = details_reason;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_details SET EMPLOYEE_STATUS = @employee_status, OFFBOARD_DATE = @offboard_date, DEPARTURE_REASON = @departure_reason, DETAILED_REASON = @details_reason, RECORD_LOG = @record_log WHERE EMPLOYEE_ID = @employee_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_work_permit(IN employee_id VARCHAR(100), IN work_permit VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @employee_id = employee_id;
	SET @work_permit = work_permit;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_details SET WORK_PERMIT = @work_permit, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE EMPLOYEE_ID = @employee_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_working_hours_exist(IN working_hours_id VARCHAR(50))
BEGIN
	SET @working_hours_id = working_hours_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_working_hours WHERE WORKING_HOURS_ID = @working_hours_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_working_hours(IN working_hours_id VARCHAR(50), IN working_hours VARCHAR(100), IN schedule_type VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @working_hours_id = working_hours_id;
	SET @working_hours = working_hours;
	SET @schedule_type = schedule_type;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_working_hours SET WORKING_HOURS = @working_hours, SCHEDULE_TYPE = @schedule_type, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE WORKING_HOURS_ID = @working_hours_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_working_hours(IN working_hours_id VARCHAR(50), IN working_hours VARCHAR(100), IN schedule_type VARCHAR(20), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @working_hours_id = working_hours_id;
	SET @working_hours = working_hours;
	SET @schedule_type = schedule_type;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_working_hours (WORKING_HOURS_ID, WORKING_HOURS, SCHEDULE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@working_hours_id, @working_hours, @schedule_type, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_working_hours_details(IN working_hours_id VARCHAR(50))
BEGIN
	SET @working_hours_id = working_hours_id;

	SET @query = 'SELECT WORKING_HOURS, SCHEDULE_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM employee_working_hours WHERE WORKING_HOURS_ID = @working_hours_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_working_hours(IN working_hours_id VARCHAR(50))
BEGIN
	SET @working_hours_id = working_hours_id;

	SET @query = 'DELETE FROM employee_working_hours WHERE WORKING_HOURS_ID = @working_hours_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_working_hours_schedule_exist(IN working_hours_id VARCHAR(50))
BEGIN
	SET @working_hours_id = working_hours_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM employee_working_hours_schedule WHERE WORKING_HOURS_ID = @working_hours_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_working_hours_schedule(IN working_hours_id VARCHAR(50), IN start_date DATE, IN end_date DATE, IN monday_morning_work_from TIME, IN monday_morning_work_to TIME, IN monday_afternoon_work_from TIME, IN monday_afternoon_work_to TIME, IN tuesday_morning_work_from TIME, IN tuesday_morning_work_to TIME, IN tuesday_afternoon_work_from TIME, IN tuesday_afternoon_work_to TIME, IN wednesday_morning_work_from TIME, IN wednesday_morning_work_to TIME, IN wednesday_afternoon_work_from TIME, IN wednesday_afternoon_work_to TIME, IN thursday_morning_work_from TIME, IN thursday_morning_work_to TIME, IN thursday_afternoon_work_from TIME, IN thursday_afternoon_work_to TIME, IN friday_morning_work_from TIME, IN friday_morning_work_to TIME, IN friday_afternoon_work_from TIME, IN friday_afternoon_work_to TIME, IN saturday_morning_work_from TIME, IN saturday_morning_work_to TIME, IN saturday_afternoon_work_from TIME, IN saturday_afternoon_work_to TIME, IN sunday_morning_work_from TIME, IN sunday_morning_work_to TIME, IN sunday_afternoon_work_from TIME, IN sunday_afternoon_work_to TIME, IN record_log VARCHAR(100))
BEGIN
	SET @working_hours_id = working_hours_id;
	SET @start_date = start_date;
	SET @end_date = end_date;
	SET @monday_morning_work_from = monday_morning_work_from;
	SET @monday_morning_work_to = monday_morning_work_to;
	SET @monday_afternoon_work_from = monday_afternoon_work_from;
	SET @monday_afternoon_work_to = monday_afternoon_work_to;
	SET @tuesday_morning_work_from = tuesday_morning_work_from;
	SET @tuesday_morning_work_to = tuesday_morning_work_to;
	SET @tuesday_afternoon_work_from = tuesday_afternoon_work_from;
	SET @tuesday_afternoon_work_to = tuesday_afternoon_work_to;
	SET @wednesday_morning_work_from = wednesday_morning_work_from;
	SET @wednesday_morning_work_to = wednesday_morning_work_to;
	SET @wednesday_afternoon_work_from = wednesday_afternoon_work_from;
	SET @wednesday_afternoon_work_to = wednesday_afternoon_work_to;
	SET @thursday_morning_work_from = thursday_morning_work_from;
	SET @thursday_morning_work_to = thursday_morning_work_to;
	SET @thursday_afternoon_work_from = thursday_afternoon_work_from;
	SET @thursday_afternoon_work_to = thursday_afternoon_work_to;
	SET @friday_morning_work_from = friday_morning_work_from;
	SET @friday_morning_work_to = friday_morning_work_to;
	SET @friday_afternoon_work_from = friday_afternoon_work_from;
	SET @friday_afternoon_work_to = friday_afternoon_work_to;
	SET @saturday_morning_work_from = saturday_morning_work_from;
	SET @saturday_morning_work_to = saturday_morning_work_to;
	SET @saturday_afternoon_work_from = saturday_afternoon_work_from;
	SET @saturday_afternoon_work_to = saturday_afternoon_work_to;
	SET @sunday_morning_work_from = sunday_morning_work_from;
	SET @sunday_morning_work_to = sunday_morning_work_to;
	SET @sunday_afternoon_work_from = sunday_afternoon_work_from;
	SET @sunday_afternoon_work_to = sunday_afternoon_work_to;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_working_hours_schedule SET START_DATE = @start_date, END_DATE = @end_date, MONDAY_MORNING_WORK_FROM = @monday_morning_work_from, MONDAY_MORNING_WORK_TO = @monday_morning_work_to, MONDAY_AFTERNOON_WORK_FROM = @monday_afternoon_work_from, MONDAY_AFTERNOON_WORK_TO = @monday_afternoon_work_to, TUESDAY_MORNING_WORK_FROM = @tuesday_morning_work_from, TUESDAY_MORNING_WORK_TO = @tuesday_morning_work_to, TUESDAY_AFTERNOON_WORK_FROM = @tuesday_afternoon_work_from, TUESDAY_AFTERNOON_WORK_TO = @tuesday_afternoon_work_to, WEDNESDAY_MORNING_WORK_FROM = @wednesday_morning_work_from, WEDNESDAY_MORNING_WORK_TO = @wednesday_morning_work_to, WEDNESDAY_AFTERNOON_WORK_FROM = @wednesday_afternoon_work_from, WEDNESDAY_AFTERNOON_WORK_TO = @wednesday_afternoon_work_to, THURSDAY_MORNING_WORK_FROM = @thursday_morning_work_from, THURSDAY_MORNING_WORK_TO = @thursday_morning_work_to, THURSDAY_AFTERNOON_WORK_FROM = @thursday_afternoon_work_from, THURSDAY_AFTERNOON_WORK_TO = @thursday_afternoon_work_to, FRIDAY_MORNING_WORK_FROM = @friday_morning_work_from, FRIDAY_MORNING_WORK_TO = @friday_morning_work_to, FRIDAY_AFTERNOON_WORK_FROM = @friday_afternoon_work_from, FRIDAY_AFTERNOON_WORK_TO = @friday_afternoon_work_to, SATURDAY_MORNING_WORK_FROM = @saturday_morning_work_from, SATURDAY_MORNING_WORK_TO = @saturday_morning_work_to, SATURDAY_AFTERNOON_WORK_FROM = @saturday_afternoon_work_from, SATURDAY_AFTERNOON_WORK_TO = @saturday_afternoon_work_to, SUNDAY_MORNING_WORK_FROM = @sunday_morning_work_from, SUNDAY_MORNING_WORK_TO = @sunday_morning_work_to, SUNDAY_AFTERNOON_WORK_FROM = @sunday_afternoon_work_from, SUNDAY_AFTERNOON_WORK_TO = @sunday_afternoon_work_to, RECORD_LOG = @record_log WHERE WORKING_HOURS_ID = @working_hours_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_working_hours_schedule(IN working_hours_id VARCHAR(50), IN start_date DATE, IN end_date DATE, IN monday_morning_work_from TIME, IN monday_morning_work_to TIME, IN monday_afternoon_work_from TIME, IN monday_afternoon_work_to TIME, IN tuesday_morning_work_from TIME, IN tuesday_morning_work_to TIME, IN tuesday_afternoon_work_from TIME, IN tuesday_afternoon_work_to TIME, IN wednesday_morning_work_from TIME, IN wednesday_morning_work_to TIME, IN wednesday_afternoon_work_from TIME, IN wednesday_afternoon_work_to TIME, IN thursday_morning_work_from TIME, IN thursday_morning_work_to TIME, IN thursday_afternoon_work_from TIME, IN thursday_afternoon_work_to TIME, IN friday_morning_work_from TIME, IN friday_morning_work_to TIME, IN friday_afternoon_work_from TIME, IN friday_afternoon_work_to TIME, IN saturday_morning_work_from TIME, IN saturday_morning_work_to TIME, IN saturday_afternoon_work_from TIME, IN saturday_afternoon_work_to TIME, IN sunday_morning_work_from TIME, IN sunday_morning_work_to TIME, IN sunday_afternoon_work_from TIME, IN sunday_afternoon_work_to TIME, IN record_log VARCHAR(100))
BEGIN
	SET @working_hours_id = working_hours_id;
	SET @start_date = start_date;
	SET @end_date = end_date;
	SET @monday_morning_work_from = monday_morning_work_from;
	SET @monday_morning_work_to = monday_morning_work_to;
	SET @monday_afternoon_work_from = monday_afternoon_work_from;
	SET @monday_afternoon_work_to = monday_afternoon_work_to;
	SET @tuesday_morning_work_from = tuesday_morning_work_from;
	SET @tuesday_morning_work_to = tuesday_morning_work_to;
	SET @tuesday_afternoon_work_from = tuesday_afternoon_work_from;
	SET @tuesday_afternoon_work_to = tuesday_afternoon_work_to;
	SET @wednesday_morning_work_from = wednesday_morning_work_from;
	SET @wednesday_morning_work_to = wednesday_morning_work_to;
	SET @wednesday_afternoon_work_from = wednesday_afternoon_work_from;
	SET @wednesday_afternoon_work_to = wednesday_afternoon_work_to;
	SET @thursday_morning_work_from = thursday_morning_work_from;
	SET @thursday_morning_work_to = thursday_morning_work_to;
	SET @thursday_afternoon_work_from = thursday_afternoon_work_from;
	SET @thursday_afternoon_work_to = thursday_afternoon_work_to;
	SET @friday_morning_work_from = friday_morning_work_from;
	SET @friday_morning_work_to = friday_morning_work_to;
	SET @friday_afternoon_work_from = friday_afternoon_work_from;
	SET @friday_afternoon_work_to = friday_afternoon_work_to;
	SET @saturday_morning_work_from = saturday_morning_work_from;
	SET @saturday_morning_work_to = saturday_morning_work_to;
	SET @saturday_afternoon_work_from = saturday_afternoon_work_from;
	SET @saturday_afternoon_work_to = saturday_afternoon_work_to;
	SET @sunday_morning_work_from = sunday_morning_work_from;
	SET @sunday_morning_work_to = sunday_morning_work_to;
	SET @sunday_afternoon_work_from = sunday_afternoon_work_from;
	SET @sunday_afternoon_work_to = sunday_afternoon_work_to;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO employee_working_hours_schedule (WORKING_HOURS_ID, START_DATE, END_DATE, MONDAY_MORNING_WORK_FROM, MONDAY_MORNING_WORK_TO, MONDAY_AFTERNOON_WORK_FROM, MONDAY_AFTERNOON_WORK_TO, TUESDAY_MORNING_WORK_FROM, TUESDAY_MORNING_WORK_TO, TUESDAY_AFTERNOON_WORK_FROM, TUESDAY_AFTERNOON_WORK_TO, WEDNESDAY_MORNING_WORK_FROM, WEDNESDAY_MORNING_WORK_TO, WEDNESDAY_AFTERNOON_WORK_FROM, WEDNESDAY_AFTERNOON_WORK_TO, THURSDAY_MORNING_WORK_FROM, THURSDAY_MORNING_WORK_TO, THURSDAY_AFTERNOON_WORK_FROM, THURSDAY_AFTERNOON_WORK_TO, FRIDAY_MORNING_WORK_FROM, FRIDAY_MORNING_WORK_TO, FRIDAY_AFTERNOON_WORK_FROM, FRIDAY_AFTERNOON_WORK_TO, SATURDAY_MORNING_WORK_FROM, SATURDAY_MORNING_WORK_TO, SATURDAY_AFTERNOON_WORK_FROM, SATURDAY_AFTERNOON_WORK_TO, SUNDAY_MORNING_WORK_FROM, SUNDAY_MORNING_WORK_TO, SUNDAY_AFTERNOON_WORK_FROM, SUNDAY_AFTERNOON_WORK_TO, RECORD_LOG) VALUES(@working_hours_id, @start_date, @end_date, @monday_morning_work_from, @monday_morning_work_to, @monday_afternoon_work_from, @monday_afternoon_work_to, @tuesday_morning_work_from, @tuesday_morning_work_to, @tuesday_afternoon_work_from, @tuesday_afternoon_work_to, @wednesday_morning_work_from, @wednesday_morning_work_to, @wednesday_afternoon_work_from, @wednesday_afternoon_work_to, @thursday_morning_work_from, @thursday_morning_work_to, @thursday_afternoon_work_from, @thursday_afternoon_work_to,  @friday_morning_work_from, @friday_morning_work_to, @friday_afternoon_work_from, @friday_afternoon_work_to, @saturday_morning_work_from, @saturday_morning_work_to, @saturday_afternoon_work_from, @saturday_afternoon_work_to, @sunday_morning_work_from, @sunday_morning_work_to, @sunday_afternoon_work_from, @sunday_afternoon_work_to, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_working_hours_schedule_details(IN working_hours_id VARCHAR(50))
BEGIN
	SET @working_hours_id = working_hours_id;

	SET @query = 'SELECT START_DATE, END_DATE, MONDAY_MORNING_WORK_FROM, MONDAY_MORNING_WORK_TO, MONDAY_AFTERNOON_WORK_FROM, MONDAY_AFTERNOON_WORK_TO, TUESDAY_MORNING_WORK_FROM, TUESDAY_MORNING_WORK_TO, TUESDAY_AFTERNOON_WORK_FROM, TUESDAY_AFTERNOON_WORK_TO, WEDNESDAY_MORNING_WORK_FROM, WEDNESDAY_MORNING_WORK_TO, WEDNESDAY_AFTERNOON_WORK_FROM, WEDNESDAY_AFTERNOON_WORK_TO, THURSDAY_MORNING_WORK_FROM, THURSDAY_MORNING_WORK_TO, THURSDAY_AFTERNOON_WORK_FROM, THURSDAY_AFTERNOON_WORK_TO, FRIDAY_MORNING_WORK_FROM, FRIDAY_MORNING_WORK_TO, FRIDAY_AFTERNOON_WORK_FROM, FRIDAY_AFTERNOON_WORK_TO, SATURDAY_MORNING_WORK_FROM, SATURDAY_MORNING_WORK_TO, SATURDAY_AFTERNOON_WORK_FROM, SATURDAY_AFTERNOON_WORK_TO, SUNDAY_MORNING_WORK_FROM, SUNDAY_MORNING_WORK_TO, SUNDAY_AFTERNOON_WORK_FROM, SUNDAY_AFTERNOON_WORK_TO, RECORD_LOG FROM employee_working_hours_schedule WHERE WORKING_HOURS_ID = @working_hours_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_working_hours_schedule(IN working_hours_id VARCHAR(50))
BEGIN
	SET @working_hours_id = working_hours_id;

	SET @query = 'DELETE FROM employee_working_hours_schedule WHERE WORKING_HOURS_ID = @working_hours_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_employee_working_hours(IN working_hours_id VARCHAR(50))
BEGIN
	SET @working_hours_id = working_hours_id;

	SET @query = 'UPDATE employee_details SET WORKING_HOURS = null WHERE WORKING_HOURS = @working_hours_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_employee_working_hours(IN employee_id VARCHAR(100), IN working_hours_id VARCHAR(50))
BEGIN
	SET @employee_id = employee_id;
	SET @working_hours_id = working_hours_id;

	SET @query = 'UPDATE employee_details SET WORKING_HOURS = @working_hours_id WHERE EMPLOYEE_ID = @employee_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_employee_working_hours_details(IN working_hours_id VARCHAR(50))
BEGIN
	SET @working_hours_id = working_hours_id;

	SET @query = 'SELECT EMPLOYEE_IMAGE, EMPLOYEE_ID, FILE_AS, JOB_POSITION FROM employee_details WHERE ATTENDANCE_SETTING = @working_hours_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_attendance_setting_exist(IN attendance_setting_id VARCHAR(50))
BEGIN
	SET @attendance_setting_id = attendance_setting_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM attendance_setting WHERE ATTENDANCE_SETTING_ID = @attendance_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_attendance_setting(IN attendance_setting_id VARCHAR(50), IN maximum_attendance INT, IN late_grace_period INT, IN time_out_interval INT, IN late_policy INT, IN early_leaving_policy INT, IN overtime_policy INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @attendance_setting_id = attendance_setting_id;
	SET @maximum_attendance = maximum_attendance;
	SET @late_grace_period = late_grace_period;
	SET @time_out_interval = time_out_interval;
	SET @late_policy = late_policy;
	SET @early_leaving_policy = early_leaving_policy;
	SET @overtime_policy = overtime_policy;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE attendance_setting SET MAX_ATTENDANCE = @maximum_attendance, LATE_GRACE_PERIOD = @late_grace_period, TIME_OUT_INTERVAL = @time_out_interval, LATE_POLICY = @late_policy, EARLY_LEAVING_POLICY = @early_leaving_policy, OVERTIME_POLICY = @overtime_policy, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ATTENDANCE_SETTING_ID = @attendance_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_attendance_setting(IN attendance_setting_id VARCHAR(50), IN maximum_attendance INT, IN late_grace_period INT, IN time_out_interval INT, IN late_policy INT, IN early_leaving_policy INT, IN overtime_policy INT, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @attendance_setting_id = attendance_setting_id;
	SET @maximum_attendance = maximum_attendance;
	SET @late_grace_period = late_grace_period;
	SET @time_out_interval = time_out_interval;
	SET @late_policy = late_policy;
	SET @early_leaving_policy = early_leaving_policy;
	SET @overtime_policy = overtime_policy;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO attendance_setting (ATTENDANCE_SETTING_ID, MAX_ATTENDANCE, LATE_GRACE_PERIOD, TIME_OUT_INTERVAL, LATE_POLICY, EARLY_LEAVING_POLICY, OVERTIME_POLICY, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@attendance_setting_id, @maximum_attendance, @late_grace_period, @time_out_interval, @late_policy, @early_leaving_policy, @overtime_policy, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_attendance_creation_exception(IN employee_id VARCHAR(100), IN exception_type VARCHAR(5), IN record_log VARCHAR(100))
BEGIN
	SET @employee_id = employee_id;
	SET @exception_type = exception_type;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO attendance_creation_exception (EMPLOYEE_ID, EXCEPTION_TYPE, RECORD_LOG) VALUES(@employee_id, @exception_type, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_attendance_adjustment_exception(IN employee_id VARCHAR(100), IN exception_type VARCHAR(5), IN record_log VARCHAR(100))
BEGIN
	SET @employee_id = employee_id;
	SET @exception_type = exception_type;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO attendance_adjustment_exception (EMPLOYEE_ID, EXCEPTION_TYPE, RECORD_LOG) VALUES(@employee_id, @exception_type, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_attendance_setting_details(IN attendance_setting_id VARCHAR(50))
BEGIN
	SET @attendance_setting_id = attendance_setting_id;

	SET @query = 'SELECT MAX_ATTENDANCE, LATE_GRACE_PERIOD, TIME_OUT_INTERVAL, LATE_POLICY, EARLY_LEAVING_POLICY, OVERTIME_POLICY, TRANSACTION_LOG_ID, RECORD_LOG FROM attendance_setting WHERE ATTENDANCE_SETTING_ID = @attendance_setting_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_attendance_creation_exception_details(IN exception_type VARCHAR(5))
BEGIN
	SET @exception_type = exception_type;

	SET @query = 'SELECT EMPLOYEE_ID, RECORD_LOG FROM attendance_creation_exception WHERE EXCEPTION_TYPE = @exception_type';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_attendance_adjustment_exception_details(IN exception_type VARCHAR(5))
BEGIN
	SET @exception_type = exception_type;

	SET @query = 'SELECT EMPLOYEE_ID, RECORD_LOG FROM attendance_adjustment_exception WHERE EXCEPTION_TYPE = @exception_type';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_attendance_creation_exception()
BEGIN
	SET @query = 'DELETE FROM attendance_creation_exception';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_attendance_adjustment_exception()
BEGIN
	SET @query = 'DELETE FROM attendance_adjustment_exception';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_employee_related_user(IN user_code VARCHAR(50))
BEGIN
	SET @user_code = user_code;

	SET @query = 'UPDATE employee_details SET USERNAME = null WHERE USERNAME = @user_code';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_employee_related_user(IN employee_id VARCHAR(100), IN user_code VARCHAR(50), IN record_log VARCHAR(100))
BEGIN
	SET @employee_id = employee_id;
	SET @user_code = user_code;
	SET @record_log = record_log;

	SET @query = 'UPDATE employee_details SET USERNAME = @user_code, RECORD_LOG = @record_log WHERE EMPLOYEE_ID = @employee_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_recent_employee_attendance_details(IN employee_id VARCHAR(100), IN TIME_IN DATE)
BEGIN
	SET @employee_id = employee_id;
	SET @TIME_IN = TIME_IN;

	SET @query = 'SELECT ATTENDANCE_ID, TIME_IN, TIME_IN_LOCATION, TIME_IN_IP_ADDRESS, TIME_IN_BY, TIME_IN_BEHAVIOR, TIME_IN_NOTE, TIME_OUT, TIME_OUT_LOCATION, TIME_OUT_IP_ADDRESS, TIME_OUT_BY, TIME_OUT_BEHAVIOR, TIME_OUT_NOTE, LATE, EARLY_LEAVING, OVERTIME, TOTAL_WORKING_HOURS, REMARKS, TRANSACTION_LOG_ID, RECORD_LOG FROM attendance_record WHERE EMPLOYEE_ID = @employee_id AND DATE(TIME_IN) = @TIME_IN ORDER BY TIME_IN DESC LIMIT 1';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_attendance_exist(IN attendance_id VARCHAR(100))
BEGIN
	SET @attendance_id = attendance_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM attendance_record WHERE ATTENDANCE_ID = @attendance_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_attendance_details(IN attendance_id VARCHAR(100))
BEGIN
	SET @attendance_id = attendance_id;

	SET @query = 'SELECT EMPLOYEE_ID, TIME_IN, TIME_IN_LOCATION, TIME_IN_IP_ADDRESS, TIME_IN_BY, TIME_IN_BEHAVIOR, TIME_IN_NOTE, TIME_OUT, TIME_OUT_LOCATION, TIME_OUT_IP_ADDRESS, TIME_OUT_BY, TIME_OUT_BEHAVIOR, TIME_OUT_NOTE, LATE, EARLY_LEAVING, OVERTIME, TOTAL_WORKING_HOURS, REMARKS, TRANSACTION_LOG_ID, RECORD_LOG FROM attendance_record WHERE ATTENDANCE_ID = @attendance_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_attendance_total_by_date(IN employee_id VARCHAR(100), IN TIME_IN DATE)
BEGIN
	SET @employee_id = employee_id;
	SET @TIME_IN = TIME_IN;

	SET @query = 'SELECT COUNT(ATTENDANCE_ID) AS TOTAL FROM attendance_record WHERE EMPLOYEE_ID = @employee_id AND DATE(TIME_IN) = @TIME_IN AND TIME_OUT IS NOT NULL';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_notification_count(IN notification_to VARCHAR(100), IN status INT)
BEGIN
	SET @notification_to = notification_to;
	SET @status = status;

	SET @query = 'SELECT COUNT(NOTIFICATION_ID) AS TOTAL FROM global_notification WHERE NOTIFICATION_TO = @notification_to AND STATUS = @status';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_notification_channel(IN notification_setting_id INT, IN channel VARCHAR(20))
BEGIN
	SET @notification_setting_id = notification_setting_id;
	SET @channel = channel;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM global_notification_channel WHERE NOTIFICATION_SETTING_ID = @notification_setting_id AND CHANNEL = @channel';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_system_notification(IN notification_id INT, IN notification_from VARCHAR(100), IN notification_to VARCHAR(100), IN notification_title VARCHAR(200), IN notification VARCHAR(1000), IN link VARCHAR(500), IN notification_date DATETIME, IN record_log VARCHAR(100))
BEGIN
	SET @notification_id = notification_id;
	SET @notification_from = notification_from;
	SET @notification_to = notification_to;
	SET @notification_title = notification_title;
	SET @notification = notification;
	SET @link = link;
	SET @notification_date = notification_date;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO global_notification (NOTIFICATION_ID, NOTIFICATION_FROM, NOTIFICATION_TO, STATUS, NOTIFICATION_TITLE, NOTIFICATION, LINK, NOTIFICATION_DATE, RECORD_LOG) VALUES(@notification_id, @notification_from, @notification_to, "0", @notification_title, @notification, @link, @notification_date, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_time_in(IN attendance_id VARCHAR(100), IN employee_id VARCHAR(100), IN time_in DATETIME, IN time_in_location VARCHAR(100), IN time_in_ip_address VARCHAR(20), IN time_in_by VARCHAR(100), IN time_in_behavior VARCHAR(20), IN time_in_note VARCHAR(200), IN late DOUBLE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @attendance_id = attendance_id;
	SET @employee_id = employee_id;
	SET @time_in = time_in;
	SET @time_in_location = time_in_location;
	SET @time_in_ip_address = time_in_ip_address;
	SET @time_in_by = time_in_by;
	SET @time_in_behavior = time_in_behavior;
	SET @time_in_note = time_in_note;
	SET @late = late;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO attendance_record (ATTENDANCE_ID, EMPLOYEE_ID, TIME_IN, TIME_IN_LOCATION, TIME_IN_IP_ADDRESS, TIME_IN_BY, TIME_IN_BEHAVIOR, TIME_IN_NOTE, LATE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@attendance_id, @employee_id, @time_in, @time_in_location, @time_in_ip_address, @time_in_by, @time_in_behavior, @time_in_note, @late, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_time_out(IN attendance_id VARCHAR(100), IN time_out DATETIME, IN time_out_location VARCHAR(100), IN time_out_ip_address VARCHAR(20), IN time_out_by VARCHAR(100), IN time_out_behavior VARCHAR(20), IN time_out_note VARCHAR(200), IN early_leaving DOUBLE, IN overtime DOUBLE, IN total_hours DOUBLE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @attendance_id = attendance_id;
	SET @time_out = time_out;
	SET @time_out_location = time_out_location;
	SET @time_out_ip_address = time_out_ip_address;
	SET @time_out_by = time_out_by;
	SET @time_out_behavior = time_out_behavior;
	SET @time_out_note = time_out_note;
	SET @early_leaving = early_leaving;
	SET @overtime = overtime;
	SET @total_hours = total_hours;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE attendance_record SET TIME_OUT = @time_out, TIME_OUT_LOCATION = @time_out_location, TIME_OUT_IP_ADDRESS = @time_out_ip_address, TIME_OUT_BY = @time_out_by, TIME_OUT_BEHAVIOR = @time_out_behavior, TIME_OUT_NOTE = @time_out_note, EARLY_LEAVING = @early_leaving, OVERTIME = @overtime, TOTAL_WORKING_HOURS = @total_hours, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ATTENDANCE_ID = @attendance_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_notification_status(IN employee_id VARCHAR(100), IN notification_id INT, IN status INT)
BEGIN
	SET @employee_id = employee_id;
	SET @status = status;
	SET @notification_id = notification_id;

	IF @status = 2 THEN
		SET @query = 'UPDATE global_notification SET STATUS = @status WHERE NOTIFICATION_TO = @employee_id AND STATUS = 0';
	ELSE
		SET @query = 'UPDATE global_notification SET STATUS = @status WHERE NOTIFICATION_TO = @employee_id AND NOTIFICATION_ID = @notification_id';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_attedance(IN attendance_id VARCHAR(100), IN employee_id VARCHAR(100), IN time_in DATETIME, IN time_in_ip_address VARCHAR(20), IN time_in_by VARCHAR(100), IN time_in_behavior VARCHAR(20), IN time_out DATETIME, IN time_out_ip_address VARCHAR(20), IN time_out_by VARCHAR(100), IN time_out_behavior VARCHAR(20), IN late DOUBLE, IN early_leaving DOUBLE, IN overtime DOUBLE, IN total_hours DOUBLE, IN remarks VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @attendance_id = attendance_id;
	SET @employee_id = employee_id;
	SET @time_in = time_in;
	SET @time_in_ip_address = time_in_ip_address;
	SET @time_in_by = time_in_by;
	SET @time_in_behavior = time_in_behavior;
	SET @time_out = time_out;
	SET @time_out_ip_address = time_out_ip_address;
	SET @time_out_by = time_out_by;
	SET @time_out_behavior = time_out_behavior;
	SET @late = late;
	SET @early_leaving = early_leaving;
	SET @overtime = overtime;
	SET @total_hours = total_hours;
	SET @remarks = remarks;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO attendance_record (ATTENDANCE_ID, EMPLOYEE_ID, TIME_IN, TIME_IN_IP_ADDRESS, TIME_IN_BY, TIME_IN_BEHAVIOR, TIME_OUT, TIME_OUT_IP_ADDRESS, TIME_OUT_BY, TIME_OUT_BEHAVIOR, LATE, EARLY_LEAVING, OVERTIME, TOTAL_WORKING_HOURS, REMARKS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@attendance_id, @employee_id, @time_in, @time_in_ip_address, @time_in_by, @time_in_behavior, @time_out, @time_out_ip_address, @time_out_by, @time_out_behavior, @late, @early_leaving, @overtime, @total_hours, @remarks, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_attendance(IN attendance_id VARCHAR(100), IN time_in DATETIME, IN time_in_ip_address VARCHAR(20), IN time_in_by VARCHAR(100), IN time_in_behavior VARCHAR(20), IN time_out DATETIME, IN time_out_ip_address VARCHAR(20), IN time_out_by VARCHAR(100), IN time_out_behavior VARCHAR(20), IN late DOUBLE, IN early_leaving DOUBLE, IN overtime DOUBLE, IN total_hours DOUBLE, IN remarks VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @attendance_id = attendance_id;
	SET @time_in = time_in;
	SET @time_in_ip_address = time_in_ip_address;
	SET @time_in_by = time_in_by;
	SET @time_in_behavior = time_in_behavior;
	SET @time_out = time_out;
	SET @time_out_ip_address = time_out_ip_address;
	SET @time_out_by = time_out_by;
	SET @time_out_behavior = time_out_behavior;
	SET @late = late;
	SET @early_leaving = early_leaving;
	SET @overtime = overtime;
	SET @total_hours = total_hours;
	SET @remarks = remarks;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE attendance_record SET TIME_IN = @time_in, TIME_IN_IP_ADDRESS = @time_in_ip_address, TIME_IN_BY = @time_in_by, TIME_IN_BEHAVIOR = @time_in_behavior, TIME_OUT = @time_out, TIME_OUT_IP_ADDRESS = @time_out_ip_address, TIME_OUT_BY = @time_out_by, TIME_OUT_BEHAVIOR = @time_out_behavior, LATE = @late, EARLY_LEAVING = @early_leaving, OVERTIME = @overtime, TOTAL_WORKING_HOURS = @total_hours, REMARKS = @remarks, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ATTENDANCE_ID = @attendance_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_attendance(IN attendance_id VARCHAR(100))
BEGIN
	SET @attendance_id = attendance_id;

	SET @query = 'DELETE FROM attendance_record WHERE ATTENDANCE_ID = @attendance_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_attendance_adjustment_exist(IN adjustment_id VARCHAR(100))
BEGIN
	SET @adjustment_id = adjustment_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM attendance_adjustment WHERE ADJUSTMENT_ID = @adjustment_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_attendance_adjustment(IN adjustment_id VARCHAR(100), IN time_in DATETIME, IN time_out DATETIME, IN reason VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @adjustment_id = adjustment_id;
	SET @time_in = time_in;
	SET @time_out = time_out;
	SET @reason = reason;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE attendance_adjustment SET TIME_IN = @time_in, TIME_OUT = @time_out, REASON = @reason, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ADJUSTMENT_ID = @adjustment_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_attendance_adjustment(IN adjustment_id VARCHAR(100), IN attendance_id VARCHAR(100), IN employee_id VARCHAR(100), IN time_in DATETIME, IN time_out DATETIME, IN reason VARCHAR(500), IN created_date DATETIME, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @adjustment_id = adjustment_id;
	SET @attendance_id = attendance_id;
	SET @employee_id = employee_id;
	SET @time_in = time_in;
	SET @time_out = time_out;
	SET @reason = reason;
	SET @created_date = created_date;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO attendance_adjustment (ADJUSTMENT_ID, ATTENDANCE_ID, EMPLOYEE_ID, TIME_IN, TIME_OUT, REASON, CREATED_DATE, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@adjustment_id, @attendance_id, @employee_id, @time_in, @time_out, @reason, @created_date, "PEN", @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_attendance_adjustment_details(IN adjustment_id VARCHAR(100))
BEGIN
	SET @adjustment_id = adjustment_id;

	SET @query = 'SELECT ATTENDANCE_ID, EMPLOYEE_ID, TIME_IN, TIME_OUT, REASON, ATTACHMENT, STATUS, SANCTION, CREATED_DATE, FOR_RECOMMENDATION_DATE, RECOMMENDATION_DATE, RECOMMENDATION_BY, RECOMMENDATION_REMARKS, DECISION_DATE, DECISION_BY, DECISION_REMARKS, TRANSACTION_LOG_ID, RECORD_LOG FROM attendance_adjustment WHERE ADJUSTMENT_ID = @adjustment_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_attendance_adjustment(IN adjustment_id VARCHAR(100))
BEGIN
	SET @adjustment_id = adjustment_id;

	SET @query = 'DELETE FROM attendance_adjustment WHERE ADJUSTMENT_ID = @adjustment_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_attendance_adjustment_attachment(IN adjustment_id VARCHAR(100), IN attachment VARCHAR(500), IN record_log VARCHAR(100))
BEGIN
	SET @adjustment_id = adjustment_id;
	SET @attachment = attachment;
	SET @record_log = record_log;

	SET @query = 'UPDATE attendance_adjustment SET ATTACHMENT = @attachment, RECORD_LOG = @record_log WHERE ADJUSTMENT_ID = @adjustment_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_attendance_creation_exist(IN creation_id VARCHAR(100))
BEGIN
	SET @creation_id = creation_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM attendance_creation WHERE CREATION_ID = @creation_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_attendance_creation(IN creation_id VARCHAR(100), IN time_in DATETIME, IN time_out DATETIME, IN reason VARCHAR(500), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @creation_id = creation_id;
	SET @time_in = time_in;
	SET @time_out = time_out;
	SET @reason = reason;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE attendance_creation SET TIME_IN = @time_in, TIME_OUT = @time_out, REASON = @reason, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE CREATION_ID = @creation_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_attendance_creation(IN creation_id VARCHAR(100), IN employee_id VARCHAR(100), IN time_in DATETIME, IN time_out DATETIME, IN reason VARCHAR(500), IN created_date DATETIME, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @creation_id = creation_id;
	SET @employee_id = employee_id;
	SET @time_in = time_in;
	SET @time_out = time_out;
	SET @reason = reason;
	SET @created_date = created_date;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO attendance_creation (CREATION_ID, EMPLOYEE_ID, TIME_IN, TIME_OUT, REASON, CREATED_DATE, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@creation_id, @employee_id, @time_in, @time_out, @reason, @created_date, "PEN", @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_attendance_creation_details(IN creation_id VARCHAR(100))
BEGIN
	SET @creation_id = creation_id;

	SET @query = 'SELECT EMPLOYEE_ID, TIME_IN, TIME_OUT, REASON, ATTACHMENT, STATUS, SANCTION, CREATED_DATE, FOR_RECOMMENDATION_DATE, RECOMMENDATION_DATE, RECOMMENDATION_BY, RECOMMENDATION_REMARKS, DECISION_DATE, DECISION_BY, DECISION_REMARKS, TRANSACTION_LOG_ID, RECORD_LOG FROM attendance_creation WHERE CREATION_ID = @creation_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_attendance_creation(IN creation_id VARCHAR(100))
BEGIN
	SET @creation_id = creation_id;

	SET @query = 'DELETE FROM attendance_creation WHERE CREATION_ID = @creation_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_attendance_creation_attachment(IN creation_id VARCHAR(100), IN attachment VARCHAR(500), IN record_log VARCHAR(100))
BEGIN
	SET @creation_id = creation_id;
	SET @attachment = attachment;
	SET @record_log = record_log;

	SET @query = 'UPDATE attendance_creation SET ATTACHMENT = @attachment, RECORD_LOG = @record_log WHERE CREATION_ID = @creation_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_employee_attendance_options(IN employee_id VARCHAR(100))
BEGIN
	SET @employee_id = employee_id;

	SET @query = 'SELECT ATTENDANCE_ID, TIME_IN, TIME_OUT FROM attendance_record WHERE EMPLOYEE_ID = @employee_id ORDER BY TIME_IN DESC';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_attendance_adjustment_status(IN adjustment_id VARCHAR(100), IN status VARCHAR(10), IN sanction INT(1), IN decision_remarks VARCHAR(500), IN decision_date DATETIME, IN decision_by VARCHAR(50), IN transaction_log_id VARCHAR(500), IN record_log VARCHAR(100))
BEGIN
	SET @adjustment_id = adjustment_id;
	SET @status = status;
	SET @sanction = sanction;
	SET @decision_remarks = decision_remarks;
	SET @decision_date = decision_date;
	SET @decision_by = decision_by;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	IF @status = 'APV' OR @status = 'REJ' OR @status = 'CAN' THEN
		SET @query = 'UPDATE attendance_adjustment SET STATUS = @status, SANCTION = @sanction, DECISION_REMARKS = @decision_remarks, DECISION_DATE = @decision_date, DECISION_BY = @decision_by, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ADJUSTMENT_ID = @adjustment_id';
	ELSEIF @status = 'REC' THEN
		SET @query = 'UPDATE attendance_adjustment SET STATUS = @status, RECOMMENDATION_DATE = @decision_date, RECOMMENDATION_BY = @decision_by, RECOMMENDATION_REMARKS = @decision_remarks, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ADJUSTMENT_ID = @adjustment_id';
	ELSEIF @status = 'FORREC' THEN
		SET @query = 'UPDATE attendance_adjustment SET STATUS = @status, FOR_RECOMMENDATION_DATE = @decision_date, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ADJUSTMENT_ID = @adjustment_id';
	ELSE
		SET @query = 'UPDATE attendance_adjustment SET STATUS = @status, FOR_RECOMMENDATION_DATE = null, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE ADJUSTMENT_ID = @adjustment_id';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_attendance_creation_status(IN creation_id VARCHAR(100), IN status VARCHAR(10), IN sanction INT(1), IN decision_remarks VARCHAR(500), IN decision_date DATETIME, IN decision_by VARCHAR(50), IN transaction_log_id VARCHAR(500), IN record_log VARCHAR(100))
BEGIN
	SET @creation_id = creation_id;
	SET @status = status;
	SET @sanction = sanction;
	SET @decision_remarks = decision_remarks;
	SET @decision_date = decision_date;
	SET @decision_by = decision_by;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	IF @status = 'APV' OR @status = 'REJ' OR @status = 'CAN' THEN
		SET @query = 'UPDATE attendance_creation SET STATUS = @status, SANCTION = @sanction, DECISION_REMARKS = @decision_remarks, DECISION_DATE = @decision_date, DECISION_BY = @decision_by, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE CREATION_ID = @creation_id';
	ELSEIF @status = 'REC' THEN
		SET @query = 'UPDATE attendance_creation SET STATUS = @status, RECOMMENDATION_DATE = @decision_date, RECOMMENDATION_BY = @decision_by, RECOMMENDATION_REMARKS = @decision_remarks, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE CREATION_ID = @creation_id';
	ELSEIF @status = 'FORREC' THEN
		SET @query = 'UPDATE attendance_creation SET STATUS = @status, FOR_RECOMMENDATION_DATE = @decision_date, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE CREATION_ID = @creation_id';
	ELSE
		SET @query = 'UPDATE attendance_creation SET STATUS = @status, FOR_RECOMMENDATION_DATE = null, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE CREATION_ID = @creation_id';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_approval_type_exist(IN approval_type_id VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM approval_type WHERE APPROVAL_TYPE_ID = @approval_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_approval_type(IN approval_type_id VARCHAR(100), IN approval_type VARCHAR(100), IN approval_type_description VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;
	SET @approval_type = approval_type;
	SET @approval_type_description = approval_type_description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE approval_type SET APPROVAL_TYPE = @approval_type, APPROVAL_TYPE_DESCRIPTION = @approval_type_description, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE APPROVAL_TYPE_ID = @approval_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_approval_type(IN approval_type_id VARCHAR(100), IN approval_type VARCHAR(100), IN approval_type_description VARCHAR(100), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;
	SET @approval_type = approval_type;
	SET @approval_type_description = approval_type_description;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO approval_type (APPROVAL_TYPE_ID, APPROVAL_TYPE, APPROVAL_TYPE_DESCRIPTION, STATUS, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@approval_type_id, @approval_type, @approval_type_description, "INACTIVE", @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_approval_type_details(IN approval_type_id VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;

	SET @query = 'SELECT APPROVAL_TYPE, APPROVAL_TYPE_DESCRIPTION, STATUS, TRANSACTION_LOG_ID, RECORD_LOG FROM approval_type WHERE APPROVAL_TYPE_ID = @approval_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_approval_type(IN approval_type_id VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;

	SET @query = 'DELETE FROM approval_type WHERE APPROVAL_TYPE_ID = @approval_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_approval_approver(IN approval_type_id VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;

	SET @query = 'DELETE FROM approval_approver WHERE APPROVAL_TYPE_ID = @approval_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_approval_exception(IN approval_type_id VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;

	SET @query = 'DELETE FROM approval_exception WHERE APPROVAL_TYPE_ID = @approval_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_approval_type_status(IN approval_type_id VARCHAR(100), IN status VARCHAR(10), IN record_log VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;
	SET @status = status;
	SET @record_log = record_log;

	SET @query = 'UPDATE approval_type SET STATUS = @status, RECORD_LOG = @record_log WHERE APPROVAL_TYPE_ID = @approval_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_approver_exist(IN approval_type_id VARCHAR(100), IN employee_id VARCHAR(100), IN department VARCHAR(50))
BEGIN
	SET @approval_type_id = approval_type_id;
	SET @employee_id = employee_id;
	SET @department = department;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM approval_approver WHERE APPROVAL_TYPE_ID = @approval_type_id AND EMPLOYEE_ID = @employee_id AND DEPARTMENT = @department';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_approver(IN approval_type_id VARCHAR(100), IN employee_id VARCHAR(100), IN department VARCHAR(50), IN record_log VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;
	SET @employee_id = employee_id;
	SET @department = department;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO approval_approver (APPROVAL_TYPE_ID, EMPLOYEE_ID, DEPARTMENT, RECORD_LOG) VALUES(@approval_type_id, @employee_id, @department, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_approver_details(IN approval_type_id VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;

	SET @query = 'SELECT EMPLOYEE_ID, DEPARTMENT, RECORD_LOG FROM approval_approver WHERE APPROVAL_TYPE_ID = @approval_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_approver(IN approval_type_id VARCHAR(100), IN employee_id VARCHAR(100), IN department VARCHAR(50))
BEGIN
	SET @approval_type_id = approval_type_id;
	SET @employee_id = employee_id;
	SET @department = department;

	SET @query = 'DELETE FROM approval_approver WHERE APPROVAL_TYPE_ID = @approval_type_id AND EMPLOYEE_ID = @employee_id AND DEPARTMENT = @department';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_approval_exception_exist(IN approval_type_id VARCHAR(100), IN employee_id VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;
	SET @employee_id = employee_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM approval_exception WHERE APPROVAL_TYPE_ID = @approval_type_id AND EMPLOYEE_ID = @employee_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_approval_exception(IN approval_type_id VARCHAR(100), IN employee_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;
	SET @employee_id = employee_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO approval_exception (APPROVAL_TYPE_ID, EMPLOYEE_ID, RECORD_LOG) VALUES(@approval_type_id, @employee_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_approval_exception_details(IN approval_type_id VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;

	SET @query = 'SELECT EMPLOYEE_ID, RECORD_LOG FROM approval_exception WHERE APPROVAL_TYPE_ID = @approval_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_approval_exception(IN approval_type_id VARCHAR(100), IN employee_id VARCHAR(100))
BEGIN
	SET @approval_type_id = approval_type_id;
	SET @employee_id = employee_id;

	SET @query = 'DELETE FROM approval_exception WHERE APPROVAL_TYPE_ID = @approval_type_id AND EMPLOYEE_ID = @employee_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_public_holiday(IN public_holiday_id VARCHAR(100), IN public_holiday VARCHAR(100), IN holiday_date DATE, IN holiday_type VARCHAR(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @public_holiday_id = public_holiday_id;
	SET @public_holiday = public_holiday;
	SET @holiday_date = holiday_date;
	SET @holiday_type = holiday_type;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE public_holiday SET PUBLIC_HOLIDAY = @public_holiday, HOLIDAY_DATE = @holiday_date, HOLIDAY_TYPE = @holiday_type, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE PUBLIC_HOLIDAY_ID = @public_holiday_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_public_holiday(IN public_holiday_id VARCHAR(100), IN public_holiday VARCHAR(100), IN holiday_date DATE, IN holiday_type VARCHAR(50), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @public_holiday_id = public_holiday_id;
	SET @public_holiday = public_holiday;
	SET @holiday_date = holiday_date;
	SET @holiday_type = holiday_type;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO public_holiday (PUBLIC_HOLIDAY_ID, PUBLIC_HOLIDAY, HOLIDAY_DATE, HOLIDAY_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@public_holiday_id, @public_holiday, @holiday_date, @holiday_type, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_public_holiday_work_location(IN public_holiday_id VARCHAR(100), IN work_location_id VARCHAR(50), IN record_log VARCHAR(100))
BEGIN
	SET @public_holiday_id = public_holiday_id;
	SET @work_location_id = work_location_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO public_holiday_work_location (PUBLIC_HOLIDAY_ID, WORK_LOCATION_ID, RECORD_LOG) VALUES(@public_holiday_id, @work_location_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_public_holiday_details(IN public_holiday_id VARCHAR(100))
BEGIN
	SET @public_holiday_id = public_holiday_id;

	SET @query = 'SELECT PUBLIC_HOLIDAY, HOLIDAY_DATE, HOLIDAY_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM public_holiday WHERE PUBLIC_HOLIDAY_ID = @public_holiday_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_public_holiday_work_location_details(IN public_holiday_id VARCHAR(100))
BEGIN
	SET @public_holiday_id = public_holiday_id;

	SET @query = 'SELECT WORK_LOCATION_ID, RECORD_LOG FROM public_holiday_work_location WHERE PUBLIC_HOLIDAY_ID = @public_holiday_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_public_holiday_work(IN public_holiday_id VARCHAR(100))
BEGIN
	SET @public_holiday_id = public_holiday_id;

	SET @query = 'DELETE FROM public_holiday WHERE PUBLIC_HOLIDAY_ID = @public_holiday_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_all_public_holiday_work_location(IN public_holiday_id VARCHAR(100))
BEGIN
	SET @public_holiday_id = public_holiday_id;

	SET @query = 'DELETE FROM public_holiday_work_location WHERE PUBLIC_HOLIDAY_ID = @public_holiday_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_leave_type_exist(IN leave_type_id VARCHAR(100))
BEGIN
	SET @leave_type_id = leave_type_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM leave_type WHERE LEAVE_TYPE_ID = @leave_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_leave_type(IN leave_type_id VARCHAR(100), IN leave_type VARCHAR(100), IN paid_type VARCHAR(10), IN allocation_type VARCHAR(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @leave_type_id = leave_type_id;
	SET @leave_type = leave_type;
	SET @paid_type = paid_type;
	SET @allocation_type = allocation_type;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE leave_type SET LEAVE_TYPE = @leave_type, PAID_TYPE = @paid_type, ALLOCATION_TYPE = @allocation_type, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE LEAVE_TYPE_ID = @leave_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_leave_type(IN leave_type_id VARCHAR(100), IN leave_type VARCHAR(100), IN paid_type VARCHAR(10), IN allocation_type VARCHAR(10), IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @leave_type_id = leave_type_id;
	SET @leave_type = leave_type;
	SET @paid_type = paid_type;
	SET @allocation_type = allocation_type;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO leave_type (LEAVE_TYPE_ID, LEAVE_TYPE, PAID_TYPE, ALLOCATION_TYPE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@leave_type_id, @leave_type, @paid_type, @allocation_type, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_leave_type_details(IN leave_type_id VARCHAR(100))
BEGIN
	SET @leave_type_id = leave_type_id;

	SET @query = 'SELECT LEAVE_TYPE, PAID_TYPE, ALLOCATION_TYPE, TRANSACTION_LOG_ID, RECORD_LOG FROM leave_type WHERE LEAVE_TYPE_ID = @leave_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_leave_type(IN leave_type_id VARCHAR(100))
BEGIN
	SET @leave_type_id = leave_type_id;

	SET @query = 'DELETE FROM leave_type WHERE LEAVE_TYPE_ID = @leave_type_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_leave_type_options()
BEGIN
	SET @query = 'SELECT LEAVE_TYPE_ID, LEAVE_TYPE FROM leave_type ORDER BY LEAVE_TYPE';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE generate_leave_type_variation_options(IN variation_type VARCHAR(10))
BEGIN
	SET @variation_type = variation_type;

	IF @variation_type = 'LIMITED' OR @variation_type = 'NOLIMIT' THEN
		SET @query = 'SELECT LEAVE_TYPE_ID, LEAVE_TYPE FROM leave_type WHERE ALLOCATION_TYPE = @variation_type ORDER BY LEAVE_TYPE';
	ELSE
		SET @query = 'SELECT LEAVE_TYPE_ID, LEAVE_TYPE FROM leave_type ORDER BY LEAVE_TYPE';
	END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_leave_allocation_overlap(IN leave_allocation_id VARCHAR(100), IN employee_id VARCHAR(100), IN leave_type VARCHAR(100))
BEGIN
	SET @leave_allocation_id = leave_allocation_id;
	SET @employee_id = employee_id;
	SET @leave_type = leave_type;

	IF @leave_allocation_id IS NULL OR @leave_allocation_id = '' THEN
		SET @query = 'SELECT VALIDITY_START_DATE, VALIDITY_END_DATE FROM leave_allocation WHERE EMPLOYEE_ID = @employee_id AND LEAVE_TYPE_ID = @leave_type';
	ELSE
		SET @query = 'SELECT VALIDITY_START_DATE, VALIDITY_END_DATE FROM leave_allocation WHERE LEAVE_ALLOCATION_ID != @leave_allocation_id AND EMPLOYEE_ID = @employee_id AND LEAVE_TYPE_ID = @leave_type';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_leave_allocation_exist(IN leave_allocation_id VARCHAR(100))
BEGIN
	SET @leave_allocation_id = leave_allocation_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM leave_allocation WHERE LEAVE_ALLOCATION_ID = @leave_allocation_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_leave_allocation(IN leave_allocation_id VARCHAR(100), IN leave_type_id VARCHAR(100), IN employee_id VARCHAR(100), IN validity_start_date DATE, IN validity_end_date DATE, IN duration DOUBLE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @leave_allocation_id = leave_allocation_id;
	SET @leave_type_id = leave_type_id;
	SET @employee_id = employee_id;
	SET @validity_start_date = validity_start_date;
	SET @validity_end_date = validity_end_date;
	SET @duration = duration;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE leave_allocation SET LEAVE_TYPE_ID = @leave_type_id, EMPLOYEE_ID = @employee_id, VALIDITY_START_DATE = @validity_start_date, VALIDITY_END_DATE = @validity_end_date, DURATION = @duration, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE LEAVE_ALLOCATION_ID = @leave_allocation_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_leave_allocation(IN leave_allocation_id VARCHAR(100), IN leave_type_id VARCHAR(100), IN employee_id VARCHAR(100), IN validity_start_date DATE, IN validity_end_date DATE, IN duration DOUBLE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @leave_allocation_id = leave_allocation_id;
	SET @leave_type_id = leave_type_id;
	SET @employee_id = employee_id;
	SET @validity_start_date = validity_start_date;
	SET @validity_end_date = validity_end_date;
	SET @duration = duration;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO leave_allocation (LEAVE_ALLOCATION_ID, LEAVE_TYPE_ID, EMPLOYEE_ID, VALIDITY_START_DATE, VALIDITY_END_DATE, DURATION, AVAILED, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@leave_allocation_id, @leave_type_id, @employee_id, @validity_start_date, @validity_end_date, @duration, @duration, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_leave_allocation_details(IN leave_allocation_id VARCHAR(100))
BEGIN
	SET @leave_allocation_id = leave_allocation_id;

	SET @query = 'SELECT LEAVE_TYPE_ID, EMPLOYEE_ID, VALIDITY_START_DATE, VALIDITY_END_DATE, DURATION, AVAILED, TRANSACTION_LOG_ID, RECORD_LOG FROM leave_allocation WHERE LEAVE_ALLOCATION_ID = @leave_allocation_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_employee_leave_allocation_details(IN employee_id VARCHAR(100), IN leave_type_id VARCHAR(100), IN leave_date DATE)
BEGIN
	SET @employee_id = employee_id;
	SET @leave_type_id = leave_type_id;
	SET @leave_date = leave_date;

	SET @query = 'SELECT LEAVE_ALLOCATION_ID, DURATION, AVAILED, VALIDITY_START_DATE, VALIDITY_END_DATE, TRANSACTION_LOG_ID, RECORD_LOG FROM leave_allocation WHERE EMPLOYEE_ID = @employee_id AND LEAVE_TYPE_ID = @leave_type_id AND (VALIDITY_START_DATE <= @leave_date AND VALIDITY_END_DATE >= @leave_date)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_leave_allocation(IN leave_allocation_id VARCHAR(100))
BEGIN
	SET @leave_allocation_id = leave_allocation_id;

	SET @query = 'DELETE FROM leave_allocation WHERE LEAVE_ALLOCATION_ID = @leave_allocation_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_available_leave_allocation(IN leave_type_id VARCHAR(100), IN employee_id VARCHAR(100), IN leave_date DATE)
BEGIN
	SET @leave_type_id = leave_type_id;
	SET @employee_id = employee_id;
	SET @leave_date = leave_date;

	SET @query = 'SELECT AVAILED FROM leave_allocation WHERE LEAVE_TYPE_ID = @leave_type_id AND EMPLOYEE_ID = @employee_id AND (VALIDITY_START_DATE <= @leave_date AND VALIDITY_END_DATE >= @leave_date)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE check_leave_exist(IN leave_id VARCHAR(100))
BEGIN
	SET @leave_id = leave_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM leave_management WHERE LEAVE_ID = @leave_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_leave(IN leave_id VARCHAR(100), IN leave_type_id VARCHAR(100), IN reason VARCHAR(500), IN leave_date DATE, IN start_time TIME, IN end_time TIME, IN total_hours DOUBLE, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @leave_id = leave_id;
	SET @leave_type_id = leave_type_id;
	SET @reason = reason;
	SET @leave_date = leave_date;
	SET @start_time = start_time;
	SET @end_time = end_time;
	SET @total_hours = total_hours;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'UPDATE leave_management SET LEAVE_TYPE_ID = @leave_type_id, REASON = @reason, LEAVE_DATE = @leave_date, START_TIME = @start_time, END_TIME = @end_time, TOTAL_HOURS = @total_hours, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE LEAVE_ID = @leave_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_leave(IN leave_id VARCHAR(100), IN employee_id VARCHAR(100), IN leave_type_id VARCHAR(100), IN reason VARCHAR(500), IN leave_date DATE, IN start_time TIME, IN end_time TIME, IN total_hours DOUBLE, IN created_date DATETIME, IN transaction_log_id VARCHAR(100), IN record_log VARCHAR(100))
BEGIN
	SET @leave_id = leave_id;
	SET @employee_id = employee_id;
	SET @leave_type_id = leave_type_id;
	SET @reason = reason;
	SET @leave_date = leave_date;
	SET @start_time = start_time;
	SET @end_time = end_time;
	SET @total_hours = total_hours;
	SET @created_date = created_date;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO leave_management (LEAVE_ID, EMPLOYEE_ID, LEAVE_TYPE_ID, REASON, LEAVE_DATE, START_TIME, END_TIME, TOTAL_HOURS, STATUS, CREATED_DATE, TRANSACTION_LOG_ID, RECORD_LOG) VALUES(@leave_id, @employee_id, @leave_type_id, @reason, @leave_date, @start_time, @end_time, @total_hours, "PEN", @created_date, @transaction_log_id, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_leave_details(IN leave_id VARCHAR(100))
BEGIN
	SET @leave_id = leave_id;

	SET @query = 'SELECT EMPLOYEE_ID, LEAVE_TYPE_ID, REASON, LEAVE_DATE, START_TIME, END_TIME, TOTAL_HOURS, STATUS, CREATED_DATE, FOR_APPROVAL_DATE, DECISION_DATE, DECISION_BY, DECISION_REMARKS, TRANSACTION_LOG_ID, RECORD_LOG FROM leave_management WHERE LEAVE_ID = @leave_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_leave(IN leave_id VARCHAR(100))
BEGIN
	SET @leave_id = leave_id;

	SET @query = 'DELETE FROM leave_management WHERE LEAVE_ID = @leave_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE TABLE leave_supporting_document(
	LEAVE_SUPPORTING_DOCUMENT_ID VARCHAR(100) PRIMARY KEY,
	LEAVE_ID VARCHAR(100) NOT NULL,
	DOCUMENT_NAME VARCHAR(100) NOT NULL,
	SUPPORTING_DOCUMENT VARCHAR(500) NOT NULL,
	UPLOADED_BY VARCHAR(50) NOT NULL,
	UPLOAD_DATE DATETIME NOT NULL,
	RECORD_LOG VARCHAR(100)
);

CREATE PROCEDURE check_leave_supporting_document_exist(IN leave_supporting_document_id VARCHAR(100))
BEGIN
	SET @leave_supporting_document_id = leave_supporting_document_id;

	SET @query = 'SELECT COUNT(1) AS TOTAL FROM leave_supporting_document WHERE LEAVE_SUPPORTING_DOCUMENT_ID = @leave_supporting_document_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE insert_leave_supporting_document(IN leave_supporting_document_id VARCHAR(100), IN leave_id VARCHAR(100), IN document_name VARCHAR(100), IN supporting_document VARCHAR(500), IN uploaded_by VARCHAR(50), IN uploaded_date DATETIME, IN record_log VARCHAR(100))
BEGIN
	SET @leave_supporting_document_id = leave_supporting_document_id;
	SET @leave_id = leave_id;
	SET @document_name = document_name;
	SET @supporting_document = supporting_document;
	SET @uploaded_by = uploaded_by;
	SET @uploaded_date = uploaded_date;
	SET @record_log = record_log;

	SET @query = 'INSERT INTO leave_supporting_document (LEAVE_SUPPORTING_DOCUMENT_ID, LEAVE_ID, DOCUMENT_NAME, SUPPORTING_DOCUMENT, UPLOADED_BY, UPLOAD_DATE, RECORD_LOG) VALUES(@leave_supporting_document_id, @leave_id, @document_name, @supporting_document, @uploaded_by, @uploaded_date, @record_log)';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE get_leave_supporting_document_details(IN leave_supporting_document_id VARCHAR(100))
BEGIN
	SET @leave_supporting_document_id = leave_supporting_document_id;

	SET @query = 'SELECT LEAVE_ID, DOCUMENT_NAME, SUPPORTING_DOCUMENT, UPLOADED_BY, UPLOAD_DATE, RECORD_LOG FROM leave_supporting_document WHERE LEAVE_SUPPORTING_DOCUMENT_ID = @leave_supporting_document_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE delete_leave_supporting_document(IN leave_supporting_document_id VARCHAR(100))
BEGIN
	SET @leave_supporting_document_id = leave_supporting_document_id;

	SET @query = 'DELETE FROM leave_supporting_document WHERE LEAVE_SUPPORTING_DOCUMENT_ID = @leave_supporting_document_id';

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_leave_status(IN leave_id VARCHAR(100), IN status VARCHAR(10), IN decision_remarks VARCHAR(500), IN decision_date DATETIME, IN decision_by VARCHAR(50), IN transaction_log_id VARCHAR(500), IN record_log VARCHAR(100))
BEGIN
	SET @leave_id = leave_id;
	SET @status = status;
	SET @decision_remarks = decision_remarks;
	SET @decision_date = decision_date;
	SET @decision_by = decision_by;
	SET @transaction_log_id = transaction_log_id;
	SET @record_log = record_log;

	IF @status = 'APV' OR @status = 'REJ' OR @status = 'CAN' THEN
		SET @query = 'UPDATE leave_management SET STATUS = @status, DECISION_REMARKS = @decision_remarks, DECISION_DATE = @decision_date, DECISION_BY = @decision_by, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE LEAVE_ID = @leave_id';
	ELSEIF @status = 'FA' THEN
		SET @query = 'UPDATE leave_management SET STATUS = @status, FOR_APPROVAL_DATE = @decision_date, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE LEAVE_ID = @leave_id';
	ELSE
		SET @query = 'UPDATE leave_management SET STATUS = @status, FOR_APPROVAL_DATE = null, TRANSACTION_LOG_ID = @transaction_log_id, RECORD_LOG = @record_log WHERE LEAVE_ID = @leave_id';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

CREATE PROCEDURE update_employee_leave_allocation(IN leave_allocation_id VARCHAR(100), IN total_hours DOUBLE, IN transaction_type VARCHAR(5), IN record_log VARCHAR(100))
BEGIN
	SET @leave_allocation_id = leave_allocation_id;
	SET @total_hours = total_hours;
	SET @transaction_type = transaction_type;
	SET @record_log = record_log;

	IF @transaction_type = 'REJ' OR @transaction_type = 'CAN' OR @transaction_type = 'PEN' THEN
		SET @query = 'UPDATE leave_allocation SET AVAILED = (AVAILED - @total_hours), RECORD_LOG = @record_log WHERE LEAVE_ALLOCATION_ID = @leave_allocation_id';
	ELSE
		SET @query = 'UPDATE leave_allocation SET AVAILED = (AVAILED + @total_hours), RECORD_LOG = @record_log WHERE LEAVE_ALLOCATION_ID = @leave_allocation_id';
    END IF;

	PREPARE stmt FROM @query;
	EXECUTE stmt;
	DROP PREPARE stmt;
END //

/* Insert Transactions */
INSERT INTO global_user_account (USERNAME, PASSWORD, USER_STATUS, PASSWORD_EXPIRY_DATE, FAILED_LOGIN, LAST_FAILED_LOGIN, TRANSACTION_LOG_ID) VALUES ('ADMIN', '68aff5412f35ed76', 'Active', '2021-12-30', 0, null, 'TL-1');
INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID) VALUES ('1', 'System Parameter', 'Parameter for system parameters.', '', 3, 'TL-2');
INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID) VALUES ('2', 'Transaction Log', 'Parameter for transaction logs.', 'TL-', 4, 'TL-3');
INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID) VALUES ('3', 'Policy', 'Parameter for policies.', '', 0, 'TL-4');
INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID) VALUES ('4', 'Permissions', 'Parameter for permissions.', '', 0, 'TL-5');
INSERT INTO global_system_parameters (PARAMETER_ID, PARAMETER, PARAMETER_DESCRIPTION, PARAMETER_EXTENSION, PARAMETER_NUMBER, TRANSACTION_LOG_ID) VALUES ('5', 'Role', 'Parameter for role.', 'RL-', 0, 'TL-5');
INSERT INTO global_system_code (SYSTEM_TYPE, SYSTEM_CODE, SYSTEM_DESCRIPTION, TRANSACTION_LOG_ID) VALUES ('SYSTYPE', 'SYSTYPE', 'SYSTEM CODE', '');

INSERT INTO global_transaction_log (TRANSACTION_LOG_ID, USERNAME, LOG_TYPE, LOG_DATE, LOG) VALUES ('TL-1', 'ADMIN', 'Insert', '2021-08-01 12:00:00', 'User ADMIN inserted user account.');
INSERT INTO global_transaction_log (TRANSACTION_LOG_ID, USERNAME, LOG_TYPE, LOG_DATE, LOG) VALUES ('TL-2', 'ADMIN', 'Insert', '2021-08-01 12:00:00', 'User ADMIN inserted system parameter.');
INSERT INTO global_transaction_log (TRANSACTION_LOG_ID, USERNAME, LOG_TYPE, LOG_DATE, LOG) VALUES ('TL-3', 'ADMIN', 'Insert', '2021-08-01 12:00:00', 'User ADMIN inserted system parameter.');
INSERT INTO global_transaction_log (TRANSACTION_LOG_ID, USERNAME, LOG_TYPE, LOG_DATE, LOG) VALUES ('TL-4', 'ADMIN', 'Insert', '2021-08-01 12:00:00', 'User ADMIN inserted system parameter.');
INSERT INTO global_transaction_log (TRANSACTION_LOG_ID, USERNAME, LOG_TYPE, LOG_DATE, LOG) VALUES ('TL-5', 'ADMIN', 'Insert', '2021-08-01 12:00:00', 'User ADMIN inserted system parameter.');
INSERT INTO global_transaction_log (TRANSACTION_LOG_ID, USERNAME, LOG_TYPE, LOG_DATE, LOG) VALUES ('TL-6', 'ADMIN', 'Insert', '2021-08-01 12:00:00', 'User ADMIN inserted system parameter.');