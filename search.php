<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

session_start();
isSessionExist();
$con =  connect();

$userId = $_SESSION['userId'];;
$keyWords = test_input($con, $_GET['search']);

if (substr($_GET['search'], 0, 1) === '#') {
  $hashtagJoin = "LEFT JOIN hashtags ON hashtags.id_post = posts.id_post";
  $match = 'hashtag_title';
} else {
  $hashtagJoin = "";
  $match = 'title, text_content, quote_author';
}

$sql = "SELECT posts.*, users.user_login, users.avatar_link, COUNT(comments.id_post) AS comments_amount,
(SELECT COUNT(*) FROM likes WHERE likes.id_post = posts.id_post) AS likes_amount ,
(SELECT COUNT(*) FROM likes WHERE likes.id_post = posts.id_post AND likes.id_user = $userId) AS amILikeThisPost
FROM posts
LEFT JOIN comments ON comments.id_post = posts.id_post
JOIN users ON posts.id_user = users.id_user " . $hashtagJoin .
  " WHERE MATCH($match) AGAINST('$keyWords')
GROUP BY posts.id_post, users.id_user
ORDER BY posts.post_date DESC";

$result = doQuery($con, $sql);

if ($result) {
  $page_content = include_template('search-result.php', ['cards' => $result, 'query' => $keyWords]);
} else {
  $page_content = include_template('no-results.php', ['query' => $keyWords]);
}

$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: результат поиска', 'query' => $keyWords, 'avatar' => getAvatarForUser($_SESSION['username'])]);

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
