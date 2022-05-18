<?php
require 'util/helpers.php';
require 'util/mysql.php';
require 'util/validate.php';

session_start();

test_input($_GET['search']);

echo ('<pre>');
print_r($_GET);
echo ('</pre>');

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}

$keyWords = $_GET['search'];
$con = connect();

$result = doQuery($con, "SELECT * FROM posts WHERE MATCH(title, text_content, quote_author) AGAINST ('$keyWords')");


foreach ($result as $key => $value) {
  $idPost = $value['id_post'];
  $idUser = $value['id_user'];
  $post = doQuery(connect(), "SELECT * FROM posts JOIN users ON posts.id_post = $idPost AND users.id_user = $idUser");
  $posts[] = $post[0];
}


if ($result) {
  $page_content = include_template('search-result.php', ['cards' => $posts, 'types' => $rows_for_types, 'query' => $keyWords]);
  $posts = [];
} else {
  $page_content = include_template('no-results.php', ['query' => $keyWords]);
}

$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: результат поиска', 'avatar' => getAvatarForUser()]);

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
