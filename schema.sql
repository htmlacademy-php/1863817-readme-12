CREATE DATABASE IF NOT EXISTS readme;
USE readme;

CREATE TABLE IF NOT EXISTS users (
  id_user INT AUTO_INCREMENT,
  email VARCHAR(128) UNIQUE NOT NULL,
  password VARCHAR(64) NOT NULL,
  registration_date DATETIME(6) NOT NULL,
  avatar_link varchar(256),
  login VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id_user`)
);

CREATE TABLE IF NOT EXISTS posts (
  id_post INT UNSIGNED AUTO_INCREMENT,
  post_date DATETIME(6) NOT NULL,
  title varchar(256),
  text_content TEXT,
  quote_author varchar(256) default '',
  image_link varchar(256) default '',
  video_link varchar(256) default '',
  website_link varchar(256) default '',
  number_of_views INT,
  id_user INT,
  content_type VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id_post`)
);

CREATE INDEX index_user_post ON posts(id_user);

CREATE TABLE IF NOT EXISTS comments (
  id_comment INT UNSIGNED AUTO_INCREMENT,
  comment_date DATETIME(6) NOT NULL,
  comment_text TEXT NOT NULL,
  id_user INT,
  id_post INT,
  PRIMARY KEY (`id_comment`)
);

CREATE INDEX index_user_comment ON comments(id_user);
CREATE INDEX index_post_comment ON comments(id_post);

CREATE TABLE IF NOT EXISTS likes (
  id_user INT NOT NULL,
  id_post INT NOT NULL
);

CREATE INDEX index_user_like ON comments(id_user);
CREATE INDEX index_post_like ON comments(id_post);

CREATE TABLE IF NOT EXISTS subscriptions (
  id_subscriber INT NOT NULL,
  id_receiver_sub INT NOT NULL
);

CREATE INDEX subscriber_index ON subscriptions(id_subscriber);
CREATE INDEX receiver_sub_index ON subscriptions(id_receiver_sub);

CREATE TABLE IF NOT EXISTS messages (
  id_message INT UNSIGNED AUTO_INCREMENT,
  message_date DATETIME(6) NOT NULL,
  message_text TEXT NOT NULL,
  id_who_writed INT NOT NULL,
  id_for_who_writed INT NOT NULL,
  PRIMARY KEY (`id_message`)
);

CREATE INDEX sender_index ON messages(id_who_writed);
CREATE INDEX receiver_index ON messages(id_for_who_writed);

CREATE TABLE IF NOT EXISTS hashtags (
  id_post INT NOT NULL,
  hashtag_title varchar(64) NOT NULL
);

CREATE TABLE IF NOT EXISTS contentTypes (
  id_type TINYINT UNSIGNED AUTO_INCREMENT,
  content_type_title CHAR(1) NOT NULL,
  content_class_type VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id_type`)
);
