ALTER DATABASE DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

--
-- Table structure for iso_users
--

CREATE TABLE iso_users (
  id int(20) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  full_name varchar(200) NOT NULL COMMENT 'User full name.',
  pwd varchar(255) DEFAULT NULL COMMENT 'Hash of the owner password',
  pwd_salt varchar(255) NOT NULL COMMENT 'Salt for securely hashing the owner password',
  email varchar(200) NOT NULL COMMENT 'User email.',
  activation_code int(10) NOT NULL DEFAULT '0' COMMENT 'User activation code.',
  joined date NOT NULL DEFAULT '0000-00-00' COMMENT 'Date user registered for an account.',
  is_activated int(1) NOT NULL DEFAULT '0' COMMENT 'If user is activated, 1 for true, 0 for false.',
  is_admin int(1) NOT NULL DEFAULT '0' COMMENT 'If user is an admin, 1 for true, 0 for false.',
  last_login date NOT NULL DEFAULT '0000-00-00' COMMENT 'Last time user logged in.',
  password_token varchar(64) DEFAULT NULL COMMENT 'Password reset token.',
  failed_logins int(11) NOT NULL DEFAULT '0' COMMENT 'Current number of failed login attempts.',
  account_status varchar(150) NOT NULL DEFAULT '' COMMENT 'Description of account status, i.e., "Inactive due to excessive failed login attempts".',
  api_key varchar(32) NOT NULL COMMENT 'Key to authorize API calls.',
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='User account details.';
