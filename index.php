<?php
require_once 'helpers.php';

if (connect () == false) {
  print("Ошибка подключения: " . mysqli_connect_error());
} else {
  $rows_for_types = doQuery(connect (), "SELECT * FROM contentTypes");
  $posts = doQuery(connect (), "SELECT * FROM posts JOIN users ON posts.id_user = users.id_user ORDER BY number_of_views ASC");
}

date_default_timezone_set('Europe/Moscow');

if (empty($_GET)) {
  $page_content = include_template('main.php', ['cards' => $posts, 'types' => $rows_for_types]);
  $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное']);
}

if (isset($_GET['post']) && empty($_GET['post']) === false) {
  $page_content = include_template('main.php', ['cards' => doQueryForType(), 'types' => $rows_for_types]);
  $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное']);
}

require_once 'post.php';
require_once 'add.php';

print($layout_content);
?>
