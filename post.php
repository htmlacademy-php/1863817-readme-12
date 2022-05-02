<?php
require 'util/helpers.php';
require 'util/mysql.php';

if (connect () == false) {
  print("Ошибка подключения: " . mysqli_connect_error());
} else {
  if (isset($_GET['post-id']) && empty($_GET['post-id']) === false) {
    $card = getPostById();
    if (empty($card)) {
      $layout_content = include_template('layout.php', ['content' => 'ERROR 404', 'title' => 'readme: публикация']);
    } else {
      $page_content = include_template('post-details.php',
      [
        'card' => $card,
        'subscriptions' => getSubById(),
        'posts' => getAllPostsPostsById(),
        'likes' => getLikesForPost(),
        'comments' => getCommentsForPost(),
        'tags' => getTagsForPost()
      ]);
      $layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: публикация']);
    }
  }
}

if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
?>
