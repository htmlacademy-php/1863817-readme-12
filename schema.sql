CREATE DATABASE IF NOT EXISTS readme;
DEFAULT CHARACTER SET utf8;
DEFAULT COLLATE utf8_general_ci;
USE readme;

CREATE TABLE IF NOT EXISTS users (
  id_user INT AUTO_INCREMENT UNIQUE UNSIGNED,
  email VARCHAR(128) NOT NULL UNIQUE,
  password VARCHAR(64) NOT NULL,
  registration_date DATETIME(64) NOT NULL,
  avatar_link varchar(256),
  PRIMARY KEY (`id_user`)
);

CREATE TABLE IF NOT EXISTS posts (
  id_post INT AUTO_INCREMENT UNIQUE UNSIGNED,
  post_date DATETIME(64) NOT NULL,
  title varchar(256),
  text_content TEXT,
  quote_author varchar(256) default '',
  image_link varchar(256) default '',
  video_link varchar(256) default '',
  website_link varchar(256) default '',
  number_of_views INT,
  id_user INT,
  content_type CHAR(1) NOT NULL,
  PRIMARY KEY (`id_post`)
);

CREATE INDEX i_for_user_post ON posts(id_user);

CREATE TABLE IF NOT EXISTS comments (
  id_comment INT AUTO_INCREMENT UNIQUE UNSIGNED,
  comment_date DATETIME(64) NOT NULL,
  comment_text TEXT NOT NULL default '',
  id_user INT UNSIGNED,
  id_post INT UNSIGNED,
  PRIMARY KEY (`id_comment`)
);

CREATE INDEX i_for_user_comment ON comments(id_user);
CREATE INDEX i_for_post_comment ON comments(id_post);

CREATE TABLE IF NOT EXISTS likes (
  id_like INT AUTO_INCREMENT UNIQUE UNSIGNED,
  id_user INT NOT NULL UNSIGNED,
  id_post INT NOT NULL UNSIGNED,
  PRIMARY KEY (`id_like`)
);

CREATE INDEX i_for_user_like ON comments(id_user);
CREATE INDEX i_for_post_like ON comments(id_post);

CREATE TABLE IF NOT EXISTS subscriptions (
  id_subscription INT AUTO_INCREMENT UNIQUE UNSIGNED,
  id_subscriber INT NOT NULL UNSIGNED,
  id_receiver_sub INT NOT NULL UNSIGNED,
  PRIMARY KEY (`id_subscription`)
);

CREATE INDEX subscriber_index ON subscriptions(id_subscriber);
CREATE INDEX receiver_sub_index ON subscriptions(id_receiver_sub);

CREATE TABLE IF NOT EXISTS messages (
  id_message INT AUTO_INCREMENT UNIQUE UNSIGNED,
  message_date DATETIME(64) NOT NULL,
  message_text TEXT NOT NULL default '',
  id_who_writed INT NOT NULL UNSIGNED,
  id_for_who_writed INT NOT NULL UNSIGNED,
  PRIMARY KEY (`id_message`)
);

CREATE INDEX sender_index ON messages(id_who_writed);
CREATE INDEX receiver_index ON messages(id_for_who_writed);

CREATE TABLE IF NOT EXISTS hashtags (
  id_hashtag INT AUTO_INCREMENT UNIQUE UNSIGNED,
  hashtag_title varchar(256) NOT NULL,
  PRIMARY KEY (`id_hashtag`)
);

CREATE TABLE IF NOT EXISTS contentTypes (
  id_type TINYINT AUTO_INCREMENT UNIQUE UNSIGNED,
  content_type_title CHAR(1) NOT NULL,
  content_class_type VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id_type`)
);
