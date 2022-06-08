<?php
require 'util/helpers.php';
require 'util/mysql.php';

session_start();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}

$con = connect();
$userId = $_SESSION['userId'];

if ($_GET['filter'] === 'all') {
  $subPosts = doQuery($con, "SELECT posts.*, users.user_login, users.avatar_link,
  (SELECT COUNT(*) FROM comments WHERE comments.id_post = posts.id_post) AS comments_count, COUNT(likes.id_post) AS likes_count
  FROM posts
  JOIN users ON posts.id_user = users.id_user
  JOIN subscriptions ON posts.id_user = subscriptions.id_receiver_sub
  left JOIN likes ON likes.id_post = posts.id_post
  WHERE id_subscriber = $userId
  GROUP BY posts.id_post");
} else {
  $filter = 'post-' . $_GET['filter'];
  $subPosts = doQuery($con, "SELECT posts.*, users.user_login, users.avatar_link,
  (SELECT COUNT(*) FROM comments WHERE comments.id_post = posts.id_post) AS comments_count, COUNT(likes.id_post) AS likes_count
  FROM posts
  JOIN users ON posts.id_user = users.id_user
  JOIN subscriptions ON posts.id_user = subscriptions.id_receiver_sub
  left JOIN likes ON likes.id_post = posts.id_post
  WHERE id_subscriber = $userId AND content_type = '$filter'
  GROUP BY posts.id_post");
}

$page_content = include_template('feed-template.php', ['posts' => $subPosts]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: моя лента', 'avatar' => getAvatarForUser($_SESSION['username'])]);

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
