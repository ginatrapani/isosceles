ALTER DATABASE DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

--
-- Table structure for table tu_owners
--

CREATE TABLE iso_owners (
  id int(20) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  full_name varchar(200) NOT NULL COMMENT 'User full name.',
  pwd varchar(255) DEFAULT NULL COMMENT 'Hash of the owner password',
  pwd_salt varchar(255) NOT NULL COMMENT 'Salt for securely hashing the owner password',
  email varchar(200) NOT NULL COMMENT 'User email.',
  activation_code int(10) NOT NULL DEFAULT '0' COMMENT 'User activation code.',
  joined date NOT NULL DEFAULT '0000-00-00' COMMENT 'Date user registered for an account.',
  is_activated int(1) NOT NULL DEFAULT '0' COMMENT 'If user is activated, 1 for true, 0 for false.',
  is_admin int(1) NOT NULL DEFAULT '0' COMMENT 'If user is an admin, 1 for true, 0 for false.',
  last_login date NOT NULL DEFAULT '0000-00-00' COMMENT 'Last time user logged into ThinkUp.',
  password_token varchar(64) DEFAULT NULL COMMENT 'Password reset token.',
  failed_logins int(11) NOT NULL DEFAULT '0' COMMENT 'Current number of failed login attempts.',
  account_status varchar(150) NOT NULL DEFAULT '' COMMENT 'Description of account status, i.e., "Inactive due to excessive failed login attempts".',
  api_key varchar(32) NOT NULL COMMENT 'Key to authorize API calls.',
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='ThinkUp user account details.';

--
-- Table structure for table tu_users
--

CREATE TABLE iso_users (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  user_id varchar(30) NOT NULL COMMENT 'User ID on a given network.',
  user_name varchar(255) NOT NULL COMMENT 'Username on a given network, like a user''s Twitter username or Facebook user name.',
  full_name varchar(255) NOT NULL COMMENT 'Full name on a given network.',
  avatar varchar(255) NOT NULL COMMENT 'URL to user''s avatar on a given network.',
  location varchar(255) DEFAULT NULL COMMENT 'Service user location.',
  description text COMMENT 'Service user description, like a Twitter user''s profile description.',
  url varchar(255) DEFAULT NULL COMMENT 'Service user''s URL.',
  is_protected tinyint(1) NOT NULL COMMENT 'Whether or not the user is public.',
  follower_count int(11) NOT NULL COMMENT 'Total number of followers a service user has.',
  friend_count int(11) NOT NULL DEFAULT '0' COMMENT 'Total number of friends a service user has.',
  post_count int(11) NOT NULL DEFAULT '0' COMMENT 'Total number of posts the user has authored.',
  last_updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Last time this user''s record was updated.',
  found_in varchar(100) DEFAULT NULL COMMENT 'What data source or API call the last update originated from (for developer debugging).',
  last_post timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The time of the latest post the user authored.',
  joined timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'When the user joined the network.',
  last_post_id varchar(80) NOT NULL COMMENT 'Network post ID of the latest post the user authored.',
  network varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'Originating network in lower case, i.e., twitter or facebook.',
  favorites_count int(11) DEFAULT NULL COMMENT 'Total number of posts the user has favorited.',
  PRIMARY KEY (id),
  UNIQUE KEY user_id (user_id,network)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Service user details.';
