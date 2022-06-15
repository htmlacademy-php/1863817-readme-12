CREATE DATABASE IF NOT EXISTS readme;
USE readme;

CREATE TABLE IF NOT EXISTS users (
  id_user INT UNSIGNED AUTO_INCREMENT,
  user_login VARCHAR(64) NOT NULL,
  email VARCHAR(128) UNIQUE NOT NULL,
  password VARCHAR(128) NOT NULL,
  registration_date DATETIME(6) NOT NULL,
  avatar_link VARCHAR(256),
  PRIMARY KEY (`id_user`)
);

CREATE TABLE IF NOT EXISTS posts (
  id_post INT UNSIGNED AUTO_INCREMENT,
  post_date DATETIME(6) NOT NULL,
  title VARCHAR(256),
  text_content TEXT,
  quote_author VARCHAR(256) default '',
  image_link VARCHAR(256) default '',
  video_link VARCHAR(256) default '',
  website_link VARCHAR(256) default '',
  repost TINYINT(1) default 0,
  id_author INT(10) default NULL,
  original_post_id INT(10) default NULL,
  number_of_views INT,
  id_user INT UNSIGNED,
  content_type VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id_post`),
  FOREIGN KEY (`id_user`) REFERENCES users(`id_user`)
);

CREATE INDEX index_user_post ON posts(id_user);

CREATE TABLE IF NOT EXISTS comments (
  id_comment INT UNSIGNED AUTO_INCREMENT,
  comment_date DATETIME(6) NOT NULL,
  comment_text TEXT NOT NULL,
  id_user INT UNSIGNED,
  id_post INT UNSIGNED,
  PRIMARY KEY (`id_comment`),
  FOREIGN KEY (`id_user`) REFERENCES users(`id_user`),
  FOREIGN KEY (`id_post`) REFERENCES posts(`id_post`)
);

CREATE INDEX index_user_comment ON comments(id_user);
CREATE INDEX index_post_comment ON comments(id_post);

CREATE TABLE IF NOT EXISTS likes (
  id_user INT UNSIGNED,
  id_post INT UNSIGNED,
  likes_date DATETIME(6) NOT NULL,
  FOREIGN KEY (`id_user`) REFERENCES users(`id_user`),
  FOREIGN KEY (`id_post`) REFERENCES posts(`id_post`)
);

CREATE INDEX index_user_like ON comments(id_user);
CREATE INDEX index_post_like ON comments(id_post);

CREATE TABLE IF NOT EXISTS subscriptions (
  id_subscriber INT UNSIGNED,
  id_receiver_sub INT UNSIGNED,
  FOREIGN KEY (`id_subscriber`) REFERENCES users(`id_user`),
  FOREIGN KEY (`id_receiver_sub`) REFERENCES users(`id_user`)
);

CREATE INDEX subscriber_index ON subscriptions(id_subscriber);
CREATE INDEX receiver_sub_index ON subscriptions(id_receiver_sub);

CREATE TABLE IF NOT EXISTS messages (
  id_message INT UNSIGNED AUTO_INCREMENT,
  message_date DATETIME(6) NOT NULL,
  message_text TEXT NOT NULL,
  id_who_writed INT UNSIGNED NOT NULL,
  id_for_who_writed INT UNSIGNED NOT NULL,
  checked TINYINT(1) default 0,
  PRIMARY KEY (`id_message`),
  FOREIGN KEY (`id_who_writed`) REFERENCES users(`id_user`),
  FOREIGN KEY (`id_for_who_writed`) REFERENCES users(`id_user`)
);

CREATE INDEX sender_index ON messages(id_who_writed);
CREATE INDEX receiver_index ON messages(id_for_who_writed);

CREATE TABLE IF NOT EXISTS hashtags (
  id_post INT UNSIGNED,
  hashtag_title varchar(64) NOT NULL,
  FOREIGN KEY (`id_post`) REFERENCES posts(`id_post`)
);

CREATE TABLE IF NOT EXISTS contentTypes (
  id_type TINYINT UNSIGNED AUTO_INCREMENT,
  content_type_title CHAR(1) NOT NULL,
  content_class_type VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id_type`)
);

CREATE FULLTEXT INDEX searche_post on posts(title, text_content, quote_author);
CREATE FULLTEXT INDEX searche_post_by_tag on hashtags(hashtag_title);
