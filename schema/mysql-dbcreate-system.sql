DROP TABLE IF EXISTS string_translations,user_code, user_profile, user;

CREATE TABLE IF NOT EXISTS `string_translations` (
  `language` varchar(50) NOT NULL,
  `context` varchar(50) NOT NULL,
  `name` varchar(160) NOT NULL,
  `value` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY `context_name_language` (`context`,`name`,`language`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


create table IF NOT EXISTS  user (
    username         varchar(255) not null,
    password         varchar(128)  not null,
    salt             varchar(100) not null,    
    old_password         varchar(128) ,
    old_salt             varchar(100) , 
    role             varchar(20) ,
    created         datetime     ,
    last_login      datetime,
    status VARCHAR(10) default 'active',
    `config` text,
    primary key (username)    
) ENGINE = InnoDB;


INSERT INTO user(username , password , salt ) VALUES('admin','6e5b5188b9a84d7d02cd94eabae281a0d2089d43','730b64da46ed81bf1a');

CREATE TABLE IF NOT EXISTS  user_profile(
    username varchar(255) NOT NULL,
    first_name varchar(50),
    last_name varchar(50),
    organization varchar(100),
    designation varchar(50),
    email varchar(50),
    address varchar(200),
    PRIMARY KEY(username),
    FOREIGN KEY (username)  REFERENCES user(username)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS user_code(
    username varchar(255) NOT NULL,
    action varchar(60),
    code varchar(60),
    expiry varchar(60),
    PRIMARY KEY ( username , action ),
    FOREIGN KEY (username) REFERENCES user(username)

)ENGINE = InnoDB;

  
DROP TABLE IF EXISTS sessions2;
  
CREATE TABLE IF NOT EXISTS sessions2(
      sesskey VARCHAR( 64 ) NOT NULL DEFAULT '',
      expiry DATETIME NOT NULL ,
      expireref VARCHAR( 250 ) DEFAULT '',
      created DATETIME NOT NULL ,
      modified DATETIME NOT NULL ,
      sessdata LONGTEXT DEFAULT '',
      PRIMARY KEY ( sesskey ) ,
      INDEX sess2_expiry( expiry ),
      INDEX sess2_expireref( expireref )
);


DROP TABLE IF EXISTS config;
-- no reporting
CREATE TABLE config(
    config_id BIGINT NOT NULL AUTO_INCREMENT,
	confkey VARCHAR(50) NOT NULL, 
	value text,
    PRIMARY KEY(config_id)
);
