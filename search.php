<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

session_start();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}

test_input($_GET['search']);

$keyWords = $_GET['search'];
$con = connect();

if (substr($_GET['search'], 0, 1) === '#') {
  $result = doQuery($con, "SELECT * FROM posts JOIN hashtags WHERE MATCH(hashtag_title) AGAINST('$keyWords') AND hashtags.id_post = posts.id_post ORDER BY post_date DESC");
} else {
  $result = doQuery($con, "SELECT * FROM posts WHERE MATCH(title, text_content, quote_author) AGAINST('$keyWords')");
}


foreach ($result as $key => $value) {
  $idPost = $value['id_post'];
  $idUser = $value['id_user'];
  $post = doQuery(connect(), "SELECT * FROM posts JOIN users ON posts.id_post = $idPost AND users.id_user = $idUser");
  $likes = doQuery(connect(), "SELECT * FROM likes WHERE id_post = $idPost");
  $post[0]['likes'] = count($likes);
  $comments = doQuery(connect(), "SELECT * FROM comments WHERE id_post = $idPost");
  $post[0]['comments'] = count($comments);
  $posts[] = $post[0];
}

if ($result) {
  $page_content = include_template('search-result.php', ['cards' => $posts, 'types' => $rows_for_types, 'query' => $keyWords]);
  $posts = [];
} else {
  $page_content = include_template('no-results.php', ['query' => $keyWords]);
}

$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: результат поиска', 'query' => $keyWords, 'avatar' => getAvatarForUser()]);

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
