<?php
require 'util/helpers.php';
require 'util/mysql.php';

session_start();

if (!isset($_SESSION['username'])) {
  header('Location: /login.php');
}

if (connect() == false) {
  print("Ошибка подключения: " . mysqli_connect_error());
} else {
  $rows_for_types = doQuery(connect(), "SELECT * FROM contentTypes");
  $posts = doQuery(connect(), "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user ORDER BY number_of_views ASC");
}

date_default_timezone_set('Europe/Moscow');

if (empty($_GET)) {
  $page_content = include_template('popular-template.php', ['cards' => $posts, 'types' => $rows_for_types]);
  $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное', 'avatar' => getAvatarForUser()]);
}

if (isset($_GET['post']) && empty($_GET['post']) === false) {
  $page_content = include_template('popular-template.php', ['cards' => doQueryForType(), 'types' => $rows_for_types]);
  $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное', 'avatar' => getAvatarForUser()]);
}

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
