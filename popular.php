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
  $postsWithoutLikes = doQueryForType();
}

$userId = $_SESSION['userId'];

foreach ($postsWithoutLikes as $key => $value) {
  $idPost = $value['id_post'];
  $likesAmount = doQuery(connect(), "SELECT COUNT(id_post) from likes where id_post = $idPost");
  $amILikeThisPost = doQuery(connect(), "SELECT * from likes where id_post = $idPost and id_user = $userId");
  if ($amILikeThisPost) {
    $value['amILikeThisPost'] = 1;
  }
  $value['likesAmount'] = $likesAmount[0]['COUNT(id_post)'];
  $posts[] = $value;
}

date_default_timezone_set('Europe/Moscow');

$pageAmount = ceil(count($posts) / 6);

if ($_GET['sort'] === 'likes') {
  usort($posts, function ($a, $b) {
    return ($a['likesAmount'] - $b['likesAmount']);
  });
} else if ($_GET['sort'] === 'data') {
  usort($posts, function ($a, $b) {
    return (strtotime($a['post_date']) < strtotime($b['post_date']));
  });
} else if ($_GET['sort'] === 'popular' || (!isset($_GET['post']) && !isset($_GET['sort']))) {
  usort($posts, function ($a, $b) {
    return ($a['number_of_views'] < $b['number_of_views']);
  });
}

if (count($posts) > 9) {
  $linkForMorePages = 1;
}

$pageNumber = $_GET['page'];

if (!isset($posts[$pageNumber * 6 + 1])) {
  $noMorePages = 1;
}

if ($linkForMorePage) {
  if ($pageNumber === 1) {
    $posts = array_slice($posts, 0, 6);
  } else {
    $posts = array_slice($posts, $pageNumber * 6 - 6, 6);
  }
}


$page_content = include_template('popular-template.php', ['cards' => $posts, 'types' => $rows_for_types, 'linkForMorePage' => $linkForMorePages, 'noMorePages' => $noMorePages]);
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное', 'avatar' => getAvatarForUser($_SESSION['username'])]);


if (isset($layout_content) && !empty($layout_content)) {
  print($layout_content);
}
