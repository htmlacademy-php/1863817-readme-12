CREATE DATABASE IF NOT EXISTS readme;
DEFAULT CHARACTER SET utf8;
DEFAULT COLLATE utf8_general_ci;
USE readme;

CREATE TABLE IF NOT EXISTS users (
  id_user INT AUTO_INCREMENT UNIQUE PRIMARY KEY,
  email VARCHAR(128) NOT NULL UNIQUE,
  password VARCHAR(64) NOT NULL,
  registration_date DATETIME(64) NOT NULL,
  avatar_link VARCHAR(64),
);

CREATE TABLE IF NOT EXISTS posts (
  id_post INT AUTO_INCREMENT UNIQUE PRIMARY KEY,
  post_date DATETIME(64) NOT NULL,
  title TEXT,
  text_content TEXT,
  quote_author TEXT,
  image_link TEXT,
  video_link TEXT,
  website_link TEXT,
  number_of_views INT,
  id_user INT,
  content_type CHAR(1) NOT NULL,
);

CREATE INDEX i_for_user_post ON posts(id_user);

CREATE TABLE IF NOT EXISTS comments (
  id_comment INT AUTO_INCREMENT UNIQUE PRIMARY KEY,
  comment_date DATETIME(64) NOT NULL,
  comment_text TEXT NOT NULL,
  id_user INT,
  id_post INT,
);

CREATE INDEX i_for_user_comment ON comments(id_user);
CREATE INDEX i_for_post_comment ON comments(id_post);

CREATE TABLE IF NOT EXISTS likes (
  id_like INT AUTO_INCREMENT UNIQUE PRIMARY KEY,
  id_user INT NOT NULL,
  id_post INT NOT NULL,
);

CREATE INDEX i_for_user_like ON comments(id_user);
CREATE INDEX i_for_post_like ON comments(id_post);

CREATE TABLE IF NOT EXISTS subscriptions (
  id_subscription INT AUTO_INCREMENT UNIQUE PRIMARY KEY,
  id_who_subscription INT NOT NULL,
  id_for_who_subscription INT NOT NULL,
);

CREATE INDEX index_who_subscription ON subscriptions(id_who_subscription);
CREATE INDEX index_for_who_subscription ON subscriptions(id_for_who_subscription);

CREATE TABLE IF NOT EXISTS messages (
  id_message INT AUTO_INCREMENT UNIQUE PRIMARY KEY,
  message_date DATETIME(64) NOT NULL,
  message_text TEXT NOT NULL,
  id_who_writed INT NOT NULL,
  id_for_who_writed INT NOT NULL,
);

CREATE INDEX index_who_writed ON messages(id_who_writed);
CREATE INDEX index_for_who_writed ON messages(id_for_who_writed);

CREATE TABLE IF NOT EXISTS hashtags (
  id_hashtag INT AUTO_INCREMENT UNIQUE PRIMARY KEY,
  hashtag_title TEXT NOT NULL,
);

CREATE TABLE IF NOT EXISTS contentTypes (
  id_type TINYINT AUTO_INCREMENT UNIQUE PRIMARY KEY,
  content_type_title CHAR(1) NOT NULL,
  content_class_type VARCHAR(64) NOT NULL,
);
