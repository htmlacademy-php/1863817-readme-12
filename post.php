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
  if (isset($_GET['post-id']) && empty($_GET['post-id']) === false) {
    $card = getPostById();
    if (empty($card)) {
      $layout_content = include_template('layout.php', ['content' => 'ERROR 404', 'title' => 'readme: публикация', 'avatar' => getAvatarForUser()]);
    } else {
      $page_content = include_template(
        'post-details.php',
        [
          'card' => $card,
          'subscriptions' => getSubById(),
          'posts' => getAllPostsPostsById(),
          'likes' => getEssenceForPost('likes'),
          'comments' => getEssenceForPost('comments'),
          'tags' => getEssenceForPost('hashtags')
        ]
      );
      $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: публикация', 'avatar' => getAvatarForUser()]);
    }
  }
}

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
